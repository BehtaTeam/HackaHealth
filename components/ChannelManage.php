<?php

namespace app\components;


use app\models\Bot;
use app\models\Channel;
use app\models\ChannelGroup;
use app\models\ChannelViolation;
use app\models\CoinLog;
use app\models\ErrorLog;
use app\models\Exchange;
use app\models\ExchangeParticipant;
use app\models\SpecialExchange;
use app\models\User;
use Exception;
use yii\db\Connection;

class ChannelManage
{
	
	
	public static function add($telegram_id, $access_token, $channel_id,
							   $name, $title, $description, $category_id, $bot_id, $join_link)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			
			Result::runf700();
			
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$exist_before = Channel::findOne(['channel_id' => $channel_id]);
		if ($exist_before)
			Result::exist_before601('این کانال قبلا اعلام شده است');
		
		UserManage::refreshLastActivity($user->id);
		
		$bot = Bot::findOne(['active' => 1, 'id' => $bot_id]);
		if (!$bot)
			Result::rBNT701();
		
		if (Telegram::getChatAdministrators("@$name", $bot->id)['ok'] === false) {
			Result::rNotAdmin405();
		}
		
		$params       = [
			'chat_id' => "@$name"
		];
		$channel_info = Helper::httpPost('https://api.telegram.org/bot' . $bot->token . '/getChat', $params);
		
		if ($channel_info['ok'] === false) {
			Result::telgram_problem900();
		}
		
		$chat_id = $channel_info['result']->id;
		
		$member_count = (int)Telegram::getChatMembersCount("$chat_id", $bot->id)['result'];
		
		$channel_group = ChannelGroup::find()->where("$member_count BETWEEN from_count AND to_count")->one()->number;
		
		if(!$join_link)
			Result::update3();
		
		$channel                = new Channel();
		$channel->channel_id    = $channel_id;
		$channel->chat_id       = $chat_id;
		$channel->admin_id      = $user->id;
		$channel->name          = $name;
		$channel->title         = $title;
		$channel->join_link     = $join_link;
		$channel->description   = $description;
		$channel->last_update   = time();
		$channel->member_count  = $member_count;
		$channel->channel_group = $channel_group;
		$channel->category_id   = $category_id;
		$channel->bot_id        = $bot->id;
		$channel->active        = 1;
		$channel->save();
		if ($channel->hasErrors()) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($channel->getErrors()));
			Result::saving_error600($channel->getErrors());
		}
		
		$result['bot']['id']                = $bot->id;
		$result['bot']['username']          = $bot->username;
		$result['channel']['member_count']  = $member_count;
		$result['channel']['channel_group'] = $channel_group;
		
		return $result;
	}
	
	public static function participate($telegram_id, $access_token, $channel_id, $at_1, $at_2, $at_3, $at_4)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		if ($channel->member_count < 10)
			Result::rCHLowCount903();
		
		if ($channel->ban_until > time()) {
			Result::rChannelViolation901();
		}
		
		$bot = Bot::findOne(['active' => 1, 'id' => $channel->bot_id]);
		if (!$bot)
			Result::rBNT701();
		
		if (Telegram::getChatAdministrators("$channel->chat_id", $bot->id)['ok'] === false) {
			Result::rNotAdmin405();
		}
		
		$member_count  = (int)Telegram::getChatMembersCount("$channel->chat_id", $bot->id)['result'];
		$channel_group = ChannelGroup::find()->where("$member_count BETWEEN from_count AND to_count")->one()->number;
		
		$channel->participate_at_1 = $at_1;
		$channel->participate_at_2 = $at_2;
		$channel->participate_at_3 = $at_3;
		$channel->participate_at_4 = $at_4;
		$channel->member_count     = $member_count;
		$channel->last_update      = time();
		$channel->channel_group    = $channel_group;
		$channel->update();
		
		if ($channel->getErrors()) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($channel->getErrors()));
			Result::saving_error600('مشکل در ذخیره سازی اطلاعات');
		}
		
		$result['at_1'] = $channel->participate_at_1;
		$result['at_2'] = $channel->participate_at_2;
		$result['at_3'] = $channel->participate_at_3;
		$result['at_4'] = $channel->participate_at_4;
		
		return $result;
	}
	
	public static function Info($telegram_id, $access_token, $channel_id)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		$bot = Bot::findOne(['active' => 1, 'id' => $channel->bot_id]);
		if (!$bot)
			Result::rBNT701();
		
		if (Telegram::getChatAdministrators("$channel->chat_id", $bot->id)['ok'] === false) {
			$result['is_admin']         = false;
			$result['is_admin_message'] = 'متاسفانه این بات در کانال مربوطه مدیر نمی باشد.';
		} else {
			$result['is_admin']         = true;
			$result['is_admin_message'] = '';
			
			$member_count           = (int)Telegram::getChatMembersCount("$channel->chat_id", $bot->id)['result'];
			$channel_group          = ChannelGroup::find()->where("$member_count BETWEEN from_count AND to_count")->one()->number;
			$channel->last_update   = time();
			$channel->member_count  = $member_count;
			$channel->channel_group = $channel_group;
			$channel->update();
		}
		
		$telegram = new Telegram($channel);
		
		$info           = $telegram->getChat();
		$res            = $info['result'];
		$channel->title = $res->title;
		$channel->name  = $res->username;
		$channel->update();
		
		
		$result['channel']['channel_id']       = $channel->channel_id;
		$result['channel']['chat_id']          = $channel->chat_id;
		$result['channel']['name']             = $channel->name;
		$result['channel']['title']            = $channel->title;
		$result['channel']['description']      = $channel->description;
		$result['channel']['join_link']        = $channel->join_link;
		$result['channel']['channel_group']    = $channel->channel_group;
		$result['channel']['category_id']      = $channel->category_id;
		$result['channel']['participate_at_1'] = $channel->participate_at_1;
		$result['channel']['participate_at_2'] = $channel->participate_at_2;
		$result['channel']['participate_at_3'] = $channel->participate_at_3;
		$result['channel']['participate_at_4'] = $channel->participate_at_4;
		
		$number_of_waiting_special_exchanges    = SpecialExchange::find()->where(['channel_id' => $channel->id, 'status' => 2])->count();
		$number_of_completing_special_exchanges = SpecialExchange::find()->where(['channel_id' => $channel->id, 'status' => 3])->count();
		$number_of_completed_special_exchanges  = SpecialExchange::find()->where(['channel_id' => $channel->id, 'status' => 4])->count();
		
		
		$result['number_of_waiting_special_exchanges']    = $number_of_waiting_special_exchanges;
		$result['number_of_completing_special_exchanges'] = $number_of_completing_special_exchanges;
		$result['number_of_completed_special_exchanges']  = $number_of_completed_special_exchanges;
		
		
		if ($channel->ban_until > time()) {
			$result['ban_status']  = true;
			$result['ban_message'] = 'این کانال تا تاریخ ' . jdate('d F y، ساعت H:i', $channel->ban_until) . ' بن است';
		} else {
			$result['ban_status']  = false;
			$result['ban_message'] = '';
		}
		$result['bot']['id']       = $bot->id;
		$result['bot']['username'] = $bot->username;
		
		return $result;
	}
	
	public static function specialExchangeRequest($telegram_id, $access_token, $channel_id, $target_group)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		if ($channel->admin_id != $user->id)
			Result::r403();
		
		if ($channel->ban_until > time()) {
			Result::rChannelViolation901();
		}
		
		$bot = Bot::findOne(['active' => 1, 'id' => $channel->bot_id]);
		if (!$bot)
			Result::rBNT701();
		
		if (Telegram::getChatAdministrators("$channel->chat_id", $bot->id)['ok'] !== false) {
			$member_count  = (int)Telegram::getChatMembersCount("$channel->chat_id", $bot->id)['result'];
			$channel_group = ChannelGroup::find()->where("$member_count BETWEEN from_count AND to_count")->one()->number;
			
			$channel->member_count  = $member_count;
			$channel->channel_group = $channel_group;
			$channel->last_update   = time();
			$channel->update();
		}
		
		
		if ($channel->getErrors()) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($channel->getErrors()));
			Result::saving_error600('مشکل در ذخیره سازی اطلاعات');
		}
		
		$target_group = ChannelGroup::findOne(['number' => $target_group]);
		
		$price = $target_group->price;
		
		if ($user->stock - $user->spent < $price) {
			Result::not_enough_stock100();
		}
		
		$connection = \Yii::$app->db;
		
		$transaction = $connection->beginTransaction();
		
		try {
			$user->spent = $user->spent + $price;
			$user->update();
			if ($user->hasErrors()) {
				Result::saving_error600('مشکل در ذخیره سازی اطلاعات');
			}
			
			CoinLog::Set($user->id, -$price, 2);
			
			$special_exchange               = new SpecialExchange();
			$special_exchange->channel_id   = $channel->id;
			$special_exchange->from_group   = $channel->channel_group;
			$special_exchange->target_group = $target_group->number;
			$special_exchange->paid_amount  = $price;
			$special_exchange->date         = time();
			$special_exchange->status       = 2;
			$special_exchange->save();
			if ($special_exchange->hasErrors()) {
				Result::saving_error600('مشکل در ذخیره سازی اطلاعات');
			}
			$transaction->commit();
		} catch (\Throwable $e) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, $e->getMessage());
			$transaction->rollBack();
			Result::saving_error600('مشکل در ذخیره سازی اطلاعات');
		}
		
		$result['special_exchange_id'] = $special_exchange->id;
		$result['coin']                = $user->stock - $user->spent;
		
		return $result;
		
	}
	
	public static function getExchangeCount($telegram_id, $access_token, $channel_id)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		if ($channel->admin_id != $user->id)
			Result::r403();
		
		$result['exchange_count'] = ExchangeParticipant::find()->where(['channel_id' => $channel->id])->count();
		
		return $result;
	}
	
	public static function setBot($telegram_id, $access_token, $channel_id, $bot_id)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		if ($channel->admin_id != $user->id)
			Result::r403();
		
		$bot = Bot::findOne(['active' => 1, 'id' => $bot_id]);
		if (!$bot)
			Result::rBNT701();
		
		if (Telegram::getChatAdministrators("$channel->chat_id", $bot->id)['ok'] === false) {
			Result::rNotAdmin405();
		}
		
		$telegram = new Telegram($channel);
		
		$info           = $telegram->getChat();
		$res            = $info['result'];
		$channel->title = $res->title;
		$channel->name  = $res->username;
		$channel->update();
		
		$member_count  = (int)Telegram::getChatMembersCount("$channel->chat_id", $bot->id)['result'];
		$channel_group = ChannelGroup::find()->where("$member_count BETWEEN from_count AND to_count")->one()->number;
		
		$channel->member_count  = $member_count;
		$channel->channel_group = $channel_group;
		$channel->last_update   = time();
		$channel->update();
		
		if ($channel->getErrors()) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($channel->getErrors()));
			Result::saving_error600('مشکل در ذخیره سازی اطلاعات');
		}
		
		$result['bot']['id']       = $bot->id;
		$result['bot']['username'] = $bot->username;
		
		return $result;
		
	}
	
	public static function editInfo($telegram_id, $access_token, $channel_id, $title, $description, $category_id, $join_link)
	{
		if(!$join_link)
			Result::update3();
		
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		if ($channel->admin_id != $user->id)
			Result::r403();
		
		$channel->title       = $title;
		$channel->description = $description;
		$channel->join_link   = $join_link;
		$channel->category_id = $category_id;
		$channel->update();
		
		if ($channel->getErrors()) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($channel->getErrors()));
			Result::saving_error600('مشکل در ذخیره سازی اطلاعات');
		}
		
		$result['success'] = 1;
		
		return $result;
		
	}
	
	public static function exchangeList($telegram_id, $access_token, $channel_id, $page_number, $per_page)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		if ($channel->admin_id != $user->id)
			Result::r403();
		
		$start = ($page_number - 1) * $per_page;
		
		$list = [];
		
		foreach (ExchangeParticipant::find()->where(['channel_id' => $channel->id])->limit($per_page)->offset($start)->orderBy('id DESC')->all() as $item) {
			$exchange               = $item->exchange;
			$temp['exchange_id']    = $item->exchange_id;
			$temp['channel_id']     = $item->channel_id;
			$temp['channel_status'] = $item->status0->title;
			$temp['has_violation']  = 0;
			$temp['violation']      = '';
			if ($item->violation_id != null) {
				$temp['has_violation'] = 1;
				$temp['violation']     = $item->violation->reason0->description;
			}
			$temp['channels_group'] = $exchange->channels_group;
			$hour                   = jdate('H', $exchange->date);
			$to_hour                = jdate('H', $exchange->date + 3600);
			$temp['date']           = jdate('d F y، ', $exchange->date) . "از ساعت $hour تا " . ($to_hour);
			
			$temp['exchange_status_id']            = $exchange->status0->id;
			$temp['exchange_status']               = $exchange->status0->title;
			$temp['participant_channels']          = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->count();
			$temp['participant_channels_violated'] = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->andWhere('violation_id IS NOT NULL')->count();
			
			
			$list[] = $temp;
			
		}
		
		$result['list']        = $list;
		$result['total_count'] = ExchangeParticipant::find()->where(['channel_id' => $channel->id])->count();
		
		return $result;
	}
	
	public static function exchangeParticipantsList($telegram_id, $access_token, $exchange_id)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$exchange = Exchange::findOne($exchange_id);
		if (!$exchange)
			Result::rExNotFound708();
		
		$list = [];
		
		foreach (ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->orderBy('id DESC')->all() as $item) {
			$channel               = $item->channel;
			$temp['id']            = $channel->channel_id;
			$temp['name']          = $channel->name;
			$temp['has_violation'] = 0;
			$temp['violation']     = '';
			if ($item->violation_id != null) {
				$temp['has_violation'] = 1;
				$temp['violation']     = $item->violation->reason0->description;
			}
			
			$list[] = $temp;
			
		}
		
		$special_channel                         = $exchange->specialChannel;
		$result['special_channel']['channel_id'] = $special_channel->channel_id;
		$result['special_channel']['name']       = $special_channel->name;
		$result['exchange_id']                   = $exchange->id;
		$hour                                    = jdate('H', $exchange->date);
		$to_hour                                 = jdate('H', $exchange->finish_date);
		$result['date']                          = jdate('d F y، ', $exchange->date) . "از ساعت $hour تا " . $to_hour;
		$result['can_report']                    = $exchange->status == 6 ? 0 : 1;
		$result['list']                          = $list;
		$result['total_count']                   = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->count();
		
		return $result;
	}
	
	public static function specialExchangeList($telegram_id, $access_token, $channel_id, $status,
											   $page_number, $per_page)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		if ($user->active != 1) {
			Result::rBan();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id, 'admin_id' => $user->id]);
		if (!$channel)
			Result::rChNotFound702();
		
		if ($channel->admin_id != $user->id)
			Result::r403();
		
		$start = ($page_number - 1) * $per_page;
		
		$list = [];
		
		$q = SpecialExchange::find()->where(['channel_id' => $channel->id])->limit($per_page)->offset($start)->orderBy('id DESC');
		if ($status != 0)
			$q->andWhere(['status' => (int)$status]);
		
		foreach ($q->all() as $item) {
			$temp['special_id']       = $item->id;
			$exchange                 = Exchange::find()->where(['special_exchange_id' => $item->id])->one();
			$temp['exchange_id']      = $exchange->id;
			$temp['channel_id']       = $item->channel_id;
			$temp['from_group']       = $item->from_group;
			$temp['target_group']     = $item->target_group;
			$temp['paid_amount']      = $item->paid_amount;
			$temp['paid_amount_back'] = $item->paid_amount_back;
			$temp['date']             = Helper::timestamp_to_shamsi($item->date);
			$temp['status']           = $item->status0->title;
			$temp['status_id']        = $item->status;
			$temp['done_time']        = '';
			if ($item->status == 4)
				$temp['done_time'] = Helper::timestamp_to_shamsi($exchange->date);
			$temp['participant_channels']          = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->count();
			$temp['participant_channels_violated'] = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->andWhere('violation_id IS NOT NULL')->count();
			
			
			$list[] = $temp;
			
		}
		
		$result['list']        = $list;
		$result['total_count'] = SpecialExchange::find()->where(['channel_id' => $channel->id])->count();
		
		return $result;
	}
	
	public static function groupStatus()
	{
		$list = [];
		foreach (ChannelGroup::find()->all() as $item) {
			$temp['number']     = $item->number;
			$temp['from_count'] = $item->from_count;
			$temp['to_count']   = $item->to_count;
			$temp['price']      = $item->price;
			$temp['status']     = (Channel::find()->where(['channel_group' => $item->number, 'active' => 1])->count() > 2) ? true : false;
			
			$list[] = $temp;
			
		}
		
		$result['list'] = $list;
		
		return $result;
	}
	
	public static function violationReport($telegram_id, $access_token, $channel_id, $exchange_id, $type, $description)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		$channel = Channel::findOne(['channel_id' => $channel_id]);
		if (!$channel)
			Result::rChNotFound702();
		
		$exchange = Exchange::findOne($exchange_id);
		if (!$exchange)
			Result::rExNotFound708();
		
		if ($exchange->date > time()) {
			Result::rOutOfRange902();
		}
		
		if ($exchange->finish_date != NULL AND $exchange->finish_date < time()) {
			Result::rOutOfRange902();
		}
		
		$connection  = \Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			$channel_violation              = new ChannelViolation();
			$channel_violation->reporter_id = $user->id;
			$channel_violation->channel_id  = $channel->id;
			$channel_violation->exchange_id = $exchange_id;
			$channel_violation->reason      = $type;
			$channel_violation->description = $description;
			$channel_violation->date        = time();
			$channel_violation->status      = 11;
			$channel_violation->save();
			if ($channel_violation->hasErrors()) {
				ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($channel_violation->getErrors(), JSON_UNESCAPED_UNICODE));
			}
			
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();
			
			Result::saving_error600($e->getMessage());
		}
		
		$result['success'] = 1;
		
		return $result;
		
	}
	
}