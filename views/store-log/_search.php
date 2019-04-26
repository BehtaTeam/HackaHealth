<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\StoreLogSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="store-log-search">
	
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
	
	<?= $form->field($model, 'id') ?>
	
	<?= $form->field($model, 'user_id') ?>
	
	<?= $form->field($model, 'stock') ?>
	
	<?= $form->field($model, 'price') ?>
	
	<?= $form->field($model, 'developer_payload') ?>
	
	<?php // echo $form->field($model, 'bazaar_token') ?>
	
	<?php // echo $form->field($model, 'purchase_state') ?>
	
	<?php // echo $form->field($model, 'market') ?>
	
	<?php // echo $form->field($model, 'date') ?>

    <div class="form-group">
		<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>
