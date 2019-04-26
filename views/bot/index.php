<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'مدیریت بات ها';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('ایجاد بات جدید', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
	<?php Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns'      => [
			['class' => 'yii\grid\SerialColumn'],
			
			'id',
			'name',
			'username',
			'token',
			[
				'label' => 'فعال است؟',
				'value' => function ($dataProvider) {
					return $dataProvider['active'] ? 'بله' : 'خیر';
				}
			],
			[
				'class'    => 'yii\grid\ActionColumn',
				'header'   => 'غیرفعال سازی بات برای همه',
				'template' => '{my_button}',
				'buttons'  => [
					'my_button' => function ($url, $model, $key) {
						$b = '<button class="btn btn-danger dim" type="button"><i class="fa fa-minus"></i></button>';
						
						return Html::a($b, ['deactive', 'id' => $model->id], [
							'data'  => [
								'confirm' => 'آیا به غیر فعال سازی اطمینان دارید؟',
								'method'  => 'post',
							],
						]);
					},
				],
			],
			
			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>