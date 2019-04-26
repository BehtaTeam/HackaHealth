<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
	public $username;
	public $password;
	public $rememberMe = true;
	
	private $_user = false;
	
	
	/**
	 * @return array the validation rules.
	 */
	public function rules()
	{
		return [
			// username and password are both required
			[['username', 'password'], 'required'],
			// password is validated by validatePassword()
			['password', 'validatePassword'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                 => 'ID',
			'first_name'         => 'نام',
			'last_name'          => 'نام خانوادگی',
			'username'           => 'نام کاربری',
			'password'           => 'رمز عبور',
			'phone_number'       => 'شماره موبایل',
			'email'              => 'پست الکترونیکی',
			'register_date'      => 'تاریخ ثبت نام',
			'last_activity_date' => 'تاریخ آخرین فعالیت',
			'last_login_ip'      => 'آخرین IP ورودی',
			'type'               => 'نوع کاربری',
			'active'             => 'تایید شده',
		];
	}
	
	/**
	 * Validates the password.
	 * This method serves as the inline validation for password.
	 *
	 * @param string $attribute the attribute currently being validated
	 * @param array  $params    the additional name-value pairs given in the rule
	 */
	public function validatePassword($attribute, $params)
	{
		if (!$this->hasErrors()) {
			$user = $this->getUser();
			
			if (!$user || !$user->validatePassword($this->password)) {
				$this->addError($attribute, 'نام کاربری یا رمز عبور صحیح وارد نشده است.');
			}
		}
	}
	
	/**
	 * Logs in a user using the provided username and password.
	 * @return boolean whether the user is logged in successfully
	 */
	public function login()
	{
		if ($this->validate()) {
			return Yii::$app->user->login($this->getUser(), 3600 * 24 * 30);
		}
		
		return false;
	}
	
	/**
	 * Finds user by [[username]]
	 *
	 * @return User|null
	 */
	public function getUser()
	{
		if ($this->_user === false) {
			$this->_user = User::findOne(['LOWER(email)' => strtolower($this->username), 'active' => 1]);
		}
		
		return $this->_user;
	}
}
