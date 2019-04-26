<?php

use app\components\Helper;
use app\models\StoreLog;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\StoreLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'خریدهای کاربران';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="store-log-index">

    <div class="row">
        <div class="col-lg-6">
            <a href="#">
                <div class="widget style1 blue-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-circle fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> فروش 24 ساعت گذشته </span>
                            <h2 class="font-bold"><?= StoreLog::last24sale() . ' تومان' ?></h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-6">
            <a href="#">
                <div class="widget style1 blue-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-circle fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> فروش امروز </span>
                            <h2 class="font-bold"><?= StoreLog::from0sale() . ' تومان' ?></h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-lg-6">
            <a href="#">
                <div class="widget style1 blue-bg">
                    <div class="row">
                        <div class="col-xs-4">
                            <i class="fa fa-circle fa-5x"></i>
                        </div>
                        <div class="col-xs-8 text-right">
                            <span> فروش  کل </span>
                            <h2 class="font-bold"><?= StoreLog::total_sale() . ' تومان' ?></h2>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <!--<p>
        <? /*= Html::a('Create Store Log', ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>-->
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
				'label'  => 'کاربر',
				'format' => 'raw',
				'value'  => function ($model) {
					return Html::a(Html::encode($model->user_id), "/user/$model->user_id");
				},
			],
			'stock',
			'price',
			'developer_payload',
			'token',
			'market',
			[
				'label' => 'تاریخ',
				'value' => function ($model) {
					return Helper::timestamp_to_shamsi($model['date']);
				}
			],
			'purchase_state',
		],
	]); ?>
	<?php Pjax::end(); ?></div>
