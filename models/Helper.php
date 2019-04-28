<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "helper".
 *
 * @property integer $id
 * @property string  $username
 * @property string  $password
 * @property string  $email
 * @property integer $register_date
 * @property string  $first_name
 * @property string  $last_name
 * @property integer $gender
 * @property double  $lat
 * @property integer $long
 * @property integer $on_call
 */
class Helper extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'helper';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['username', 'password', 'email', 'register_date', 'first_name', 'last_name', 'gender'], 'required'],
			[['register_date', 'gender', 'on_call'], 'integer'],
			[['lat', 'long'], 'number'],
			[['username'], 'string', 'max' => 150],
			[['password', 'email'], 'string', 'max' => 255],
			[['first_name', 'last_name'], 'string', 'max' => 100],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'            => 'ID',
			'username'      => 'نام کاربری',
			'password'      => 'رمز عبور',
			'email'         => 'پست الکترونیک',
			'register_date' => 'تاریخ ثبت نام',
			'first_name'    => 'نام',
			'last_name'     => 'نام خانوادگی',
			'gender'        => 'جنسیت',
			'lat'           => 'Lat',
			'long'          => 'Long',
			'on_call'       => 'On Call',
		];
	}
}