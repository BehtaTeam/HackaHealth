<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Option */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'تنظیمات', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="option-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('به روزرسانی', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'value',
        ],
    ]) ?>

</div>
