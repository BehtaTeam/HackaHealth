<?php

namespace app\components;


use app\models\Area;
use app\models\ErrorLog;
use app\models\HeartRate;
use app\models\Notification;
use app\models\User;

class LocationManager
{
	
	public static function location($start, $end)
	{
		$options = [
			'origin'       => $start,
			'destination'  => $end,
			'key'          => 'AIzaSyAiVFF15gsPcTaYOv7kS_gYC1xmRzgSCCY',
			'mode'         => 'Driving',
			'waypoints'    => '35.760632, 51.451677',
			'alternatives' => true,
		];
		
		$str = '';
		foreach ($options as $key => $value) {
			$value = str_replace(' ', '', $value);
			$str   .= $key . '=' . $value . '&';
		}
		
		$content = file_get_contents("https://maps.googleapis.com/maps/api/directions/json?" . $str);
		
		$content = json_decode($content);
		
		$result['location'] = $content;
		
		return $result;
	}
	
	public static function getAreas()
	{
		$list = [];
		foreach (Area::find()->orderBy('id ASC')->all() as $area) {
			$item['id']    = (int)$area->id;
			$item['lat1']  = $area->lat1;
			$item['long1'] = $area->long1;
			$item['lat2']  = $area->lat2;
			$item['long2'] = $area->long2;
			$item['lat3']  = $area->lat3;
			$item['long3'] = $area->long3;
			$item['lat4']  = $area->lat4;
			$item['long4'] = $area->long4;
			
			$list[] = $item;
		}
		
		$result['list'] = $list;
		
		return $result;
	}
	
}