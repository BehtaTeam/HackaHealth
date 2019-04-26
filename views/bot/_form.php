<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bot-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'token')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'active')->checkbox() ?>
	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'ایجاد' : 'به روزرسانی', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>