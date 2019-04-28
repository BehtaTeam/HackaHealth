<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "heart_rate".
 *
 * @property string  $id
 * @property string  $user_id
 * @property integer $rate
 * @property integer $date
 *
 * @property User    $user
 */
class HeartRate extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'heart_rate';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'rate', 'date'], 'required'],
			[['user_id', 'rate', 'date'], 'integer'],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'user_id' => 'کاربر',
			'rate'    => 'سرعت ضربان قلب',
			'date'    => 'تاریخ ثبت',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}