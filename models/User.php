<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property integer            $id
 * @property integer            $telegram_id
 * @property string             $username
 * @property string             $first_name
 * @property string             $last_name
 * @property string             $phone
 * @property string             $access_token
 * @property integer            $register_date
 * @property integer            $last_activity_time
 * @property integer            $stock
 * @property integer            $spent
 * @property string             $last_login_ip
 * @property string             $device_id
 * @property string             $serial_number
 * @property string             $model
 * @property string             $manufacture
 * @property string             $brand
 * @property integer            $api_version
 * @property integer            $app_version
 * @property integer            $active
 * @property integer            $type
 * @property string             $email
 * @property string             $password
 *
 * @property Channel[]          $channels
 * @property ChannelViolation[] $channelViolations
 * @property CoinLog[]          $coinLogs
 * @property Notification[]     $notifications
 * @property StoreLog[]         $storeLogs
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
	const ROLE_USER  = 1;
	const ROLE_ADMIN = 10;
	
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
			[['telegram_id', 'access_token'], 'required'],
			[['telegram_id', 'register_date', 'last_activity_time', 'stock', 'spent', 'api_version', 'app_version', 'active'], 'safe'],
			[['username'], 'string', 'max' => 120],
			[['first_name', 'last_name'], 'string', 'max' => 255],
			[['phone', 'access_token'], 'string', 'max' => 20],
			[['last_login_ip', 'manufacture', 'brand'], 'string', 'max' => 30],
			[['email', 'password'], 'string', 'max' => 255],
			[['device_id', 'serial_number', 'model'], 'string', 'max' => 50],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                 => 'ID',
			'telegram_id'        => 'ID تلگرام',
			'username'           => 'نام کاربری',
			'first_name'         => 'نام',
			'last_name'          => 'نام خانوادگی',
			'phone'              => 'شماره تماس',
			'access_token'       => 'اکسس توکن',
			'register_date'      => 'تاریخ ثبت نام',
			'last_activity_time' => 'زمان آخرین فعالیت',
			'stock'              => 'موجودی',
			'spent'              => 'مصرف شده',
			'last_login_ip'      => 'IP آخرین ورود',
			'device_id'          => 'ID دستگاه',
			'serial_number'      => 'سریال نامبر',
			'model'              => 'مدل',
			'manufacture'        => 'کارخانه',
			'brand'              => 'برند',
			'api_version'        => 'نسخه Api',
			'app_version'        => 'نسخه برنامه',
			'active'             => 'فعال است؟',
			'email'              => 'پست الکترونیکی',
			'password'           => 'رمز عبور',
			'type'               => 'سطح دسترسی',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels()
	{
		return $this->hasMany(Channel::className(), ['admin_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCoinLogs()
	{
		return $this->hasMany(CoinLog::className(), ['user_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getNotifications()
	{
		return $this->hasMany(Notification::className(), ['user_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getStoreLogs()
	{
		return $this->hasMany(StoreLog::className(), ['user_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannelViolations()
	{
		return $this->hasMany(ChannelViolation::className(), ['reporter_id' => 'id']);
	}
	
	/**
	 * Finds an identity by the given ID.
	 *
	 * @param string|integer $id the ID to be looked for
	 *
	 * @return IdentityInterface the identity object that matches the given ID.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentity($id)
	{
		$model = self::findOne($id);
		
		return $model ? new static ($model) : null;
	}
	
	/**
	 * Finds an identity by the given token.
	 *
	 * @param mixed $token the token to be looked for
	 * @param mixed $type  the type of the token. The value of this parameter depends on the implementation.
	 *                     For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be
	 *                     `yii\filters\auth\HttpBearerAuth`.
	 *
	 * @return IdentityInterface the identity object that matches the given token.
	 * Null should be returned if such an identity cannot be found
	 * or the identity is not in an active state (disabled, deleted, etc.)
	 */
	public static function findIdentityByAccessToken($token, $type = null)
	{
		// TODO: Implement findIdentityByAccessToken() method.
	}
	
	/**
	 * Returns an ID that can uniquely identify a user identity.
	 * @return string|integer an ID that uniquely identifies a user identity.
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Returns a key that can be used to check the validity of a given identity ID.
	 *
	 * The key should be unique for each individual user, and should be persistent
	 * so that it can be used to check the validity of the user identity.
	 *
	 * The space of such keys should be big enough to defeat potential identity attacks.
	 *
	 * This is required if [[User::enableAutoLogin]] is enabled.
	 * @return string a key that is used to check the validity of a given identity ID.
	 * @see validateAuthKey()
	 */
	public function getAuthKey()
	{
		return $this->password;
	}
	
	/**
	 * Validates the given auth key.
	 *
	 * This is required if [[User::enableAutoLogin]] is enabled.
	 *
	 * @param string $authKey the given auth key
	 *
	 * @return boolean whether the given auth key is valid.
	 * @see getAuthKey()
	 */
	public function validateAuthKey($authKey)
	{
		return $this->password == $authKey;
	}
	
	public function validatePassword($password)
	{
		return ($password == $this->password);
	}
}