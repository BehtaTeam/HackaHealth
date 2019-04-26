<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "channel_violation".
 *
 * @property integer               $id
 * @property integer               $channel_id
 * @property integer               $reporter_id
 * @property integer               $status
 * @property integer               $exchange_id
 * @property integer               $reason
 * @property string                $description
 * @property integer               $date
 *
 * @property Channel               $channel
 * @property Violation             $reason0
 * @property User                  $reporter
 * @property ExchangeParticipant[] $exchangeParticipants
 * @property Exchange              $exchange
 */
class ChannelViolation extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'channel_violation';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['channel_id', 'reason', 'date'], 'required'],
			[['channel_id', 'reason', 'date', 'reporter_id', 'status', 'exchange_id'], 'integer'],
			[['description'], 'string'],
			[['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Channel::className(), 'targetAttribute' => ['channel_id' => 'id']],
			[['reason'], 'exist', 'skipOnError' => true, 'targetClass' => Violation::className(), 'targetAttribute' => ['reason' => 'id']],
			[['reporter_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['reporter_id' => 'id']],
			[['status'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::className(), 'targetAttribute' => ['status' => 'id']],
			[['exchange_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::className(), 'targetAttribute' => ['exchange_id' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'channel_id'  => 'کانال',
			'reason'      => 'علت',
			'description' => 'توضیحات',
			'date'        => 'زمان گزارش',
			'exchange_id' => 'شماره تبادل'
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannel()
	{
		return $this->hasOne(Channel::className(), ['id' => 'channel_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReason0()
	{
		return $this->hasOne(Violation::className(), ['id' => 'reason']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchangeParticipants()
	{
		return $this->hasMany(ExchangeParticipant::className(), ['violation_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getReporter()
	{
		return $this->hasOne(User::className(), ['id' => 'reporter_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStatus0()
	{
		return $this->hasOne(StatusType::className(), ['id' => 'status']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchange()
	{
		return $this->hasOne(Exchange::className(), ['id' => 'exchange_id']);
	}
}