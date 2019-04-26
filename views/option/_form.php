<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Option */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="option-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	<?= $form->field($model, 'value')->textInput() ?>


    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'Create' : 'به روزرسانی', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
