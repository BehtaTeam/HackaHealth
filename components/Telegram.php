<?php

namespace app\components;

use app\components\Helper;
use app\components\Tools;
use app\models\Bot;
use app\models\Channel;

require_once(dirname(__FILE__) . '/jdf.php');

class Telegram
{
	/* @var  Channel $channel */
	public $channel;
	
	public function __construct($channel)
	{
		$this->channel = $channel;
	}
	
	public static function getBotToken($bot_id)
	{
		return 'bot' . Bot::findOne($bot_id)->token;
	}
	
	public function _getBotToken()
	{
		return 'bot' . Bot::findOne($this->channel->bot_id)->token;
	}
	
	public static function getActiveBot()
	{
		return Bot::find()->where(['active' => 1])->orderBy('RAND()')->one();
	}
	
	/* @var  Channel $channel */
	public function sendMessage($text, $parse_mode = 'Markdown')
	{
		$params = [
			'chat_id'    => $this->channel->chat_id,
			'text'       => $text,
			'parse_mode' => $parse_mode,
		];
		
		return Helper::httpPost('https://api.telegram.org/' . $this->_getBotToken() . '/sendMessage', $params);
	}
	
	public function editMessage($text, $message_id)
	{
		$params = [
			'chat_id'    => $this->channel->chat_id,
			'text'       => $text,
			'message_id' => (int)$message_id,
			'parse_mode' => 'HTML',
		];
		
		return Helper::httpPost('https://api.telegram.org/' . $this->_getBotToken() . '/editMessageText', $params);
	}
	
	public function deleteMessage($message_id)
	{
		$params = [
			'chat_id'    => $this->channel->chat_id,
			'message_id' => (int)$message_id,
		];
		
		return Helper::httpPost('https://api.telegram.org/' . $this->_getBotToken() . '/deleteMessage', $params);
	}
	
	public function getChat()
	{
		$params = [
			'chat_id' => $this->channel->chat_id,
		];
		
		return Helper::httpPost('https://api.telegram.org/' . $this->_getBotToken() . '/getChat', $params);
	}
	
	public static function getChatMembersCount($chat_id, $bot_id)
	{
		/*
		 *
[
    'ok' => true
    'result' => 12921
]

		 */
		$params = [
			'chat_id' => $chat_id
		];
		
		$token = Telegram::getBotToken($bot_id);
		$url   = "https://api.telegram.org/$token/getChatMembersCount";
		
		return (Helper::httpPost($url, $params));
	}
	
	public static function getChatAdministrators($chat_id, $bot_id)
	{
		/*
		 *

[
    'ok' => false
    'error_code' => 400
    'description' => 'Bad Request: channel members are unavailable'
]

		[
    'ok' => true
    'result' => [
        0 => stdClass#1
        (
            [user] => stdClass#2
            (
                [id] => 277106356
                [first_name] => 'TeleTabadol'
                [username] => 'TeleTabadolBot'
            )
            [status] => 'administrator'
        )
        1 => stdClass#3
        (
            [user] => stdClass#4
            (
                [id] => 74052859
                [first_name] => 'Saleh'
                [last_name] => 'Hashemi'
                [username] => 'saleh_hashemi'
            )
            [status] => 'creator'
        )
    ]
]


		 */
		$params = [
			'chat_id' => $chat_id
		];
		
		$token = Telegram::getBotToken($bot_id);
		$url   = "https://api.telegram.org/$token/getChatAdministrators";
		
		return (Helper::httpPost($url, $params));
	}
	
	
}