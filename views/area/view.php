<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Area */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ناحیه ها', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-view">
	
	<h1><?= Html::encode($this->title) ?></h1>
	
	<p>
		<?= Html::a('به روزرسانی', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('حذف', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data' => [
				'confirm' => 'آیا اطمینان به حذف دارید؟',
				'method' => 'post',
			],
		]) ?>
	</p>
	
	<?= DetailView::widget([
		'model' => $model,
		'attributes' => [
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
		],
	]) ?>

</div>