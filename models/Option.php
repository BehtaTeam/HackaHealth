<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "option".
 *
 * @property integer $id
 * @property string  $name
 * @property string  $value
 * @property integer $group_id
 * @property integer $sort
 */
class Option extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'option';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['group_id', 'sort'], 'integer'],
			[['name', 'value'], 'string', 'max' => 255],
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
			'value'    => 'مقدار',
			'group_id' => 'Group ID',
			'sort'     => 'Sort',
		];
	}
}