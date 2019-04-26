<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "error_log".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $where_is
 * @property integer $line
 * @property string $description
 * @property string $agent
 * @property string $ip
 * @property integer $date
 */
class ErrorLog extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'error_log';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'line', 'date'], 'integer'],
			[['line'], 'required'],
			[['description', 'agent'], 'string'],
			[['where_is'], 'string', 'max' => 255],
			[['ip'], 'string', 'max' => 50],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id' => 'ID',
			'user_id' => 'User ID',
			'where_is' => 'Where Is',
			'line' => 'Line',
			'description' => 'Description',
			'agent' => 'Agent',
			'ip' => 'Ip',
			'date' => 'Date',
		];
	}
	
	public static function set($user_id, $where_is, $line, $description)
	{
		$error              = new ErrorLog();
		$error->user_id     = $user_id;
		$error->where_is    = $where_is;
		$error->line        = $line;
		$error->description = $description;
		if (isset($_SERVER['HTTP_USER_AGENT']))
			$error->agent = $_SERVER['HTTP_USER_AGENT'];
		if (isset($_SERVER['REMOTE_ADDR']))
			$error->ip = $_SERVER['REMOTE_ADDR'];
		$error->date = time();
		$error->save();
		
		//	return $error->id;
	}
}