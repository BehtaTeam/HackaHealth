<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property integer $sort
 * @property integer $active
 *
 * @property Channel[] $channels
 */
class Category extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'category';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['title', 'sort'], 'required'],
			[['description'], 'string'],
			[['sort', 'active'], 'integer'],
			[['title'], 'string', 'max' => 100],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'title' => 'Title',
			'description' => 'Description',
			'sort' => 'Sort',
			'active' => 'Active',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannels()
	{
		return $this->hasMany(Channel::className(), ['category_id' => 'id']);
	}
}