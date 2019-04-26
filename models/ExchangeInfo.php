<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "exchange_info".
 *
 * @property integer $id
 * @property integer $start_hour
 * @property integer $finish_hour
 * @property integer $date
 * @property integer $same_channels_count
 * @property integer $special_channels_count
 * @property integer $first_request_date
 * @property integer $last_request_date
 * @property integer $total_coin_earned
 * @property integer $total_coin_back
 * @property integer $violated_channels_count
 * @property integer $first_checking_date
 * @property integer $last_checking_date
 */
class ExchangeInfo extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'exchange_info';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['start_hour', 'finish_hour', 'date', 'same_channels_count', 'special_channels_count', 'first_request_date', 'last_request_date', 'total_coin_earned', 'total_coin_back', 'violated_channels_count', 'first_checking_date', 'last_checking_date'], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                      => 'ID',
			'start_hour'              => 'ساعت شروع',
			'finish_hour'             => 'ساعت پایان',
			'date'                    => 'تاریخ',
			'same_channels_count'     => 'کانال های هم اعضا',
			'special_channels_count'  => 'کانال های ویژه',
			'first_request_date'      => 'اولین درخواست',
			'last_request_date'       => 'آخرین درخواست',
			'total_coin_earned'       => 'سکه های دریافتی',
			'total_coin_back'         => 'سکه های بازگشتی',
			'violated_channels_count' => 'کانال های متخلف',
			'first_checking_date'     => 'شروع چکینگ',
			'last_checking_date'      => 'پایان چکینگ',
		];
	}
}