<?php

namespace app\components;


class Result
{
	const ERROR403                  = 'عدم امکان دسترسی';
	const ERRORBAN                  = 'متاسفانه حساب شما در سامانه مسدود شده است، با پشتیبانی در ارتباط باشید';
	const ERRORUNF                  = 'کاربر مورد نظر یافت نشد';
	const ERRORRI                   = 'مشکل در دریافت اطلاعات';
	const ERRORR_SAVING             = 'مشکل در ذخیره سازی اطلاعات';
	const SUCCESS                   = 'عملیات با موفقیت انجام شد';
	
	
	public static function r403()
	{
		header('HTTP/1.1 403 Forbidden');
		$result = ['data' => ['value' => 'zero'], 'status' => 403, 'message' => Result::ERROR403];
		Secure::Pack($result);
	}
	
	public static function rBan()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 4031, 'message' => Result::ERRORBAN];
		Secure::Pack($result);
	}
	
	public static function runf700()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 700, 'message' => Result::ERRORUNF];
		Secure::Pack($result);
	}
	
	public static function rinf400()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 400, 'message' => Result::ERRORRI];
		Secure::Pack($result);
	}
	
	public static function saving_error600($message)
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 600, 'message' => Result::ERRORR_SAVING, 'error' => $message];
		Secure::Pack($result);
	}
	
	public static function exist_before601($message)
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 601, 'message' => $message];
		Secure::Pack($result);
	}
	
	public static function success($data)
	{
		$result = ['data' => $data, 'status' => 200, 'message' => Result::SUCCESS];
		Secure::Pack($result);
	}
	
}