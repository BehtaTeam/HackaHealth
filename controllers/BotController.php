<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\Channel;
use app\models\User;
use Yii;
use app\models\Bot;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BotController implements the CRUD actions for Bot model.
 */
class BotController extends Controller
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
	 * Lists all Bot models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$dataProvider = new ActiveDataProvider([
			'query' => Bot::find(),
		]);
		
		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}
	
	/**
	 * Displays a single Bot model.
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
	 * Creates a new Bot model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 * @return mixed
	 */
	public function actionCreate()
	{
		$model = new Bot();
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(['view', 'id' => $model->id]);
		} else {
			return $this->render('create', [
				'model' => $model,
			]);
		}
	}
	
	/**
	 * Updates an existing Bot model.
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
	 * Deletes an existing Bot model.
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
	
	public function actionDeactive($id)
	{
		Channel::updateAll(['bot_id' => Null], ['bot_id' => $id]);
		
		return $this->redirect(['index']);
	}
	
	/**
	 * Finds the Bot model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return Bot the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = Bot::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('The requested page does not exist.');
		}
	}
}