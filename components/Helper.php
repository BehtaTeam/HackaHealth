<?php

namespace app\components;


use app\models\Log;
use app\models\Option;
use app\models\Setting;
use app\models\User;

class Helper
{
	public static function setting($id)
	{
		return Option::findOne($id)->value;
	}
	
	public static function stringGenerator($length = 7)
	{
		$chars = array_merge(range(0, 9), range('a', 'z'));
		shuffle($chars);
		
		return implode(array_slice($chars, 0, $length));
	}
	
	public static function refreshUser($user_id)
	{
		$user                     = User::findOne($user_id);
		$user->last_activity_date = time();
		$user->last_login_ip      = $_SERVER['REMOTE_ADDR'];
		$user->update();
	}
	
	public static function intToDay($int)
	{
		switch ($int) {
			case 0:
				return 'شنبه';
				break;
			case 1:
				return 'یکشنبه';
				break;
			case 2:
				return 'دوشنبه';
				break;
			case 3:
				return 'سه شنبه';
				break;
			case 4:
				return 'چهارشنبه';
				break;
		}
	}
	
	public static function timestamp_to_shamsi($time)
	{
		if ($time == null OR $time == 0) {
			return 'نامشخص';
		}
		if ($time + 3599 > time()) {
			$temp = ((time() - $time) / 60);
			
			return floor($temp) ? tr_num(floor($temp), 'fa') . ' دقیقه پیش' : ' ثانیه های پیش';
		}
		if ($time + 12 * 3600 > time()) {
			$temp = ((time() - $time) / 3600);
			
			return tr_num(floor($temp) . ' ساعت پیش', 'fa');
		} elseif ($time > strtotime("today Asia/Tehran")) {
			return 'امروز';
		} elseif ($time > strtotime("yesterday Asia/Tehran")) {
			return 'دیروز ';
		} else {
			return jdate("d F y", $time);
		}
	}
	
	public static function timestamp_to_eshamsi($time)
	{
		$hour = jdate('H', $time);
		
		return jdate('d F y، ', $time) . " ساعت " . $hour;
		
	}
	
	public static function timestamp_to_cshamsi($time)
	{
		return jdate('d F y، H:i:s', $time);
		
	}
	
	public static function httpPost($url, $params)
	{
		$postData = '';
		foreach ($params as $k => $v) {
			$postData .= $k . '=' . $v . '&';
		}
		rtrim($postData, '&');
		
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_POST, count($postData));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		
		$output = curl_exec($ch);
		
		curl_close($ch);
		if (!isset($output)) {
			return 0;
		}
		$output = json_decode($output);
		if (!isset($output)) {
			return 0;
		}
		$output = get_object_vars($output);
		
		return $output;
	}
	
	public static function httpGet($url)
	{
		$output = file_get_contents($url);
		
		if (!isset($output)) {
			return 0;
		}
		$output = json_decode($output);
		if (!isset($output)) {
			return 0;
		}
		$output = get_object_vars($output);
		
		return $output;
	}
}