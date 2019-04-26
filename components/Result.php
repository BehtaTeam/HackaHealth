<?php

namespace app\components;


class Result
{
	const ERROR403                  = 'عدم امکان دسترسی';
	const ERRORBAN                  = 'متاسفانه حساب شما در سامانه مسدود شده است، با پشتیبانی در ارتباط باشید';
	const ERRORUNF                  = 'کاربر مورد نظر یافت نشد';
	const ERRORBNF                  = 'بات مورد نظر یافت نشد';
	const ERROCHNF                  = 'کانال مورد نظر یافت نشد';
	const ERRORRI                   = 'مشکل در دریافت اطلاعات';
	const ERRORR_SAVING             = 'مشکل در ذخیره سازی اطلاعات';
	const SUCCESS                   = 'عملیات با موفقیت انجام شد';
	const ERRORR_TELEGRAM           = 'مشکل در دریافت اطلاعات از تلگرام';
	const ERRORNotAdmin403          = 'لطفا بات را به عنوان مدیر به کانال مورد نظر اضافه کنید';
	const ERRORR_ChannelViolation   = 'کانال مورد نظر فعلا مسدود است، با پشتیبانی تماس بگیرید';
	const ERRORR_not_enough_stock   = 'متاسفانه موجودی شما کافی نیست';
	const ERROEXNF                  = 'متاسفانه تبادل مورد نظر یافت نشد';
	const ERROITEMNF                = 'آیتم فروشگاه اعلام شده یافت نشد';
	const ERRORTransDoneBefore      = 'تراکنش مورد نظر قبلا ثبت شده است';
	const ERRORTransFailed          = 'خطا در پردازش تراکنش';
	const ERRORTransUnverified      = 'علیرغم صحت بازگشتی بازار، پرداختی شما در وب سرویس بازار تایید نشد، با پشتیبانی بازار تماس بگیرید';
	const ERRORR_ReportOutOfRange   = 'گزارش در خارج از ساعات تبادل ارسال شده است';
	const ERRORChannelLowUsersCount = 'برای شرکت در تبادل لیستی، کانال شما باید حداقل ۱۰ عضو داشته باشد';
	const ERRORUPdate               = 'لطفا تله تبادل را آپدیت نمایید.';
	
	public static function rNotAdmin405()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 405, 'message' => Result::ERRORNotAdmin403];
		Secure::Pack($result);
	}
	
	public static function r403()
	{
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
	
	public static function rBNT701()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 701, 'message' => Result::ERRORBNF];
		Secure::Pack($result);
	}
	
	public static function rChNotFound702()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 702, 'message' => Result::ERROCHNF];
		Secure::Pack($result);
	}
	
	public static function rItemNotFound703()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 703, 'message' => Result::ERROITEMNF];
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
	
	public static function telgram_problem900()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 900, 'message' => Result::ERRORR_TELEGRAM];
		Secure::Pack($result);
	}
	
	public static function rChannelViolation901()
	{
		// @todo the reason of the ban
		$result = ['data' => ['value' => 'zero'], 'status' => 901, 'message' => Result::ERRORR_ChannelViolation];
		Secure::Pack($result);
	}
	
	public static function rOutOfRange902()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 902, 'message' => Result::ERRORR_ReportOutOfRange];
		Secure::Pack($result);
	}
	
	public static function not_enough_stock100()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 100, 'message' => Result::ERRORR_not_enough_stock];
		Secure::Pack($result);
	}
	
	public static function success($data)
	{
		$result = ['data' => $data, 'status' => 200, 'message' => Result::SUCCESS];
		Secure::Pack($result);
	}
	
	public static function rExNotFound708()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 708, 'message' => Result::ERROEXNF];
		Secure::Pack($result);
	}
	
	public static function TransDoneBefore800($coin)
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 800, 'current_coin' => $coin, 'message' => Result::ERRORTransDoneBefore];
		Secure::Pack($result);
	}
	
	public static function TransFailed801($coin, $error)
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 801, 'current_coin' => $coin, 'message' => Result::ERRORTransFailed, 'error' => $error];
		Secure::Pack($result);
	}
	
	public static function TransUnverified802($coin)
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 802, 'current_coin' => $coin, 'message' => Result::ERRORTransUnverified];
		Secure::Pack($result);
	}
	
	public static function rCHLowCount903()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 903, 'message' => Result::ERRORChannelLowUsersCount];
		Secure::Pack($result);
	}
	
	public static function update3()
	{
		$result = ['data' => ['value' => 'zero'], 'status' => 3, 'message' => Result::ERRORUPdate];
	}
}