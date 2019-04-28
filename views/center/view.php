<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Center */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'مراکز', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="center-view">
	
	<h1><?= Html::encode($this->title) ?></h1>
	
	<p>
		<?= Html::a('به روزرسانی', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('حذف', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'آیا از حذف اطمینان دارید؟',
				'method' => 'post',
			],
		]) ?>
	</p>
	
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
			'id',
			'title',
			'lat',
			'long',
			'type',
			'description:ntext',
		],
	]) ?>

</div>