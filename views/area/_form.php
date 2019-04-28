<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Area */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'lat1')->textInput() ?>
	
	<?= $form->field($model, 'long1')->textInput() ?>
	
	<?= $form->field($model, 'lat2')->textInput() ?>
	
	<?= $form->field($model, 'long2')->textInput() ?>
	
	<?= $form->field($model, 'lat3')->textInput() ?>
	
	<?= $form->field($model, 'long3')->textInput() ?>
	
	<?= $form->field($model, 'lat4')->textInput() ?>
	
	<?= $form->field($model, 'long4')->textInput() ?>
	
	<?= $form->field($model, 'pollute')->textInput() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'ایجاد' : 'به روزرسانی', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>