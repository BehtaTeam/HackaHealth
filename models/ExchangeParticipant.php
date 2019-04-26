<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exchange_participant".
 *
 * @property string           $id
 * @property integer          $exchange_id
 * @property integer          $channel_id
 * @property integer          $violation_id
 * @property string           $message_id
 * @property integer          $status
 *
 * @property Exchange         $exchange
 * @property Channel          $channel
 * @property StatusType       $status0
 * @property ChannelViolation $violation
 */
class ExchangeParticipant extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'exchange_participant';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['exchange_id', 'channel_id', 'message_id'], 'required'],
			[['exchange_id', 'channel_id', 'violation_id', 'message_id', 'status'], 'integer'],
			[['exchange_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::className(), 'targetAttribute' => ['exchange_id' => 'id']],
			[['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Channel::className(), 'targetAttribute' => ['channel_id' => 'id']],
			[['status'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::className(), 'targetAttribute' => ['status' => 'id']],
			[['violation_id'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelViolation::className(), 'targetAttribute' => ['violation_id' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'           => 'ID',
			'exchange_id'  => 'کد پیگیری',
			'channel_id'   => 'Channel ID',
			'violation_id' => 'تخلف',
			'message_id'   => 'Message ID',
			'status'       => 'وضعیت',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchange()
	{
		return $this->hasOne(Exchange::className(), ['id' => 'exchange_id']);
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
	public function getStatus0()
	{
		return $this->hasOne(StatusType::className(), ['id' => 'status']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getViolation()
	{
		return $this->hasOne(ChannelViolation::className(), ['id' => 'violation_id']);
	}
}