<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "status_type".
 *
 * @property integer               $id
 * @property string                $title
 *
 * @property Exchange[]            $exchanges
 * @property ExchangeParticipant[] $exchangeParticipants
 * @property SpecialExchange[]     $specialExchanges
 */
class StatusType extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'status_type';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['title'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'    => 'ID',
			'title' => 'Title',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchanges()
	{
		return $this->hasMany(Exchange::className(), ['status' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchangeParticipants()
	{
		return $this->hasMany(ExchangeParticipant::className(), ['status' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpecialExchanges()
	{
		return $this->hasMany(SpecialExchange::className(), ['status' => 'id']);
	}
}