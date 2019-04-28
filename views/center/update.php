<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Center */

$this->title                   = 'به روزرسانی مرکز ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'مراکز', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'به روزرسانی';
?>
<div class="center-update">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>