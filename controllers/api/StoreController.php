<?php

namespace app\controllers\api;

use app\components\MCrypt;
use app\components\Result;
use app\components\Secure;
use app\components\StoreManage;
use app\components\UserManage;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class StoreController extends Controller
{
	public $enableCsrfValidation = false;
	
	public function beforeAction($action)
	{
		header('Content-Type: application/json;Connection:close');
		
		return parent::beforeAction($action); // TODO: Change the autogenerated stub
	}
	
	public function actionPay()
	{
		$request = \Yii::$app->request;
		
		$telegram_id       = $request->post('telegram_id', '');
		$developer_payload = $request->post('developer_payload', '');
		$bazaar_token      = $request->post('bazaar_token', '');
		$purchase_state    = $request->post('purchase_state', '');
		$item_id           = $request->post('id', '');
		$market            = isset($_POST['market']) ? $_POST['market'] : 'bazaar';
		
		$result = StoreManage::addStock($telegram_id, $item_id, $developer_payload, $bazaar_token, $purchase_state, $market);
		
		Result::success($result);
	}
	
	public function actionList()
	{
		$request = Yii::$app->request;
		
		$telegram_id  = $request->post('telegram_id', '');
		$access_token = $request->post('at', '');
		$market       = $request->post('market', '');
		
		$result = StoreManage::getList($market);
		
		
		Result::success($result);
		
	}
	
	
}
