<?php

use app\components\Helper;
use app\models\ChannelGroup;
use app\models\StatusType;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SpecialExchangeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'درخواست های ویژه';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="special-exchange-index">

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
			
			'id',
			[
				'attribute' => 'channel_id',
				'format'    => 'raw',
				'value'     => function ($channel) {
					return Html::a($channel->channel->name, "/channel/$channel->channel_id");
				},
			],
			[
				'attribute' => 'from_group',
				'value'     => function ($channel) {
					$group = ChannelGroup::findOne($channel->from_group);
					
					return "از $group->from_count تا $group->to_count کاربر";
				},
			],
			[
				'attribute' => 'target_group',
				'value'     => function ($channel) {
					$group = ChannelGroup::findOne($channel->target_group);
					
					return "از $group->from_count تا $group->to_count کاربر";
				},
			],
			'paid_amount',
			'paid_amount_back',
			[
				'attribute' => 'date',
				'filter'    => false,
				'value'     => function ($model) {
					return Helper::timestamp_to_shamsi($model->date);
				}
			],
			[
				'attribute' => 'status',
				'filter'    => ArrayHelper::map(StatusType::findAll([2, 3, 4]), 'id', 'title'),
				'value'     => function ($channel) {
					return $channel->status0->title;
				}
			],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>