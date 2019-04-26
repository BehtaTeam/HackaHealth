<?php

use app\components\Helper;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ChannelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title                   = 'مدیریت کانال ها';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channel-index">

    <h1><?= Html::encode($this->title) ?></h1>
	<?php echo $this->render('_search', ['model' => $searchModel]); ?>
	
	<?php Pjax::begin(); ?>    <?= GridView::widget([
		'dataProvider' => $dataProvider,
		'pager'        => [
			'firstPageLabel' => 'اولین',
			'lastPageLabel'  => 'آخرین'
		],
		'filterModel'  => $searchModel,
		'columns'      => [
			[
				'attribute' => 'id',
				'format'    => 'raw',
				'value'     => function ($channel) {
					return Html::a($channel->id, "/channel/$channel->id");
				},
			],
			[
				'attribute' => 'admin_id',
				'label'     => 'مدیر کانال',
				'format'    => 'raw',
				'value'     => function ($channel) {
					$user = $channel->admin;
					
					return Html::a($user->first_name . ' ' . $user->last_name, "/user/$channel->admin_id");
				},
			],
			'name',
			'title',
			'description',
			/*[
				'attribute' => 'description',
				'value'     => function ($channel) {
					return mb_convert_encoding($channel->description, 'UTF-8', 'utf8mb4_bin');
				},
			],*/
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
			// 'participate_at_1',
			// 'participate_at_2',
			// 'participate_at_3',
			// 'participate_at_4',
			// 'active',
			
			['class' => 'yii\grid\ActionColumn'],
		],
	]); ?>
	<?php Pjax::end(); ?>
</div>