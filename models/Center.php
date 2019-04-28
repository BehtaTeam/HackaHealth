<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "center".
 *
 * @property integer $id
 * @property string  $title
 * @property double  $lat
 * @property double  $long
 * @property integer $type
 * @property string  $description
 */
class Center extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'center';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'lat', 'long'], 'required'],
			[['lat', 'long'], 'number'],
			[['type'], 'integer'],
			[['description'], 'string'],
			[['title'], 'string', 'max' => 255],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'          => 'ID',
			'title'       => 'عنوان',
			'lat'         => 'Lat',
			'long'        => 'Long',
			'type'        => 'نوع',
			'description' => 'توضیحات',
		];
	}
}