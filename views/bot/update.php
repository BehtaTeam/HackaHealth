<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */

$this->title                   = 'به روزرسانی ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'مدیریت بات ها', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'به روزرسانی';
?>
<div class="bot-update">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>