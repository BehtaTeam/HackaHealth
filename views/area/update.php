<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Area */

$this->title = 'Update Area: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'ناحیه ها', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'به روزرسانی';
?>
<div class="area-update">
	
	<h1><?= Html::encode($this->title) ?></h1>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>