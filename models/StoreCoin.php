<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "store_coin".
 *
 * @property integer $id
 * @property string  $identity
 * @property integer $coin_count
 * @property integer $price
 * @property integer $striked_value
 * @property integer $sort
 * @property integer $active
 */
class StoreCoin extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'store_coin';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['coin_count', 'price', 'sort'], 'required'],
			[['coin_count', 'price', 'striked_value', 'sort', 'active'], 'integer'],
			[['identity'], 'string', 'max' => 30],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'            => 'ID',
			'identity'      => 'Identity',
			'coin_count'    => 'Coin Count',
			'price'         => 'Price',
			'striked_value' => 'Striked Value',
			'sort'          => 'Sort',
			'active'        => 'Active',
		];
	}
}
