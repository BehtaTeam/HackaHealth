<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Bot */

$this->title                   = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'مدیریت بات ها', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('به روزرسانی', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('حذف', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data'  => [
				'confirm' => 'آیا به حذف اطمینان دارید؟',
				'method'  => 'post',
			],
		]) ?>
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'id',
			'name',
			'username',
			'token',
			[
				'label' => 'فعال است؟',
				'value' => $model['active'] ? 'بله' : 'خیر'
			],
		],
	]) ?>

</div>