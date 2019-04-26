<?php

use app\components\Helper;
use app\models\ChannelGroup;
use app\models\ChannelViolation;
use app\models\Exchange;
use app\models\ExchangeParticipant;
use app\models\SpecialExchange;
use app\models\User;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\Channel */

$this->title                   = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'مدیریت کانال ها', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('به روزرسانی', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('دریافت و به روزرسانی اطلاعات کانال از تلگرام', ['update-info', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
		<?= Html::a('اعلام عدم توقیف بودن', ['unban', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>
	
	<?php
	$user = $model->admin;
	$name = $model->admin->first_name . ' ' . $model->admin->last_name;
	
	$group = ChannelGroup::findOne($model->channel_group);
	
	?>
	<?=
	
	DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'id',
			'channel_id',
			'chat_id',
			[
				'attribute' => 'admin_id',
				'label'     => 'مدیر کانال',
				'format'    => 'raw',
				'value'     => Html::a($name, "/user/$model->admin_id")
			],
			'name',
			'title:ntext',
			'description',
			'member_count',
			[
				'label' => 'تاریخ آخرین به روزرسانی',
				'value' => Helper::timestamp_to_shamsi($model->last_update)
			],
			[
				'attribute' => 'channel_group',
				'label'     => 'کاربران',
				'value'     => "از $group->from_count تا $group->to_count کاربر",
			],
			[
				'attribute' => 'category_id',
				'label'     => 'دسته بندی',
				'value'     => $model->category->title
			],
			[
				'attribute' => 'category_id',
				'label'     => 'بات',
				'value'     => $model->bot->name
			],
			[
				'attribute' => 'ban_until',
				'label'     => 'توقیف تا',
				'value'     => $model->ban_until == 0 ? 'توقیف نیست' : Helper::timestamp_to_eshamsi($model->ban_until)
			],
			[
				'label' => 'شرکت در تبادل اول',
				'value' => $model->participate_at_1 ? 'بله' : 'خیر'
			],
			[
				'label' => 'شرکت در تبادل دوم',
				'value' => $model->participate_at_2 ? 'بله' : 'خیر'
			],
			[
				'label' => 'شرکت در تبادل سوم',
				'value' => $model->participate_at_3 ? 'بله' : 'خیر'
			],
			[
				'label' => 'شرکت در تبادل چهارم',
				'value' => $model->participate_at_4 ? 'بله' : 'خیر'
			],
			[
				'label' => 'فعال است؟',
				'value' => $model->active ? 'بله' : 'خیر'
			],
		],
	]) ?>

    <h2>تبادل های هم اعضا</h2>
	
	<?php
	/* @var $model app\models\ExchangeParticipant */
	
	$dataProvider = new ActiveDataProvider([
		'query' => ExchangeParticipant::find()->where('channel_id=' . $model->id)->orderBy('id DESC'),
	]);
	
	Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			'id',
			'exchange_id',
			[
				'attribute' => 'violation_id',
				'value'     => function ($model) {
					return ($model->violation_id) ? $model->violation->description : 'بدون تخلف';
				}
			],
			[
				'attribute' => 'status',
				'value'     => function ($model) {
					return $model->status0->title;
				}
			],
			[
				'label' => 'شروع تبادل',
				'value' => function ($model) {
					return Helper::timestamp_to_eshamsi($model->exchange->date);
				}
			],
			[
				'label' => 'وضعیت تبادل',
				'value' => function ($model) {
					return $model->exchange->status0->title;
				}
			],
			[
				'label' => 'کانال های شرکت کننده',
				'value' => function ($model) {
					return count($model->exchange->exchangeParticipants);
				}
			],
		],
	]); ?>
	<?php Pjax::end(); ?>

    <h2>تبادل های ویژه</h2>
	
	<?php
	/* @var $model app\models\ExchangeParticipant */
	
	$dataProvider = new ActiveDataProvider([
		'query' => SpecialExchange::find()->where('channel_id=' . $model->id)->orderBy('id DESC'),
	]);
	
	Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			'id',
			[
				'label' => 'شماره پیگیری تبادل',
				'value' => function ($model) {
					$exchange = Exchange::find()->where(['special_exchange_id' => $model->id])->one();
					
					return $exchange->id;
				}
			],
			[
				'attribute' => 'from_group',
				'value'     => function ($model) {
					$group = ChannelGroup::findOne($model->from_group);
					
					return "از $group->from_count تا $group->to_count کاربر";
				}
			],
			[
				'attribute' => 'target_group',
				'value'     => function ($model) {
					$group = ChannelGroup::findOne($model->target_group);
					
					return "از $group->from_count تا $group->to_count کاربر";
				}
			],
			'paid_amount',
			'paid_amount_back',
			[
				'attribute' => 'status',
				'label'     => 'وضعیت',
				'value'     => function ($model) {
					return $model->status0->title;
				}
			],
			[
				'label' => 'شروع تبادل',
				'value' => function ($model) {
					return Helper::timestamp_to_eshamsi($model->date);
				}
			],
			[
				'label' => 'وضعیت تبادل',
				'value' => function ($model) {
					$exchange = Exchange::find()->where(['special_exchange_id' => $model->id])->one();
					
					return $exchange->status0->title;
				}
			],
			[
				'label' => 'کانال های شرکت کننده',
				'value' => function ($model) {
					$exchange = Exchange::find()->where(['special_exchange_id' => $model->id])->one();
					
					return count($exchange->exchangeParticipants);
				}
			],
		],
	]); ?>
	<?php Pjax::end(); ?>

    <h2>تخلف ها</h2>
	
	<?php
	$dataProvider = new ActiveDataProvider([
		'query' => ChannelViolation::find()->orderBy('id DESC')->where(['channel_id' => $model->id]),
	]);
	
	Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
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
					
					return $show_comment;
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