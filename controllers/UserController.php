<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Channel;
use app\models\CoinLog;
use app\models\StoreLog;
use Yii;
use app\models\User;
use app\models\UserSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
	 * Lists all User models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$searchModel  = new UserSearch();
		$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
		
		return $this->render('index', [
			'searchModel'  => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single User model.
	 *
	 * @param integer $id
	 *
	 * @return mixed
	 */
	public function actionView($id)
	{
		$store_log = new ActiveDataProvider([
			'query' => StoreLog::find()->where(['user_id' => $id]),
			'sort'  => [
				'defaultOrder' => ['id' => SORT_DESC],
			],
		]);
		
		$logs = new ActiveDataProvider([
			'query' => CoinLog::find()->where(['user_id' => $id]),
			'sort'  => [
				'defaultOrder' => ['id' => SORT_DESC],
			],
		]);
		
		$channels = new ActiveDataProvider([
			'query' => Channel::find()->where(['admin_id' => $id]),
			'sort'  => [
				'defaultOrder' => ['id' => SORT_DESC],
			],
		]);
		
		return $this->render('view', [
			'model'     => $this->findModel($id),
			'store_log' => $store_log,
			'logs'      => $logs,
			'channels'  => $channels,
		]);
	}
	
	/**
	 * Creates a new User model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new User();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing User model.
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
	 * Deletes an existing User model.
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
	
	/**
	 * Finds the User model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return User the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = User::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('صفحه درخواست شده یافت نشد.');
		}
	}
}