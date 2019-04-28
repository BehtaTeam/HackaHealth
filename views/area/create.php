<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Area */

$this->title = 'ایجاد ناحیه';
$this->params['breadcrumbs'][] = ['label' => 'ناحیه ها', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="area-create">
	
	<h1><?= Html::encode($this->title) ?></h1>
	
	<?= $this->render('_form', [
		'model' => $model,
	]) ?>

</div>