<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bot".
 *
 * @property integer   $id
 * @property string    $name
 * @property string    $username
 * @property string    $token
 * @property integer   $active
 *
 * @property Channel[] $channels
 */
class Bot extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'bot';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['name', 'username', 'token'], 'required'],
			[['active'], 'integer'],
			[['name', 'token'], 'string', 'max' => 255],
			[['username'], 'string', 'max' => 130],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'       => 'ID',
			'name'     => 'نام',
			'username' => 'نام کاربری',
			'token'    => 'توکن',
			'active'   => 'فعال است؟',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels()
	{
		return $this->hasMany(Channel::className(), ['bot_id' => 'id']);
	}
}