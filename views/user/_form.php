<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'telegram_id')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'phone')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'access_token')->textInput(['maxlength' => true]) ?>
 
	<?= $form->field($model, 'stock')->textInput() ?>
	
	<?= $form->field($model, 'spent')->textInput() ?>
	
	<?= $form->field($model, 'last_login_ip')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'device_id')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'serial_number')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'model')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'manufacture')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'brand')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'api_version')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'app_version')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'active')->checkbox() ?>
	
	<?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>
	
	<div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'ایجاد' : 'به روزرسانی', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>