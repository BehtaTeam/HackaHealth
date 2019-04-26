<?php

namespace app\components;


use app\models\Channel;
use app\models\CoinLog;
use app\models\ErrorLog;
use app\models\Notification;
use app\models\User;

class UserManage
{
	
	public static function register($telegram_id, $username, $first_name, $last_name, $phone,
									$device_id, $serial_number, $model, $manufacture, $brand,
									$api_version, $app_version)
	{
		if ($telegram_id < 10) {
			Result::rinf400();
		}
		
		$exist_user = User::findOne(['telegram_id' => $telegram_id]);
		
		if (!$exist_user) { // ghablan mowjod boode in karbar
			$at = (string)Helper::stringGenerator(15);
			
			$new_user                     = new User();
			$new_user->telegram_id        = $telegram_id;
			$new_user->username           = $username;
			$new_user->first_name         = $first_name;
			$new_user->last_name          = $last_name;
			$new_user->phone              = $phone;
			$new_user->stock              = 10;
			$new_user->access_token       = $at;
			$new_user->register_date      = time();
			$new_user->last_activity_time = time();
			$new_user->device_id          = $device_id;
			$new_user->serial_number      = $serial_number;
			$new_user->model              = $model;
			$new_user->manufacture        = $manufacture;
			$new_user->brand              = $brand;
			$new_user->api_version        = $api_version;
			$new_user->app_version        = $app_version;
			if (isset($_SERVER['REMOTE_ADDR']))
				$new_user->last_login_ip = $_SERVER['REMOTE_ADDR'];
			$new_user->save();
			if ($new_user->hasErrors()) {
				ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($new_user->getErrors()));
				Result::saving_error600($new_user->getErrors());
			}
			
			CoinLog::Set($new_user->id, 10, 1);
			
			$user = User::findOne($new_user->id);
			if (!$user) {
				ErrorLog::set($telegram_id, __FILE__, __LINE__, 'مشکل ذخیره سازی کاربر');
				
				Result::saving_error600('مشکل در سطر ' . __LINE__);
			}
		} else {
			$at = $exist_user->access_token;
			
			$exist_user->username           = $username;
			$exist_user->first_name         = $first_name;
			$exist_user->last_name          = $last_name;
			$exist_user->phone              = $phone;
			$exist_user->last_activity_time = time();
			$exist_user->device_id          = $device_id;
			$exist_user->serial_number      = $serial_number;
			$exist_user->model              = $model;
			$exist_user->manufacture        = $manufacture;
			$exist_user->brand              = $brand;
			$exist_user->api_version        = $api_version;
			$exist_user->app_version        = $app_version;
			if (isset($_SERVER['REMOTE_ADDR']))
				$exist_user->last_login_ip = $_SERVER['REMOTE_ADDR'];
			$exist_user->update();
			if ($exist_user->getErrors()) {
				ErrorLog::set($exist_user->telegram_id, __FILE__, __LINE__, json_encode($exist_user->getErrors()));
			}
			
			$user = User::findOne($exist_user->id);
			if (!$user) {
				Result::saving_error600('مشکل در سطر ' . __LINE__);
			}
		}
		
		$result                 = [];
		$result['telegram_id']  = $telegram_id;
		$result['coin']         = $user->stock - $user->spent;
		$result['access_token'] = $at;
		
		return $result;
	}
	
	public static function Login($telegram_id, $at, $username, $first_name,
								 $last_name, $phone, $device_id, $serial_number,
								 $model, $manufacture, $brand, $api_version,
								 $app_version, $checksum)
	{
		$exist_user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$exist_user) {
			$exist_user = User::findOne(['username' => $username]);
			if (!$exist_user) {
				ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id, username= $username");
				
				Result::runf700();
			}
		}
		
		if ($exist_user->access_token != $at) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			
			Result::r403();
		}
		
		if ($exist_user->active != 1) {
			Result::rBan();
		}
		
		UserManage::refreshLastActivity($exist_user->id);
		
		$exist_user->username           = $username;
		$exist_user->first_name         = $first_name;
		$exist_user->last_name          = $last_name;
		$exist_user->phone              = $phone;
		$exist_user->device_id          = $device_id;
		$exist_user->serial_number      = $serial_number;
		$exist_user->manufacture        = $manufacture;
		$exist_user->model              = $model;
		$exist_user->brand              = $brand;
		$exist_user->last_activity_time = time();
		$exist_user->api_version        = $api_version;
		$exist_user->app_version        = $app_version;
		if (isset($_SERVER['REMOTE_ADDR']))
			$exist_user->last_login_ip = $_SERVER['REMOTE_ADDR'];
		$exist_user->update();
		if ($exist_user->getErrors()) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, json_encode($exist_user->getErrors()));
			Result::saving_error600(__LINE__);
		}
		
		$user = User::findOne($exist_user->id);
		if (!$user) {
			Result::saving_error600(__LINE__);
		}
		
		$unread_message_count = Notification::find()->where(['user_id' => $user->id, 'message_read' => 0])->count();
		
		$list['exchange_hours'] = Info::ExchangeHours();
		$list['channel_groups'] = Info::ChannelGroups();
		
		$new_checksum = md5(serialize($list));
		
		if ($new_checksum == $checksum) {
			$result['list'] = ['value' => 'zero'];
		} else {
			$result['list'] = $list;
		}
		
		//	$result                         = [];
		$result['telegram_id']          = $telegram_id;
		$result['coin']                 = $user->stock - $user->spent;
		$result['version_code']         = (int)Helper::setting(1);
		$result['required_update']      = (int)Helper::setting(2);
		$result['support_user']         = '@behta_support2';
		$result['update_message']       = Helper::setting(3);
		$result['unread_message_count'] = (int)$unread_message_count;
		$result['checksum']             = $new_checksum;
		$result['caption']['title']     = '';
		$result['caption']['message']   = 'پیام و محتوا';
		$result['caption']['link']      = 'http://google.com';
		
		if($app_version < 814){
			$result['caption']['title']     = 'به روزرسانی تله تبادل';
			$result['caption']['message']   = 'لطفا تله تبادل را به روزرسانی نمایید.';
			$result['caption']['link']      = 'https://myket.ir/app/com.behta.tabadol/?lang=fa';
		}
		
		return $result;
		
	}
	
	public static function refreshLastActivity($user_id)
	{
		$user                     = User::findOne($user_id);
		$user->last_activity_time = time();
		$user->update();
		
		return 1;
	}
	
	public static function userChannels($telegram_id, $at, $page_number, $per_page)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id, username= $username");
			Result::runf700();
		}
		
		if ($user->access_token != $at) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		$start = ($page_number - 1) * $per_page;
		
		$list = [];
		
		foreach (Channel::find()->where(['admin_id' => $user->id])->limit($per_page)->offset($start)->orderBy('id DESC')->all() as $item) {
			$temp['channel_id']       = $item->channel_id;
			$temp['chat_id']          = $item->chat_id;
			$temp['name']             = $item->name;
			$temp['title']            = $item->title;
			$temp['description']      = $item->description;
			$temp['channel_group']    = $item->channel_group;
			$temp['category_id']      = $item->category_id;
			$temp['ban']              = $item->ban_until > time() ? 1 : 0;
			$temp['participate_at_1'] = $item->participate_at_1;
			$temp['participate_at_2'] = $item->participate_at_2;
			$temp['participate_at_3'] = $item->participate_at_3;
			$temp['participate_at_4'] = $item->participate_at_4;
			
			$list[] = $temp;
			
		}
		
		$result['list']        = $list;
		$result['total_count'] = Channel::find()->where(['admin_id' => $user->id, 'active' => 1])->count();
		
		return $result;
	}
}