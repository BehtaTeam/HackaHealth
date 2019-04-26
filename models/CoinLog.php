<?php

namespace app\models;

use app\components\Helper;
use app\components\Result;
use app\components\Tools;
use Yii;

/**
 * This is the model class for table "coin_log".
 *
 * @property integer     $id
 * @property integer     $user_id
 * @property integer     $amount
 * @property integer     $type
 * @property integer     $date
 *
 * @property User        $user
 * @property CoinLogType $type0
 */
class CoinLog extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'coin_log';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'type', 'date'], 'required'],
			[['user_id', 'amount', 'type', 'date', 'unread'], 'integer'],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
			[['type'], 'exist', 'skipOnError' => true, 'targetClass' => CoinLogType::className(), 'targetAttribute' => ['type' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'user_id' => 'User ID',
			'amount'  => 'مقدار',
			'type'    => 'Type',
			'date'    => 'Date',
			'unread'  => 'خوانده نشده',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getType0()
	{
		return $this->hasOne(CoinLogType::className(), ['id' => 'type']);
	}
	
	public static function Set($user_id, $amount, $type, $replaces = [])
	{
		$log          = new CoinLog();
		$log->user_id = $user_id;
		$log->amount  = $amount;
		$log->type    = $type;
		$log->date    = time();
		$log->save();
		
		if ($log->hasErrors()) {
			ErrorLog::set($user_id, __FILE__, __LINE__, json_encode($log->getErrors()));
			
			return false;
		} else {
			return $log->id;
		}
	}
	
	public static function getList($telegram_id, $access_token, $page_number, $per_page)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		$start = ($page_number - 1) * $per_page;
		
		$list = [];
		foreach (Coinlog::find()->where(['user_id' => $user->id])->limit($per_page)->offset($start)->orderBy('id DESC')->all() as $notification) {
			$temp['id']          = $notification->id;
			$temp['amount']      = $notification->amount;
			$temp['description'] = $notification->type0->description;
			$temp['date']        = Helper::timestamp_to_shamsi($notification->date);
			
			$list[] = $temp;
		}
		
		return [
			'total_count' => Coinlog::find()->where(['user_id' => $user->id])->count(),
			'list'        => $list
		];
		
	}
}