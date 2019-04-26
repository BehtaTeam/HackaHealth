<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "special_exchange".
 *
 * @property integer      $id
 * @property integer      $channel_id
 * @property integer      $from_group
 * @property integer      $target_group
 * @property integer      $paid_amount
 * @property integer      $paid_amount_back
 * @property integer      $date
 * @property integer      $status
 *
 * @property Exchange[]   $exchanges
 * @property Channel      $channel
 * @property ChannelGroup $fromGroup
 * @property ChannelGroup $targetGroup
 * @property StatusType   $status0
 */
class SpecialExchange extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'special_exchange';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['channel_id', 'from_group', 'target_group', 'date'], 'required'],
			[['channel_id', 'from_group', 'target_group', 'paid_amount', 'paid_amount_back', 'date', 'status'], 'safe'],
			[['channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Channel::className(), 'targetAttribute' => ['channel_id' => 'id']],
			[['from_group'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelGroup::className(), 'targetAttribute' => ['from_group' => 'number']],
			[['target_group'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelGroup::className(), 'targetAttribute' => ['target_group' => 'number']],
			[['status'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::className(), 'targetAttribute' => ['status' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'channel_id'       => 'کانال',
			'from_group'       => 'از گروه',
			'target_group'     => 'به گروه',
			'paid_amount'      => 'پرداختی',
			'paid_amount_back' => 'بازگشتی',
			'date'             => 'زمان',
			'status'           => 'وضعیت',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchanges()
	{
		return $this->hasMany(Exchange::className(), ['special_exchange_id' => 'id']);
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
	public function getFromGroup()
	{
		return $this->hasOne(ChannelGroup::className(), ['number' => 'from_group']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getTargetGroup()
	{
		return $this->hasOne(ChannelGroup::className(), ['number' => 'target_group']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStatus0()
	{
		return $this->hasOne(StatusType::className(), ['id' => 'status']);
	}
}