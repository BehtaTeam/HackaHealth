<?php

use app\components\Helper;
use app\models\Channel;
use app\models\ChannelGroup;
use app\models\ExchangeParticipant;
use app\models\StatusType;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExchangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'جزئیات تبادل ها';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="exchange-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>
 
	<?php Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'pager'        => [
			'firstPageLabel' => 'اولین',
			'lastPageLabel'  => 'آخرین'
		],
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'id',
			[
				'attribute' => 'special_channel_id',
				'format'    => 'raw',
				'filter' => ArrayHelper::map(Channel::find()->all(), 'id', 'name'),
				'value'     => function ($model) {
					$special_channel = $model->specialChannel;
					$user            = $special_channel->admin;
					$name            = $user->first_name . ' ' . $user->last_name;
					$group           = ChannelGroup::findOne($special_channel->channel_group);
					
					$show_comment = '<button type="button" class="btn btn-success" data-toggle="modal" data-target="#' . $model->id . '">' . $special_channel->name . '</button>';
					$show_comment
								  .= '
                    <div class="modal inmodal fade" id="' . $model->id . '" tabindex="-1" role="dialog"  aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">بستن</span></button>
                                        </div>
                                        <div class="modal-body">
                                        ' . DetailView::widget([
							'model'      => $special_channel,
							'attributes' => [
								'id',
								'channel_id',
								'chat_id',
								[
									'attribute' => 'admin_id',
									'label'     => 'مدیر کانال',
									'format'    => 'raw',
									'value'     => Html::a($name, "/user/$special_channel->admin_id")
								],
								'name',
								'title:ntext',
								'description',
								'member_count',
								[
									'label' => 'تاریخ آخرین به روزرسانی',
									'value' => Helper::timestamp_to_shamsi($special_channel->last_update)
								],
								[
									'attribute' => 'channel_group',
									'label'     => 'کاربران',
									'value'     => "از $group->from_count تا $group->to_count کاربر",
								],
								[
									'attribute' => 'category_id',
									'label'     => 'دسته بندی',
									'value'     => $special_channel->category->title
								],
								[
									'attribute' => 'category_id',
									'label'     => 'بات',
									'value'     => $special_channel->bot->name
								],
								[
									'attribute' => 'ban_until',
									'label'     => 'توقیف تا',
									'value'     => $special_channel->ban_until == 0 ? 'توقیف نیست' : Helper::timestamp_to_eshamsi($special_channel->ban_until)
								],
								[
									'label' => 'فعال است؟',
									'value' => $special_channel->active ? 'بله' : 'خیر'
								],
							],
						]) . '
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
				'attribute' => 'channels_group',
				'label'     => 'کاربران',
				'value'     => function ($model) {
					$exchange_group = ChannelGroup::findOne($model->channels_group);
					
					return "از $exchange_group->from_count تا $exchange_group->to_count کاربر";
				}
			],
			[
				'label'  => 'مشاهده شرکت کننده ها',
				'format' => 'raw',
				'value'  => function ($model) {
					$text         = '';
					$participants = ExchangeParticipant::find()->where(['exchange_id' => $model->id])->all();
					
					foreach ($participants as $participant) {
						$text .= Html::a($participant->channel->name, "/channel/$participant->channel_id") . '، ';
					}
					
					$text = mb_substr(trim($text), 0, -1);
					return $text;
				},
			],
			[
				'attribute' => 'date',
				'value'     => function ($model) {
					return Helper::timestamp_to_eshamsi($model['date']);
				}
			],
			[
				'attribute' => 'finish_date',
				'value'     => function ($model) {
					return Helper::timestamp_to_eshamsi($model['finish_date']);
				}
			],
			[
				'attribute' => 'status',
				'value'     => function ($model) {
					return $model->status0->title;
				},
				'filter' => ArrayHelper::map(StatusType::find()->all(), 'id', 'title'),
			],
		],
	]); ?>
	<?php Pjax::end(); ?></div>