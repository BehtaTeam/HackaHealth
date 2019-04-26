<?php

namespace app\controllers;

use app\components\AccessRule;
use app\models\ErrorLog;
use app\models\ExchangeHours;
use app\models\ExchangeParticipant;
use app\models\Notification;
use app\models\User;
use ErrorException;
use Yii;
use app\models\ChannelViolation;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ChannelViolationController implements the CRUD actions for ChannelViolation model.
 */
class ChannelViolationController extends Controller
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
	 * Lists all ChannelViolation models.
	 * @return mixed
	 */
	public function actionIndex()
	{
		$iCurrentTimestamp = strtotime("now");
		$iStartOfHour      = $iCurrentTimestamp - ($iCurrentTimestamp % 3600);
		
		$dataProvider = new ActiveDataProvider([
			'query' => ChannelViolation::find()->orderBy('id DESC')->where('date>' . $iStartOfHour),
		]);
		
		return $this->render('index', [
			'dataProvider' => $dataProvider,
		]);
	}
	
	public function actionApprove()
	{
		/* @var $violation ChannelViolation */
		$violation = ChannelViolation::find()->where(['id' => (int)$_POST['id']])->one();
		if (!$violation) {
			throw new NotFoundHttpException('گزارش مورد نظر پیدا نشد.');
		}
		$violation->status = (int)$_POST['status'];
		if ($violation->update()) {
			if ((int)$_POST['status'] == 12) {
				if ($violation->exchange_id != null) {
					/* @var $exchange_participant ExchangeParticipant */
					$exchange_participant = ExchangeParticipant::find()->where(['exchange_id' => $violation->exchange_id, 'channel_id' => $violation->channel_id])->one();
					
					if ($exchange_participant) {
						$exchange_participant->violation_id = $violation->id;
						$exchange_participant->status       = 8;
						$exchange_participant->update();
						
						if ($exchange_participant->hasErrors())
							ErrorLog::set($exchange_participant->id, __FILE__, __LINE__, json_encode($exchange_participant->getErrors()));
					}
				}
				$channel = $violation->channel;
				
				// @todo more penalty next time
				$channel->ban_until = time() + $violation->reason0->first_time_penalty;
				$channel->update();
				
				if ($channel->hasErrors())
					ErrorLog::set($channel->admin->id, __FILE__, __LINE__, json_encode($channel->getErrors()));
				
				$notification          = new Notification();
				$notification->user_id = $channel->admin_id;
				$notification->url     = Yii::$app->params['package_name'];
				$notification->title   = 'گزارش تخلف';
				$notification->body    = 'کانال ' . $channel->name . ' هنگام تبادل مرتکب تخلف شده است';
				$notification->date    = time();
				$notification->type    = 15;
				$notification->save();
				
				if ($notification->hasErrors())
					ErrorLog::set($channel->admin_id, __FILE__, __LINE__, json_encode($notification->getErrors()));
				
			} else {
				$channel            = $violation->channel;
				$channel->ban_until = time();
				$channel->update();
			}
			
			return $this->redirect(Yii::$app->request->referrer);
		} else {
			throw new ErrorException('خطایی در تایید گزارش رخ داده است.');
		}
	}
	
	/**
	 * Deletes an existing ChannelViolation model.
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
	 * Finds the ChannelViolation model based on its primary key value.
	 * If the model is not found, a 404 HTTP exception will be thrown.
	 *
	 * @param integer $id
	 *
	 * @return ChannelViolation the loaded model
	 * @throws NotFoundHttpException if the model cannot be found
	 */
	protected function findModel($id)
	{
		if (($model = ChannelViolation::findOne($id)) !== null) {
			return $model;
		} else {
			throw new NotFoundHttpException('گزارش مورد نظر یافت نشد.');
		}
	}
}