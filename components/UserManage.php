<?php

namespace app\components;


use app\models\Bot;
use app\models\Channel;
use app\models\CoinLog;
use app\models\ErrorLog;
use app\models\Notification;
use app\models\User;

class UserManage
{
	
	public static function register($id, $username, $first_name, $last_name,
									$device_id, $serial_number, $model, $manufacture, $brand,
									$api_version, $app_version)
	{
		// todo Will be done later
	}
	
	public static function Login($id, $username, $first_name,
								 $last_name, $device_id, $serial_number,
								 $model, $manufacture, $brand, $api_version,
								 $app_version, $checksum)
	{
		// todo will be done later
		
	}
	
	public static function getData($user_id, $token)
	{
		$user = User::findOne(['telegram_id' => $user_id]);
		if (!$user) {
			Result::runf700();
		}
		if ($user->api_token != $token) {
			Result::r403();
		}
		
		$user = User::findOne(['api_token' =>$token]);
		
		$result['user']['id'] = $user->id;
		$result['user']['username'] = $user->username;
		$result['user']['email'] = $user->email;
		$result['user']['register_date'] = jdate('d F y، ساعت H:i', $user->register_date);
		$result['user']['phone_number'] = $user->phone_number;
		$result['user']['picture_id'] = $user->picture_id;
		$result['user']['gender'] = $user->gender;
		$result['user']['height'] = $user->height;
		$result['user']['weight'] = $user->weight;
		$result['user']['status'] = $user->status;
		$result['user']['confidence_number'] = $user->confidence_number;
		$result['user']['confidence_number_enabled'] = $user->confidence_number_enabled;
		$result['user']['emergency_number_enabled'] = $user->emergency_number_enabled;
		$result['user']['pollution_notif_alert'] = $user->pollution_notif_alert;
		$result['user']['helper_enabled'] = $user->helper_enabled;
		$result['user']['first_name'] = $user->first_name;
		$result['user']['last_name'] = $user->last_name;
		$result['user']['healthy_notif'] = $user->healthy_notif;
		
		return $result;
	}
	
}