<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "channel_group".
 *
 * @property integer           $id
 * @property integer           $number
 * @property integer           $from_count
 * @property integer           $to_count
 * @property integer           $price
 *
 * @property Exchange[]        $exchanges
 * @property SpecialExchange[] $specialExchanges
 * @property SpecialExchange[] $specialExchanges0
 */
class ChannelGroup extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'channel_group';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['number', 'from_count', 'to_count', 'price'], 'required'],
			[['number', 'from_count', 'to_count', 'price'], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'         => 'ID',
			'number'     => 'Number',
			'from_count' => 'From Count',
			'to_count'   => 'To Count',
			'price'      => 'Price',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchanges()
	{
		return $this->hasMany(Exchange::className(), ['channels_group' => 'number']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpecialExchanges()
	{
		return $this->hasMany(SpecialExchange::className(), ['from_group' => 'number']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpecialExchanges0()
	{
		return $this->hasMany(SpecialExchange::className(), ['target_group' => 'number']);
	}
}