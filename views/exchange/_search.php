<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExchangeInfoSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="exchange-info-search">
	
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
	
	<?= $form->field($model, 'id') ?>
	
	<?= $form->field($model, 'start_hour') ?>
	
	<?= $form->field($model, 'finish_hour') ?>
	
	<?= $form->field($model, 'date') ?>
	
	<?= $form->field($model, 'same_channels_count') ?>
	
	<?php // echo $form->field($model, 'special_channels_count') ?>
	
	<?php // echo $form->field($model, 'first_request_date') ?>
	
	<?php // echo $form->field($model, 'last_request_date') ?>
	
	<?php // echo $form->field($model, 'total_coin_earned') ?>
	
	<?php // echo $form->field($model, 'total_coin_back') ?>
	
	<?php // echo $form->field($model, 'violated_channels_count') ?>
	
	<?php // echo $form->field($model, 'first_checking_date') ?>
	
	<?php // echo $form->field($model, 'last_checking_date') ?>

    <div class="form-group">
		<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>