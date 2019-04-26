<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ExchangeInfoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'آمار تبادل ها';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="exchange-info-index">

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
			'start_hour',
			'finish_hour',
			[
				'attribute' => 'date',
				'value'     => function ($model) {
					return Helper::timestamp_to_cshamsi($model->date);
				}
			],
			'same_channels_count',
			'special_channels_count',
			[
				'attribute' => 'first_request_date',
				'value'     => function ($model) {
					return Helper::timestamp_to_cshamsi($model->first_request_date);
				}
			],
			[
				'attribute' => 'last_request_date',
				'value'     => function ($model) {
					return Helper::timestamp_to_cshamsi($model->last_request_date);
				}
			],
			'total_coin_earned',
			'total_coin_back',
			'violated_channels_count',
			[
				'attribute' => 'first_checking_date',
				'value'     => function ($model) {
					return ($model->first_checking_date > 0) ? Helper::timestamp_to_cshamsi($model->first_checking_date) : 'تبادل در حال اجراست';
				}
			],
			[
				'attribute' => 'last_checking_date',
				'value'     => function ($model) {
					return ($model->last_checking_date > 0) ? Helper::timestamp_to_cshamsi($model->last_checking_date) : 'تبادل در حال اجراست';
				}
			],
			[
				'label' => 'جزئیات',
                'format'=> 'raw',
				'value'     => function ($model) {
					return Html::a('مشاهده', ['/exchange-detail/index', 'id' => $model->id], ['class' => 'btn btn-success']);
				}
			],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>