<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "channel".
 *
 * @property integer               $id
 * @property string                $channel_id
 * @property integer               $chat_id
 * @property integer               $admin_id
 * @property string                $name
 * @property string                $title
 * @property string                $description
 * @property string                $join_link
 * @property integer               $member_count
 * @property integer               $last_update
 * @property integer               $channel_group
 * @property integer               $category_id
 * @property integer               $bot_id
 * @property integer               $ban_until
 * @property integer               $participate_at_1
 * @property integer               $participate_at_2
 * @property integer               $participate_at_3
 * @property integer               $participate_at_4
 * @property integer               $active
 *
 * @property Category              $category
 * @property Bot                   $bot
 * @property User                  $admin
 * @property ChannelViolation[]    $channelViolations
 * @property Exchange[]            $exchanges
 * @property ExchangeParticipant[] $exchangeParticipants
 * @property SpecialExchange[]     $specialExchanges
 */
class Channel extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'channel';
	}
	
	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['channel_id', 'admin_id', 'description', 'active'], 'required'],
			[['channel_id', 'admin_id', 'member_count', 'channel_group', 'category_id', 'bot_id', 'ban_until', 'participate_at_1', 'participate_at_2', 'participate_at_3', 'participate_at_4', 'chat_id', 'active', 'last_update'], 'integer'],
			[['title', 'join_link'], 'string'],
			[['name'], 'string', 'max' => 200],
			[['description'], 'string', 'max' => 255],
			[['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::className(), 'targetAttribute' => ['category_id' => 'id']],
			[['bot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bot::className(), 'targetAttribute' => ['bot_id' => 'id']],
			[['admin_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['admin_id' => 'id']],
		];
	}
	
	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'id'               => 'ID',
			'channel_id'       => 'ID کانال',
			'admin_id'         => 'ID مدیر',
			'chat_id'          => 'Chat ID',
			'name'             => 'نام کاربری',
			'title'            => 'عنوان',
			'description'      => 'توضیحات',
			'join_link'        => 'لینک جوین',
			'member_count'     => 'تعداد کاربر',
			'last_update'      => 'آخرین به روزرسانی',
			'channel_group'    => 'گروه',
			'category_id'      => 'دسته بندی',
			'bot_id'           => 'بات',
			'ban_until'        => 'توقیف تا',
			'participate_at_1' => 'شرکت در تبادل اول',
			'participate_at_2' => 'شرکت در تبادل دوم',
			'participate_at_3' => 'شرکت در تبادل سوم',
			'participate_at_4' => 'شرکت در تبادل چهارم',
			'active'           => 'فعال است؟',
		];
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCategory()
	{
		return $this->hasOne(Category::className(), ['id' => 'category_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBot()
	{
		return $this->hasOne(Bot::className(), ['id' => 'bot_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getAdmin()
	{
		return $this->hasOne(User::className(), ['id' => 'admin_id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getChannelViolations()
	{
		return $this->hasMany(ChannelViolation::className(), ['channel_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchanges()
	{
		return $this->hasMany(Exchange::className(), ['special_channel_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchangeParticipants()
	{
		return $this->hasMany(ExchangeParticipant::className(), ['channel_id' => 'id']);
	}
	
	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getSpecialExchanges()
	{
		return $this->hasMany(SpecialExchange::className(), ['channel_id' => 'id']);
	}
}