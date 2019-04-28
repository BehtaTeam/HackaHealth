<?php

namespace app\components;


use app\models\Area;
use app\models\Center;
use app\models\ErrorLog;
use app\models\HeartRate;
use app\models\Notification;
use app\models\User;

class LocationManager
{
	
	public static function location($start_lat, $start_long, $end_lat, $end_long)
	{
		$start_lat  = (double)$start_lat;
		$start_long = (double)$start_long;
		$end_lat    = (double)$end_lat;
		$end_long   = (double)$end_long;
		
		// First Request To Google For The First And the Main Route
		$content1 = LocationManager::googleRequest($start_lat . ',' . $start_long, $start_lat . ',' . $end_long, null);
		usleep(500);
		
		// Other Requests
		$content2 = LocationManager::googleRequest($start_lat . ',' . $start_long, $start_lat . ',' . $end_long, $start_lat . ',' . ((($end_long - $start_long) / 2) + $start_long));
		usleep(500);
		$content3 = LocationManager::googleRequest($start_lat . ',' . $start_long, $end_lat . ',' . $end_long, ((($end_lat - $start_lat) / 2) + $start_lat) . ',' . $start_long);
		
		
		$legs = [];
		
		foreach ($content1->routes[0]->legs as $leg) {
			$legs[] = $leg;
		}
		
		foreach ($content2->routes[0]->legs as $leg) {
			$legs[] = $leg;
		}
		
		foreach ($content3->routes[0]->legs as $leg) {
			$legs[] = $leg;
		}
		/*
				$result['legs'] = $legs;
				
				return $result;*/
		
		$step_groups = [];
		foreach ($legs as $id => $leg) {
			$steps = $leg->steps;
			
			//$step_groups["id-" . $id] = $steps;
			
			$end_locations = [];
			foreach ($steps as $step_id => $step) {
				$end_location = $step->end_location;
				
				$end_locations[$step_id] = $end_location;
				
				$lat                              = $end_location->lat;
				$long                             = $end_location->lng;
				$area                             = Area::find()->where('lat1>' . $lat)->andWhere('lat4<' . $lat)->andWhere('long2>' . $long)->andWhere('long1<' . $long)->one();
				$end_locations[$step_id]->area_id = $area->id;
				$end_locations[$step_id]->pollute = $area->pollute;
				
				$averager[$id][$step_id] = $area->pollute;
			}
			if (count($averager[$id])) {
				$averager[$id] = array_filter($averager[$id]);
				$average       = array_sum($averager[$id]) / count($averager[$id]);
			}
			
			$step_groups[$id]['average'] = (int)$average;
			
			if ($average < 51) {
				$pollute_status = 'مناسب برای همه';
			} elseif ($average < 101) {
				$pollute_status = 'تقریبا مناسب برای همه';
			} elseif ($average < 151) {
				$pollute_status = 'هوای ناسالم برای بیماران قلبی';
			} elseif ($average < 201) {
				$pollute_status = 'هوای ناسالم برای عموم';
			} elseif ($average < 251) {
				$pollute_status = 'هوای بسیار ناسالم';
			} elseif ($average < 301) {
				$pollute_status = 'هوای خطرناک';
			}
			
			$step_groups[$id]['pollute_status'] = $pollute_status;
			$step_groups[$id]['steps']          = $steps;
		}
		
		$result['routes'] = $step_groups;
		
		return $result;
	}
	
	public static function googleRequest($start, $end, $waypoints)
	{
		$options = [
			'origin'       => $start,
			'destination'  => $end,
			'key'          => 'AIzaSyAiVFF15gsPcTaYOv7kS_gYC1xmRzgSCCY',
			'mode'         => 'Driving',
			'waypoints'    => $waypoints,
			'alternatives' => true,
		];
		
		$str = '';
		foreach ($options as $key => $value) {
			if ($value != null) {
				$value = str_replace(' ', '', $value);
				$str   .= $key . '=' . $value . '&';
			}
		}
		
		$content = file_get_contents("https://maps.googleapis.com/maps/api/directions/json?" . $str);
		
		return json_decode($content);
	}
	
	public static function getAreas()
	{
		$list = [];
		foreach (Area::find()->orderBy('id ASC')->all() as $area) {
			$item['id']      = (int)$area->id;
			$item['lat1']    = $area->lat1;
			$item['long1']   = $area->long1;
			$item['lat2']    = $area->lat2;
			$item['long2']   = $area->long2;
			$item['lat3']    = $area->lat3;
			$item['long3']   = $area->long3;
			$item['lat4']    = $area->lat4;
			$item['long4']   = $area->long4;
			$item['pollute'] = $area->pollute;
			
			$pollute = $area->pollute;
			if ($pollute < 51) {
				$item['pollute_string'] = 'هوای پاک';
			} elseif ($pollute < 101) {
				$item['pollute_string'] = 'هوای نیمه پاک';
			} elseif ($pollute < 151) {
				$item['pollute_string'] = 'هوای ناسالم برای بیماران قلبی';
			} elseif ($pollute < 201) {
				$item['pollute_string'] = 'هوای ناسالم برای عموم';
			} elseif ($pollute < 201) {
				$item['pollute_string'] = 'هوای بسیار ناسالم';
			} elseif ($pollute < 251) {
				$item['pollute_string'] = 'هوای خطرناک';
			}
			
			$list[] = $item;
		}
		
		$result['list'] = $list;
		
		return $result;
	}
	
	public static function nearestCenter($lat, $long, $token)
	{
		$qstring = "(POW(('center.long'-$long),2) + POW(('center.lat'-$lat),1))";
		//$center = Center::find()->orderBy($qstring)->one();
		
		//$center = Center::findOne(['orderBy' => $qstring]);
		$center = Center::findBySql('SELECT * FROM `center` ORDER BY ' . $qstring)->one();
		
		$result['center']['id']          = $center->id;
		$result['center']['lat']         = $center->lat;
		$result['center']['long']        = $center->long;
		$result['center']['title']       = $center->title;
		$result['center']['description'] = $center->description;
		$result['center']['type']        = $center->type;
		
		return $result;
		
	}
	
}