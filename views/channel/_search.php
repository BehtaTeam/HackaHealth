<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ChannelSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="channel-search">
	
	<?php $form = ActiveForm::begin([
		'action' => ['index'],
		'method' => 'get',
	]); ?>
	
	<?= $form->field($model, 'id') ?>
	
	<?= $form->field($model, 'channel_id') ?>
	
	<?= $form->field($model, 'chat_id') ?>
	
	<?php // echo $form->field($model, 'title') ?>
	
	<?php // echo $form->field($model, 'description') ?>
	
	<?php // echo $form->field($model, 'member_count') ?>
	
	<?php // echo $form->field($model, 'last_update') ?>
	
	<?php // echo $form->field($model, 'channel_group') ?>
	
	<?php // echo $form->field($model, 'category_id') ?>
	
	<?php // echo $form->field($model, 'bot_id') ?>
	
	<?php // echo $form->field($model, 'ban_until') ?>
	
	<?php echo $form->field($model, 'participate_at_1')->checkbox() ?>
	
	<?php echo $form->field($model, 'participate_at_2')->checkbox() ?>
	
	<?php echo $form->field($model, 'participate_at_3')->checkbox() ?>
	
	<?php echo $form->field($model, 'participate_at_4')->checkbox() ?>
	
	<?php // echo $form->field($model, 'active') ?>

    <div class="form-group">
		<?= Html::submitButton('جست و جو', ['class' => 'btn btn-primary']) ?>
		<?= Html::resetButton('بازنشانی', ['class' => 'btn btn-default']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>