<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Center */

$this->title                   = 'ایجاد مرکز';
$this->params['breadcrumbs'][] = ['label' => 'مراکز', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="center-create">

    <h1><?= Html::encode($this->title) ?></h1>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>