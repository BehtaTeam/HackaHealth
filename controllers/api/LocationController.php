<?php

namespace app\controllers\api;

use app\components\LocationManager;
use app\components\MCrypt;
use app\components\Result;
use app\components\Secure;
use app\components\UserManage;
use app\models\Area;
use Yii;
use yii\web\Controller;

class LocationController extends Controller
{
	public $enableCsrfValidation = false;
	
	public function beforeAction($action)
	{
		header('Content-Type: application/json;Connection:close');
		
		return parent::beforeAction($action); // TODO: Change the autogenerated stub
	}
	
	public function actionGet()
	{
		$request = Yii::$app->request;
		
		$start_lat  = $_GET['start_lat'];
		$start_long = $_GET['start_long'];
		$end_lat    = $_GET['end_lat'];
		$end_long   = $_GET['end_long'];
		
		$result = LocationManager::location($start_lat, $start_long, $end_lat, $end_long);
		
		Result::success($result);
	}
	
	public function actionGetArea()
	{
		
		$lat  = $_GET['lat'];
		$long = $_GET['long'];
		
		$result = LocationManager::getArea($lat, $long);
		
		Result::success($result);
	}
	
	public function actionPopulate()
	{
		$first = Area::findOne(['id' => 21]);
		
		$lat1  = $first->lat1;
		$long1 = $first->long1;
		$lat2  = $first->lat2;
		$long2 = $first->long2;
		$lat3  = $first->lat3;
		$long3 = $first->long3;
		$lat4  = $first->lat4;
		$long4 = $first->long4;
		
		$lat_dist  = 0.0396335;
		$long_dist = 0.0683105;
		
		for ($i = 1; $i < 5; $i++) {
			$area        = new Area();
			$area->lat1  = $lat1;
			$area->long1 = $long1 + ($i * $long_dist);
			$area->lat2  = $lat1;
			$area->long2 = $area->long1 + $long_dist;
			$area->lat3  = $lat3;
			$area->long3 = $area->long2;
			$area->lat4  = $lat4;
			$area->long4 = $area->long1;
			$area->save();
		}
		
	}
	
	public function actionNewRow()
	{
		
		$first  = Area::findOne(['id' => 11]);
		$second = Area::findOne(['id' => 16]);
		
		$lat1  = 2 * $second->lat1 - $first->lat1;
		$long1 = 2 * $second->long1 - $first->long1;
		$lat2  = 2 * $second->lat2 - $first->lat2;
		$long2 = 2 * $second->long2 - $first->long2;
		$lat3  = 2 * $second->lat3 - $first->lat3;
		$long3 = 2 * $second->long3 - $first->long3;
		$lat4  = 2 * $second->lat4 - $first->lat4;
		$long4 = 2 * $second->long4 - $first->long4;
		
		$area        = new Area();
		$area->lat1  = $lat1;
		$area->long1 = $long1;
		$area->lat2  = $lat2;
		$area->long2 = $long2;
		$area->lat3  = $lat3;
		$area->long3 = $long3;
		$area->lat4  = $lat4;
		$area->long4 = $long4;
		$area->save();
		
	}
	
	public function actionGetAreas()
	{
		$result = LocationManager::getAreas();
		
		Result::success($result);
	}
	
	public function actionSetRandoms()
	{
		foreach (Area::find()->all() as $area) {
			$area->pollute = rand(0, 300);
			$area->save();
		}
	}
	
	public function actionAlertHelpers()
	{
		$lat      = $_GET['lat'];
		$long     = $_GET['long'];
		$token    = $_GET['token'];
		$pushe_id = $_GET['pushe_id'];
		$gaid     = $_GET['gaid'];
		
		$result = LocationManager::alertHelpers($lat, $long, $token, $pushe_id, $gaid);
		
		Result::success($result);
	}
	
}
