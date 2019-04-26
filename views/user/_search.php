<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">
	
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
	

	
	<?php  echo $form->field($model, 'phone') ?>
	
	<?php // echo $form->field($model, 'access_token') ?>
	
	<?php // echo $form->field($model, 'register_date') ?>
	
	<?php // echo $form->field($model, 'last_activity_time') ?>
	
	<?php // echo $form->field($model, 'stock') ?>
	
	<?php // echo $form->field($model, 'spent') ?>
	
	<?php  echo $form->field($model, 'last_login_ip') ?>
	
	<?php // echo $form->field($model, 'device_id') ?>
	
	<?php // echo $form->field($model, 'serial_number') ?>
	
	<?php // echo $form->field($model, 'model') ?>
	
	<?php // echo $form->field($model, 'manufacture') ?>
	
	<?php // echo $form->field($model, 'brand') ?>
	
	<?php // echo $form->field($model, 'api_version') ?>
	
	<?php // echo $form->field($model, 'app_version') ?>
	
	<?php // echo $form->field($model, 'active') ?>
	
	<?php  echo $form->field($model, 'type') ?>
	
	<?php // echo $form->field($model, 'email') ?>
	
	<?php // echo $form->field($model, 'password') ?>
	
	<div class="form-group">
		<?= Html::submitButton('جست و جو', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('بازنشانی', ['class' => 'btn btn-default']) ?>
	</div>
	
	<?php ActiveForm::end(); ?>

</div>