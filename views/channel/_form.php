<?php

use app\models\Bot;
use app\models\Category;
use app\models\ChannelGroup;
use app\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Channel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="channel-form">
	
	<?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'channel_id')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?= $form->field($model, 'chat_id')->textInput(['readonly' => !$model->isNewRecord]) ?>
	
	<?php $requesters = [];
	$requesters[null] = null;
	foreach (User::find()->all() as $user) {
		$requesters[$user->id] = $user->first_name . ' ' . $user->last_name;
	}
	?>
	
	<?= $form->field($model, 'admin_id')->dropDownList($requesters) ?>
	
	<?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'title')->textInput() ?>
	
	<?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'member_count')->textInput() ?>
	
	<?php $items = [];
	$items[null] = null;
	foreach (ChannelGroup::find()->all() as $item) {
		$items[$item->id] = 'از ' . $item->from_count . ' تا ' . $item->to_count;
	}
	?>
	
	<?= $form->field($model, 'channel_group')->dropDownList($items)?>
	
	<?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'title')) ?>
	
	<?= $form->field($model, 'bot_id')->dropDownList(ArrayHelper::map(Bot::find()->all(), 'id', 'name')) ?>
 
	<?= $form->field($model, 'participate_at_1')->checkbox() ?>
	
	<?= $form->field($model, 'participate_at_2')->checkbox() ?>
	
	<?= $form->field($model, 'participate_at_3')->checkbox() ?>
	
	<?= $form->field($model, 'participate_at_4')->checkbox() ?>
	
	<?= $form->field($model, 'active')->checkbox() ?>

    <div class="form-group">
		<?= Html::submitButton($model->isNewRecord ? 'ایجاد' : 'به روزرسانی', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>
	
	<?php ActiveForm::end(); ?>

</div>