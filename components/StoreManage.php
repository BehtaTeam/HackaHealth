<?php

namespace app\components;


use app\models\CoinLog;
use app\models\ErrorLog;
use app\models\Notification;
use app\models\StoreCoin;
use app\models\StoreCoinMyket;
use app\models\StoreLog;
use app\models\User;
use Exception;
use Yii;

class StoreManage
{
	
	public static function getList($market)
	{
		if ($market == 'bazaar') {
			$result = Yii::$app->cache->get("teletabadol_bazaar_store_lists");
			if ($result == false) {
				$list = StoreCoin::find()->where('active=1')->orderBy('sort ASC')->all();
				
				$result = [];
				foreach ($list as $item) {
					$temp['id']            = (int)$item->id;
					$temp['identity']      = $item->identity;
					$temp['coin_count']    = $item->coin_count;
					$temp['price']         = $item->price;
					$temp['striked_value'] = $item->striked_value;
					
					$result['list'][] = $temp;
				}
				
				$result['success'] = 1;
				
				Yii::$app->cache->set("teletabadol_bazaar_store_lists", $result, 200);
			}
		} elseif ($market == 'myket') {
			$result = Yii::$app->cache->get("teletabadol_myket_store_lists");
			if ($result == false) {
				$list = StoreCoinMyket::find()->where('active=1')->orderBy('sort ASC')->all();
				
				$result = [];
				foreach ($list as $item) {
					$temp['id']            = (int)$item->id;
					$temp['identity']      = $item->identity;
					$temp['coin_count']    = $item->coin_count;
					$temp['price']         = $item->price;
					$temp['striked_value'] = $item->striked_value;
					
					$result['list'][] = $temp;
				}
				
				$result['success'] = 1;
				
				Yii::$app->cache->set("teletabadol_myket_store_lists", $result, 200);
			}
		}
		
		return $result;
	}
	
	/**
	 * @param $telegram_id
	 * @param $item_id
	 * @param $developer_payload
	 * @param $bazaar_token
	 * @param $purchase_state
	 * @param $market
	 *
	 * @return array
	 */
	public static function addStock($telegram_id, $item_id, $developer_payload, $bazaar_token, $purchase_state, $market)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		try {
			if ($market == 'bazaar') {
				$item = StoreCoin::findOne($item_id);
				if (!$item) {
					Result::rItemNotFound703();
				}
				// check bazaar api
				$access_token = Yii::$app->cache->get('bazaar_access_token');
				if ($access_token == null) {
					$access_token = file_get_contents('http://payamkadeh.com/site/GetAccessCode');
					Yii::$app->cache->set('bazaar_access_token', $access_token, 3600);
				}
				
				$url     = 'https://pardakht.cafebazaar.ir/devapi/v2/api/validate/' . Yii::$app->params['package_name'] . '/inapp/' .
						   $item->identity . '/purchases/' . $bazaar_token . '/?access_token=' . $access_token;
				$output  = Helper::httpGet($url);
				$trusted = 0;
				if ($output != 0)
					if (array_key_exists('developerPayload', $output)) {
						$main_developer_payload = $output['developerPayload'];
						
						$found = StoreLog::find()->where("market = '$market' AND token = '$bazaar_token' AND purchase_state = 'موفق'")->one();
						if ($found) {
							Result::TransDoneBefore800($user->stock - $user->spent);
						}
						if (($main_developer_payload == $developer_payload)) {
							$trusted = 1;
						}
					}
			} elseif ($market == 'myket') {
				$item = StoreCoinMyket::findOne($item_id);
				if (!$item) {
					Result::rItemNotFound703();
				}
				$pn  = Yii::$app->params['package_name'];
				$url = "https://api.myket.ir/IapService.svc/getpurchases?packagename=$pn&productId=$item->identity&token=$bazaar_token";
				
				$output  = Helper::httpGet($url);
				$trusted = 0;
				if (isset($output))
					if (array_key_exists('purchaseState', $output)) {
						$purchaseState = $output['purchaseState'];
						
						$found = StoreLog::find()->where("market = '$market' AND token = '$bazaar_token' AND purchase_state = 'موفق'")->one();
						if ($found) {
							Result::TransDoneBefore800($user->stock - $user->spent);
						}
						if (($purchaseState == 0)) {
							$trusted = 1;
						}
					}
			}
			
		} catch (Exception $e) {
			$notification          = new Notification();
			$notification->user_id = $user->id;
			$notification->url     = Yii::$app->params['package_name'];
			$notification->title   = 'خطا در خرید';
			$notification->body    = 'لطفا در بخش ارتباط با ما، ضمن اعلام نام کاربری خود، مشکل پرداخت را پیگیری فرمایید';
			$notification->date    = time();
			$notification->type    = 101;
			$notification->save();
			
			Result::TransFailed801($user->stock - $user->spent, $e->getMessage());
		}
		
		$connection  = Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			$log                    = new StoreLog();
			$log->user_id           = $user->id;
			$log->stock             = $item->coin_count;
			$log->price             = $item->price;
			$log->market            = $market;
			$log->developer_payload = $developer_payload;
			$log->token      = $bazaar_token;
			$log->purchase_state    = $purchase_state;
			if ($trusted == 0) {
				$log->purchase_state = 'مشکل';
			} else {
				$log->purchase_state = 'موفق';
			}
			$log->date = time();
			$log->save();
			if ($log->getErrors()) {
				Yii::$app->cache->set('temp_e', $log->getErrors(), 3000);
			}
			
			if ($trusted == 0) {
				$notification          = new Notification();
				$notification->user_id = $user->id;
				$notification->url     = Yii::$app->params['package_name'];
				$notification->title   = 'خطا در خرید';
				$notification->body    = 'لطفا در بخش ارتباط با ما، ضمن اعلام نام کاربری خود، مشکل پرداخت را پیگیری فرمایید';
				$notification->date    = time();
				$notification->type    = 101;
				$notification->save();
				
				Result::TransUnverified802($user->stock - $user->spent);
			};
			
			$user->stock = $user->stock + $item->coin_count;
			$user->update();
			if ($user->hasErrors())
				Result::TransFailed801($user->stock - $user->spent, json_encode($user->getErrors()));
			
			$user = User::findOne($user->id);
			
			$transaction->commit();
		} catch (Exception $e) {
			$transaction->rollBack();
			
			ErrorLog::set($user->telegram_id, __FILE__, __LINE__, $e->getMessage());
			
			Result::TransFailed801($user->stock - $user->spent, $e->getMessage());
		}
		
		CoinLog::Set($user->id, $item->coin_count, 3);
		
		
		$user          = User::findOne($user->id);
		$current_stock = $user->stock - $user->spent;
		
		return [
			'success'       => 1,
			'message'       => 'تراکنش با موفقیت انجام شد',
			'current_stock' => $current_stock,
		]; // operation successfully done
	}
}