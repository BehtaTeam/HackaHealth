<?php

use app\components\Helper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title                   = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'مدیریت کاربران', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
		<?= Html::a('به روزرسانی', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
		<?= Html::a('حذف', ['delete', 'id' => $model->id], [
			'class' => 'btn btn-danger',
			'data'  => [
				'confirm' => 'آیا از حذف این آیتم اطمینان دارید؟',
				'method'  => 'post',
			],
		]) ?>
    </p>
	
	<?= DetailView::widget([
		'model'      => $model,
		'attributes' => [
			'id',
			'telegram_id',
			'username',
			'first_name',
			'last_name',
			'phone',
			'access_token',
			[
				'label' => 'تاریخ ثبت نام',
				'value' => Helper::timestamp_to_shamsi($model->register_date)
			],
			[
				'label' => 'تاریخ آخرین فعالیت',
				'value' => Helper::timestamp_to_shamsi($model->last_activity_time)
			],
			[
				'label' => 'برآیند موجودی',
				'value' => $model->stock - $model->spent
			],
			'last_login_ip',
			'device_id',
			'serial_number',
			'model',
			'manufacture',
			'brand',
			'api_version',
			'app_version',
			'active',
			'type',
			'email:email',
			'password',
		],
	]) ?>

    <h2><?= 'کانال های کاربر' ?></h2>
	
	<?php Pjax::begin(); ?>
	
	<?=
	/* @var $channel \app\models\Channel */
	GridView::widget([
		'dataProvider' => $channels,
		'pager'        => [
			'firstPageLabel' => 'اولین',
			'lastPageLabel'  => 'آخرین'
		],
		'columns'      => [
			[
				'attribute' => 'id',
				'label'     => 'id',
				'format'    => 'raw',
				'value'     => function ($channel) {
					return Html::a($channel->id, "/channel/$channel->id");
				},
			],
			'channel_id',
			'name',
			'title',
			'description',
			'member_count',
			[
				'attribute' => 'last_update',
				'label'     => 'آخرین به روزرسانی',
				'value'     => function ($channel) {
					return Helper::timestamp_to_shamsi($channel['last_update']);
				},
			],
			'channel_group',
			[
				'attribute' => 'category_id',
				'label'     => 'دسته بندی',
				'value'     => function ($channel) {
					return $channel->category->title;
				}
			],
			[
				'attribute' => 'bot_id',
				'label'     => 'بات',
				'value'     => function ($channel) {
					return $channel->bot->username;
				}
			],
			[
				'attribute' => 'ban_until',
				'label'     => 'توقیف تا',
				'value'     => function ($channel) {
					return $channel['ban_until'] == 0 ? 'توقیف نیست' : Helper::timestamp_to_eshamsi($channel['ban_until']);
				}
			],
			[
				'attribute' => 'active',
				'label'     => 'فعال است؟',
				'value'     => function ($channel) {
					return $channel->active ? 'بله' : 'خیر';
				}
			],
		],
	]); ?>
	<?php Pjax::end(); ?>

    <h2><?= 'خرید های کاربر' ?></h2>
	
	<?php Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $store_log,
		'pager'        => [
			'firstPageLabel' => 'اولین',
			'lastPageLabel'  => 'آخرین'
		],
		'columns'      => [
			'id',
			'stock',
			'price',
			'developer_payload',
			'token',
			'purchase_state',
			[
				'attribute' => 'date',
				'label'     => 'تاریخ',
				'value'     => function ($store_log) {
					return Helper::timestamp_to_shamsi($store_log['date']);
				}
			],
		],
	]); ?>
	<?php Pjax::end(); ?>

    <h2><?= 'رویدادهای کاربر' ?></h2>
	
	<?php Pjax::begin(); ?>
	<?= GridView::widget([
		'dataProvider' => $logs,
		'pager'        => [
			'firstPageLabel' => 'اولین',
			'lastPageLabel'  => 'آخرین'
		],
		'columns'      => [
			'id',
			'amount',
			[
				'label' => 'توضیحات',
				'value' => function ($logs) {
					return $logs->type0->description;
				}
			],
			[
				'attribute' => 'date',
				'label'     => 'تاریخ',
				'value'     => function ($logs) {
					return Helper::timestamp_to_shamsi($logs['date']);
				}
			],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>