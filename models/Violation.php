<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "violation".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $first_time_penalty
 * @property integer $second_time_penalty
 * @property integer $active
 *
 * @property ChannelViolation[] $channelViolations
 * @property ExchangeParticipant[] $exchangeParticipants
 */
class Violation extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'violation';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title'], 'required'],
			[['description'], 'string'],
			[['first_time_penalty', 'second_time_penalty', 'active'], 'integer'],
			[['title'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'first_time_penalty' => 'First Time Penalty',
			'second_time_penalty' => 'Second Time Penalty',
			'active' => 'Active',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannelViolations()
	{
		return $this->hasMany(ChannelViolation::className(), ['reason' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchangeParticipants()
	{
		return $this->hasMany(ExchangeParticipant::className(), ['violation_id' => 'id']);
	}
}