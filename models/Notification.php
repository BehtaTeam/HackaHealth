<?php

namespace app\models;

use app\components\Helper;
use app\components\Result;
use Yii;

/**
 * This is the model class for table "notification".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string  $url
 * @property string  $title
 * @property string  $body
 * @property integer $date
 * @property integer $type
 * @property integer $message_read
 *
 * @property User    $user
 */
class Notification extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'notification';
	}
	
	public static function getList($telegram_id, $access_token, $page_number, $per_page)
	{
		$user = User::findOne(['telegram_id' => $telegram_id]);
		if (!$user) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم وجود اطلاعات کاربر' . "telegram_id = $telegram_id");
			Result::runf700();
		}
		
		if ($user->access_token != $access_token) {
			ErrorLog::set($telegram_id, __FILE__, __LINE__, 'عدم صدور مجوز');
			Result::r403();
		}
		
		$start = ($page_number - 1) * $per_page;
		
		$list = [];
		foreach (Notification::find()->where(['user_id' => $user->id])->limit($per_page)->offset($start)->orderBy('id DESC')->all() as $notification) {
			$temp['is_read'] = $notification->message_read;
			$temp['type']    = (int)$notification->type;
			$temp['url']     = $notification->url;
			$temp['title']   = $notification->title;
			$temp['body']    = $notification->body;
			$temp['date']    = Helper::timestamp_to_shamsi($notification->date);
			
			$notification->message_read = 1;
			$notification->update();
			
			$list[] = $temp;
		}
		
		return [
			'total_count' => Notification::find()->where(['user_id' => $user->id])->count(),
			'list'        => $list
		];
		
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['user_id', 'url', 'title', 'date'], 'required'],
			[['user_id', 'date', 'type', 'message_read'], 'integer'],
			[['url', 'body'], 'string', 'max' => 255],
			[['title'], 'string', 'max' => 100],
			[['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'      => 'ID',
			'user_id' => 'User ID',
			'url'     => 'Url',
			'title'   => 'Title',
			'body'    => 'Body',
			'date'    => 'Date',
			'type'    => 'Type',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getUser()
	{
		return $this->hasOne(User::className(), ['id' => 'user_id']);
	}
}