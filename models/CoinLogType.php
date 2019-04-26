<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "coin_log_type".
 *
 * @property integer $id
 * @property string $description
 * @property string $the_string
 *
 * @property CoinLog[] $coinLogs
 */
class CoinLogType extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'coin_log_type';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['description', 'the_string'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'description' => 'Description',
			'the_string' => 'The String',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCoinLogs()
	{
		return $this->hasMany(CoinLog::className(), ['type' => 'id']);
	}
}