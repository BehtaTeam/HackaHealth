<?php

use app\components\Helper;
use app\models\StatusType;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'بررسی گزارش ها';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-violation-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>فقط گزارش های مربوط به ساعت گذشته در جریان تبادل نمایش داده می شوند</p>
	
	<?php Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'pager'        => [
			'firstPageLabel' => 'اولین',
			'lastPageLabel'  => 'آخرین'
		],
		'columns'      => [
			'id',
			[
				'attribute' => 'reporter_id',
				'label'     => 'گزارش دهنده',
				'value'     => function ($model) {
					if (isset($model->reporter_id)) {
						$user = User::findOne(['id' => $model->reporter_id]);
						
						return $user->first_name . ' ' . $user->last_name;
					} else {
						return 'گزارش سیستمی';
					}
				},
			],
			[
				'attribute' => 'channel_id',
				'format'    => 'raw',
				'value'     => function ($model) {
					return Html::a($model->channel->name, "/channel/$model->channel_id") . ' ' .
						   Html::a('مشاهده', 'https://www.telegram.me/' . $model->channel->name, ['class' => 'btn btn-success', 'target' => '_blank']);
				},
			],
			'exchange_id',
			[
				'attribute' => 'reason',
				'label'     => 'نوع گزارش',
				'value'     => function ($model) {
					return $model->reason0->title;
				},
			],
			[
				'attribute' => 'description',
				'format'    => 'raw',
				'value'     => function ($model) {
					$show_comment = '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#' . $model->id . '">نمایش توضیحات</button>';
					$show_comment
								  .= '
                    <div class="modal inmodal fade" id="' . $model->id . '" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">بستن</span></button>
                                        </div>
                                        <div class="modal-body">
                                        ' . Html::encode($model->description) . '
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal">بستن</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    ';
					
					if ($model->description)
						return $show_comment;
					else
						return 'بدون توضیحات';
				},
			],
			[
				'attribute' => 'date',
				'value'     => function ($model) {
					return Helper::timestamp_to_shamsi($model->date);
				},
			],
			[
				'attribute' => 'status',
				'label'     => 'وضعیت',
				'format'    => 'raw',
				'value'     => function ($model) {
					if ($model->status == 10)
						return 'عدم نیاز';
					
					return '<form action="' . Yii::$app->homeUrl . 'channel-violation/approve' . '" method="post">
                              <input type="hidden" name="' . Yii::$app->request->csrfParam . '" value="' . Yii::$app->request->csrfToken . '" />
                              <input type="hidden" name="id" value="' . $model->id . '" />
                              <button type="submit" name="status" value="' . ($model->status == 11 ? 12 : 11) . '" class="btn">' . ($model->status == 12 ? 'تایید شده' : '<font color="red">تایید نشده</font>') . '</button>
                            </form>';
				},
			],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>