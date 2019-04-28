<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "area".
 *
 * @property integer $id
 * @property double  $lat1
 * @property double  $long1
 * @property double  $lat2
 * @property double  $long2
 * @property double  $lat3
 * @property double  $long3
 * @property double  $lat4
 * @property double  $long4
 * @property integer $pollute
 */
class Area extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'area';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['lat1', 'long1', 'lat2', 'long2', 'lat3', 'long3', 'lat4', 'long4'], 'required'],
			[['lat1', 'long1', 'lat2', 'long2', 'lat3', 'long3', 'lat4', 'long4'], 'number'],
			[['pollute'], 'integer'],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'lat1'    => 'Lat1',
			'long1'   => 'Long1',
			'lat2'    => 'Lat2',
			'long2'   => 'Long2',
			'lat3'    => 'Lat3',
			'long3'   => 'Long3',
			'lat4'    => 'Lat4',
			'long4'   => 'Long4',
			'pollute' => 'میزان آلودگی',
		];
	}
}