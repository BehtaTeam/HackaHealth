<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\AreaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'ناحیه ها';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
		<?= Html::a('ایجاد ناحیه جدید', ['create'], ['class' => 'btn btn-success']) ?>
		<?= Html::a('اختصاص آلودگی های رندوم بین 0 تا 300 به نواحی', 'http://koota.bio/api/location/set-randoms', ['class' => 'btn btn-primary']) ?>
    </p>
	<?php Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		'filterModel'  => $searchModel,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'id',
			'lat1',
			'long1',
			'lat2',
			'long2',
			'lat3',
			'long3',
			'lat4',
			'long4',
			'pollute',
			[
				'label'  => 'وضعیت هوا',
				'format' => 'raw',
				'value'  => function ($row) {
					$value = $row->pollute;
					
					if ($value < 51) {
						$pollute = 'هوای پاک';
					} elseif ($value < 101) {
						$pollute = 'هوای نیمه پاک';
					} elseif ($value < 151) {
						$pollute = 'هوای ناسالم برای بیماران قلبی';
					} elseif ($value < 201) {
						$pollute = 'هوای ناسالم برای عموم';
					} elseif ($value < 251) {
						$pollute = 'هوای بسیار ناسالم';
					} elseif ($value < 301) {
						$pollute = 'هوای خطرناک';
					}
					
					return $pollute;
				},
			],
			
			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>