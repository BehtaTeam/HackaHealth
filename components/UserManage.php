<?php

namespace app\components;


use app\models\Bot;
use app\models\Channel;
use app\models\CoinLog;
use app\models\ErrorLog;
use app\models\HeartRate;
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
		$user = User::findOne(['id' => $user_id]);
		if (!$user) {
			Result::runf700();
		}
		if ($user->api_token != $token) {
			Result::r403();
		}
		
		$user = User::findOne(['api_token' => $token]);
		
		$result['user']['id']            = $user->id;
		$result['user']['username']      = $user->username;
		$result['user']['email']         = $user->email;
		$result['user']['register_date'] = jdate('d F y، ساعت H:i', $user->register_date);
		$result['user']['phone_number']  = $user->phone_number;
		$result['user']['picture_id']    = $user->picture_id;
		$result['user']['gender']        = $user->gender;
		$height_in_meter                 = $user->height / 100;
		$result['user']['bmi_number']    = $user->weight / ($height_in_meter * $height_in_meter);
		$bmi_number                      = $result['user']['bmi_number'];
		
		if ($bmi_number < 18.5) {
			$bmi_status = 'کمبود وزن';
		} elseif ($bmi_number < 24.9) {
			$bmi_status = 'وزن نرمال';
		} elseif ($bmi_number < 29.9) {
			$bmi_status = 'اضافه وزن';
		} elseif ($bmi_number < 34.9) {
			$bmi_status = 'چاق';
		} elseif ($bmi_number < 35) {
			$bmi_status = 'خیلی چاق';
		}
		
		$result['user']['bmi_status'] = $bmi_status;
		$result['user']['age']        = $user->age;
		$result['user']['height']     = $user->height;
		$result['user']['weight']     = $user->weight;
		$result['user']['status']     = $user->status;
		
		$status = $result['user']['status'];
		
		switch ($status) {
			case 1:
				$status_string = 'سالم';
				break;
			case 2:
				$status_string = 'دچار بیماری قلبی';
				break;
			case 2:
				$status_string = 'آسیب دیده از بیماری قلبی';
				break;
		}
		
		$result['user']['status_string']             = $status_string;
		$result['user']['confidence_number']         = $user->confidence_number;
		$result['user']['confidence_number_enabled'] = $user->confidence_number_enabled;
		$result['user']['emergency_number_enabled']  = $user->emergency_number_enabled;
		$result['user']['pollution_notif_alert']     = $user->pollution_notif_alert;
		$result['user']['helper_enabled']            = $user->helper_enabled;
		$result['user']['first_name']                = $user->first_name;
		$result['user']['last_name']                 = $user->last_name;
		$result['user']['healthy_notif']             = $user->healthy_notif;
		
		return $result;
	}
	
	public static function setHeartRate($token, $rate)
	{
		$user = User::findOne(['api_token' => $token]);
		if (!$user) {
			Result::r403();
		}
		
		$heart_rate          = new HeartRate();
		$heart_rate->user_id = $user->id;
		$heart_rate->rate    = $rate;
		$heart_rate->date    = time();
		$heart_rate->save();
		
		if ($heart_rate->getErrors()) {
			Result::ERRORR_SAVING;
		}
		
		$result['success'] = 1;
		
		return $result;
	}
	
	public static function getHeartRate($token, $number)
	{
		$user = User::findOne(['api_token' => $token]);
		if (!$user) {
			Result::r403();
		}
		
		$list = [];
		foreach (HeartRate::find()->where(['user_id' => $user->id])->limit($number)->orderBy('date DESC')->all() as $heart_rate) {
			$item['id']        = (int)$heart_rate->id;
			$item['rate']      = $heart_rate->rate;
			$item['date']      = jdate('d F y، ساعت H:i', $heart_rate->date);
			$item['timestamp'] = $heart_rate->date;
			
			$list[] = $item;
		}
		
		$result['list'] = $list;
		
		return $result;
	}
	
	public static function getPublicData($user_id)
	{
		$user = User::findOne(['id' => $user_id]);
		if (!$user) {
			Result::runf700();
		}
		
		$result['user']['id']            = $user->id;
		$result['user']['username']      = $user->username;
		$result['user']['phone_number']  = $user->phone_number;
		$result['user']['picture_id']    = $user->picture_id;
		$result['user']['gender']        = $user->gender;
		$height_in_meter                 = $user->height / 100;
		$result['user']['bmi_number']    = $user->weight / ($height_in_meter * $height_in_meter);
		$bmi_number                      = $result['user']['bmi_number'];
		
		if ($bmi_number < 18.5) {
			$bmi_status = 'کمبود وزن';
		} elseif ($bmi_number < 24.9) {
			$bmi_status = 'وزن نرمال';
		} elseif ($bmi_number < 29.9) {
			$bmi_status = 'اضافه وزن';
		} elseif ($bmi_number < 34.9) {
			$bmi_status = 'چاق';
		} elseif ($bmi_number < 35) {
			$bmi_status = 'خیلی چاق';
		}
		
		$result['user']['bmi_status'] = $bmi_status;
		$result['user']['age']        = $user->age;
		$result['user']['height']     = $user->height;
		$result['user']['weight']     = $user->weight;
		$result['user']['status']     = $user->status;
		
		$status = $result['user']['status'];
		
		switch ($status) {
			case 1:
				$status_string = 'سالم';
				break;
			case 2:
				$status_string = 'دچار بیماری قلبی';
				break;
			case 2:
				$status_string = 'آسیب دیده از بیماری قلبی';
				break;
		}
		
		$result['user']['status_string']             = $status_string;
		$result['user']['confidence_number']         = $user->confidence_number;
		$result['user']['first_name']                = $user->first_name;
		$result['user']['last_name']                 = $user->last_name;
		
		return $result;
	}
	
}