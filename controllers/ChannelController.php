<?php

namespace app\controllers;

use app\components\AccessRule;
use app\components\Telegram;
use app\models\Bot;
use app\models\ChannelGroup;
use app\models\User;
use Yii;
use app\models\Channel;
use app\models\ChannelSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelController implements the CRUD actions for Channel model.
 */
class ChannelController extends Controller
{
	/**
	 * @inheritdoc
	 */
	public function behaviors()
	{
		return [
			'verbs'  => [
				'class'   => VerbFilter::className(),
				'actions' => [
					'delete' => ['POST'],
				],
			],
			'access' => [
				'class'      => AccessControl::className(),
				// We will override the default rule config with the new AccessRule class
				'ruleConfig' => [
					'class' => AccessRule::className(),
				],
				//'only'       => ['index', 'new', 'upload'],
				'rules'      => [
					[
						'allow' => true,
						'roles' => [
							User::ROLE_ADMIN
						],
					],
				],
			],
		];
	}
	
	
	/**
	 * Lists all Channel models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel  = new ChannelSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single Channel model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		return $this->render('view', [
			'model' => $this->findModel($id),
		]);
	}
	
	/**
	 * Creates a new Channel model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Channel();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing Channel model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionUpdate($id)
	{
		$model = $this->findModel($id);
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('update', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Deletes an existing Channel model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionDelete($id)
	{
		$this->findModel($id)->delete();
		
		return $this->redirect(['index']);
	}
	
	public function actionUpdateInfo($id)
	{
		$channel = $this->findModel($id);
		$bot     = Bot::findOne(['active' => 1, 'id' => $channel->bot_id]);
		if (!$bot)
			throw new NotFoundHttpException('بات ثبت شده یافت نشد.');
		
		if (Telegram::getChatAdministrators("$channel->chat_id", $bot->id)['ok'] !== false) {
			$member_count  = (int)Telegram::getChatMembersCount("$channel->chat_id", $bot->id)['result'];
			$channel_group = ChannelGroup::find()->where("$member_count BETWEEN from_count AND to_count")->one()->number;
			
			$channel->member_count  = $member_count;
			$channel->channel_group = $channel_group;
			$channel->last_update   = time();
			$channel->update();
		} else {
			throw new NotFoundHttpException('بات برنامه سوپر ممبر به عنوان مدیر ثبت نشده');
		}
		
		return $this->redirect('/channel/' . $id);
	}
	
	public function actionUnban($id)
	{
		$channel            = $this->findModel($id);
		$channel->ban_until = time();
		$channel->update();
		
		return $this->redirect('/channel/' . $id);
	}
	
	/**
	 * Finds the Channel model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Channel the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Channel::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('صفحه مورد نظر یافت نشد.');
		}
	}
}