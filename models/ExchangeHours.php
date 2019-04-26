<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exchange_hours".
 *
 * @property integer $id
 * @property integer $start_hour
 * @property integer $end_hour
 * @property integer $active
 */
class ExchangeHours extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'exchange_hours';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['start_hour', 'end_hour'], 'required'],
			[['start_hour', 'end_hour', 'active'], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'start_hour' => 'Start Hour',
			'end_hour' => 'End Hour',
			'active' => 'Active',
		];
	}
}