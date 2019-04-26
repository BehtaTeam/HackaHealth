<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "store_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $stock
 * @property integer $price
 * @property string  $developer_payload
 * @property string  $token
 * @property string  $purchase_state
 * @property string  $market
 * @property integer $date
 *
 * @property User    $user
 */
class StoreLog extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'store_log';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'stock', 'token', 'date'], 'required'],
			[['user_id', 'stock', 'price', 'date'], 'integer'],
			[['developer_payload', 'token'], 'string', 'max' => 255],
			[['purchase_state'], 'string', 'max' => 5],
			[['market'], 'string', 'max' => 20],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'                => 'ID',
			'user_id'           => 'id کاربر',
			'stock'             => 'تعداد سکه',
			'price'             => 'مبلغ به تومان',
			'developer_payload' => 'پیلود توسعه دهنده',
			'purchase_state'    => 'وضعیت خرید',
			'date'              => 'تاریخ',
			'market'            => 'فروشگاه',
			'token'             => 'توکن',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
	
	public static function last24sale()
	{
		$last24 = time() - (24 * 3600);
		
		return StoreLog::find()->where("date > $last24" . ' AND purchase_state="موفق"')->sum('price');
	}
	
	public static function total_sale()
	{
		return StoreLog::find()->where('purchase_state="موفق"')->sum('price');
	}
	
	public static function from0sale()
	{
		$from0 = strtotime('today Asia/Tehran');
		
		return StoreLog::find()->where("date > $from0" . ' AND purchase_state="موفق"')->sum('price');
	}
}