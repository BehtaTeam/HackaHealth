<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string  $id
 * @property string  $username
 * @property string  $password
 * @property string  $email
 * @property integer $register_date
 * @property integer $picture_id
 * @property integer $age
 * @property integer $gender
 * @property integer $height
 * @property integer $weight
 * @property integer $status
 * @property string  $confidence_number
 * @property integer $confidence_number_enabled
 * @property integer $emergency_number_enabled
 * @property integer $pollution_notif_alert
 * @property string  $api_token
 * @property string  $first_name
 * @property string  $last_name
 * @property integer $healthy_notif
 */
class User extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'user';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['register_date', 'age', 'confidence_number'], 'required'],
			[['register_date', 'picture_id', 'age', 'gender', 'height', 'weight', 'status', 'confidence_number_enabled', 'emergency_number_enabled', 'pollution_notif_alert', 'healthy_notif'], 'integer'],
			[['username'], 'string', 'max' => 150],
			[['password'], 'string', 'max' => 255],
			[['email'], 'string', 'max' => 254],
			[['confidence_number'], 'string', 'max' => 15],
			[['api_token'], 'string', 'max' => 40],
			[['first_name', 'last_name'], 'string', 'max' => 256],
			[['api_token'], 'unique'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                        => 'ID',
			'username'                  => 'نام کاربری',
			'password'                  => 'رمز عبور',
			'email'                     => 'پست الکترونیک',
			'register_date'             => 'تاریخ ثبت نام',
			'picture_id'                => 'تصویر پروفایل',
			'age'                       => 'سن',
			'gender'                    => 'جنسیت',
			'height'                    => 'قد',
			'weight'                    => 'وزن',
			'status'                    => 'وضعیت سلامتی',
			'confidence_number'         => 'شماره ی آرامش بخش',
			'confidence_number_enabled' => 'شماره ی آرامش بخش فعال است؟',
			'emergency_number_enabled'  => 'شماره اورژانسی فعال است؟',
			'pollution_notif_alert'     => 'اعلان آلودگی هوا فعال است؟',
			'api_token'                 => 'توکن',
			'first_name'                => 'نام',
			'last_name'                 => 'نام خانوادگی',
			'healthy_notif'             => 'اعلان های آموزشی سلامت فعال است؟',
		];
	}
}