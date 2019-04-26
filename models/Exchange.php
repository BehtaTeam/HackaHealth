<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exchange".
 *
 * @property integer               $id
 * @property integer               $special_channel_id
 * @property integer               $special_exchange_id
 * @property integer               $channels_group
 * @property integer               $date
 * @property integer               $finish_date
 * @property integer               $status
 * @property integer               $info_id
 *
 * @property Channel               $specialChannel
 * @property SpecialExchange       $specialExchange
 * @property ChannelGroup          $channelsGroup
 * @property StatusType            $status0
 * @property ExchangeParticipant[] $exchangeParticipants
 */
class Exchange extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'exchange';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['special_channel_id', 'special_exchange_id', 'channels_group', 'date'], 'required'],
			[['special_channel_id', 'special_exchange_id', 'channels_group', 'date', 'finish_date', 'status', 'info_id'], 'integer'],
			[['special_channel_id'], 'exist', 'skipOnError' => true, 'targetClass' => Channel::className(), 'targetAttribute' => ['special_channel_id' => 'id']],
			[['special_exchange_id'], 'exist', 'skipOnError' => true, 'targetClass' => SpecialExchange::className(), 'targetAttribute' => ['special_exchange_id' => 'id']],
			[['channels_group'], 'exist', 'skipOnError' => true, 'targetClass' => ChannelGroup::className(), 'targetAttribute' => ['channels_group' => 'number']],
			[['status'], 'exist', 'skipOnError' => true, 'targetClass' => StatusType::className(), 'targetAttribute' => ['status' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                  => 'شماره پیگیری',
			'special_channel_id'  => 'کانال تبادل ویژه',
			'special_exchange_id' => 'درخواست تبادل ویژه',
			'channels_group'      => 'گروه های کاربری',
			'date'                => 'زمان شروع',
			'finish_date'         => 'زمان پایان',
			'status'              => 'وضعیت',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpecialChannel()
	{
		return $this->hasOne(Channel::className(), ['id' => 'special_channel_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpecialExchange()
	{
		return $this->hasOne(SpecialExchange::className(), ['id' => 'special_exchange_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannelsGroup()
	{
		return $this->hasOne(ChannelGroup::className(), ['number' => 'channels_group']);
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
	public function getExchangeParticipants()
	{
		return $this->hasMany(ExchangeParticipant::className(), ['exchange_id' => 'id']);
	}
}