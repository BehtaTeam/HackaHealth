<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'مدیریت کاربران';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_search', ['model' => $searchModel]); ?>
	
	<?php Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		'pager'        => [
			'firstPageLabel' => 'اولین',
			'lastPageLabel'  => 'آخرین'
		],
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'id',
			'telegram_id',
			'username',
			'first_name',
			'last_name',
			[
				'label' => 'موجودی',
				'value' => function ($model) {
					return $model->stock - $model->spent;
				}
			],
			[
				'attribute' => 'register_date',
				'label'     => 'تاریخ ثبت نام',
				'value'     => function ($model) {
					return Helper::timestamp_to_shamsi($model->register_date);
				}
			],
			[
				'attribute' => 'last_activity_time',
				'label'     => 'آخرین تاریخ فعالیت',
				'value'     => function ($model) {
					return Helper::timestamp_to_shamsi($model->last_activity_time);
				}
			],
			
			
			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?></div>