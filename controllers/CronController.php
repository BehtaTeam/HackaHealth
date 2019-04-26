<?php

namespace app\controllers;

use app\components\ExchangeManage;
use app\components\Telegram;
use app\models\Bot;
use app\models\Channel;
use app\models\ChannelGroup;
use Yii;
use yii\web\Controller;

class CronController extends Controller
{
	public $enableCsrfValidation = false;
	
	
	public function actionExchangeStart($key, $at)
	{
		if ($key != 2083) {
			return 0;
		}
		
		ExchangeManage::startExchange((int)$at);
	}
	
	public function actionExchangeFinish($key)
	{
		if ($key != 2083) {
			return 0;
		}
		
		ExchangeManage::finishExchange();
		
	}
	
	public function actionExchangeDelete($key)
	{
		if ($key != 2082) {
			return 0;
		}
		
		ExchangeManage::deleteExchange();
		
	}
	
	public function actionChannels($key)
	{
		if ($key != 2082) {
			return 0;
		}
		
		$channels = Channel::find()
			->where(['active' => 1])
			->andWhere('ban_until <' . time())
			->andWhere('last_update <' . (time() - 3600 * 24 * 3))
			->andWhere('bot_id IS NOT NULL')
			->andWhere('(participate_at_1 + participate_at_2 + participate_at_3 + participate_at_4) > 0')
			->orderBy('RAND()')
			->limit(200)
			->all();
		
		/* @var $channel Channel */
		foreach ($channels as $channel) {
			usleep(20000);
			
			$bot = Bot::findOne(['active' => 1, 'id' => $channel->bot_id]);
			if (!$bot)
				continue;
			
			if (Telegram::getChatAdministrators("$channel->chat_id", $bot->id)['ok'] === false) {
				$channel->active           = 0;
				$channel->participate_at_1 = 0;
				$channel->participate_at_2 = 0;
				$channel->participate_at_3 = 0;
				$channel->participate_at_4 = 0;
				$channel->update();
				continue;
			}
			
			$telegram = new Telegram($channel);
			
			$info           = $telegram->getChat();
			$result         = $info['result'];
			$channel->title = $result->title;
			$channel->name  = $result->username;
			$channel->update();
			
			$member_count  = (int)Telegram::getChatMembersCount("$channel->chat_id", $bot->id)['result'];
			$channel_group = ChannelGroup::find()->where("$member_count BETWEEN from_count AND to_count")->one()->number;
			
			if ($member_count < 10)
				$channel->active = 0;
			
			$channel->member_count  = $member_count;
			$channel->channel_group = $channel_group;
			$channel->last_update   = time();
			$channel->update();
		}
	}
	
}
