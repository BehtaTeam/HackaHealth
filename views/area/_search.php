<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AreaSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-search">
	
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
	
	<?= $form->field($model, 'id') ?>
	
	<?= $form->field($model, 'lat1') ?>
	
	<?= $form->field($model, 'long1') ?>
	
	<?= $form->field($model, 'lat2') ?>
	
	<?= $form->field($model, 'long2') ?>
	
	<?php echo $form->field($model, 'lat3') ?>
	
	<?php echo $form->field($model, 'long3') ?>
	
	<?php echo $form->field($model, 'lat4') ?>
	
	<?php echo $form->field($model, 'long4') ?>
	
	<?php echo $form->field($model, 'pollute') ?>

    <div class="form-group">
		<?= Html::submitButton('جست و جو', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('ریست', ['class' => 'btn btn-default']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>