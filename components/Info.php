<?php

namespace app\components;


use app\models\ChannelGroup;
use app\models\ErrorLog;
use app\models\ExchangeHours;
use app\models\User;

class Info
{
	public static function ExchangeHours()
	{
		$list = ExchangeHours::find()->where(['active' => 1])->select('id, start_hour, end_hour')->asArray()->all();
		
		return $list;
	}
	
	public static function ChannelGroups()
	{
		
		$list = ChannelGroup::find()->select('number, from_count, to_count, price')->asArray()->all();
		
		return $list;
	}
}