<?php
/**
 * Created by PhpStorm.
 * User: behta
 * Date: 09/05/2017
 * Time: 06:48 PM
 */

namespace app\components;


use app\models\Channel;
use app\models\ChannelGroup;
use app\models\ChannelViolation;
use app\models\CoinLog;
use app\models\ErrorLog;
use app\models\Exchange;
use app\models\ExchangeHours;
use app\models\ExchangeInfo;
use app\models\ExchangeParticipant;
use app\models\Notification;
use app\models\Option;
use app\models\SpecialExchange;
use app\models\Violation;
use Yii;

class ExchangeManage
{
	public static function startExchange($at)
	{
		$info             = new ExchangeInfo();
		$info->start_hour = (int)date('H');
		$finish_hour      = ExchangeHours::find()->where('start_hour=' . (int)date('H'))->one();
		if ($finish_hour)
			$finish_hour = $finish_hour->end_hour;
		else
			$finish_hour = date('H') + 1;
		
		/*if (time() % 3600 > 500)
			return 0;*/
		$info->finish_hour = $finish_hour;
		$info->date        = time();
		
		$same_channels_count           = 0;
		$special_channels_count        = 0;
		$total_coin_earned             = 0;
		$info->first_request_date      = 0;
		$info->violated_channels_count = 0;
		$info->save();
		
		
		if ($info->hasErrors()) {
			Tools::debug($info->getErrors());
			ErrorLog::set(1, __FILE__, __LINE__, json_encode($info->getErrors()));
		}
		
		$info = ExchangeInfo::findOne($info->id);
		
		$c = Option::findOne(4)->value; // how many items in a exchange list
		
		$channel_groups = ChannelGroup::find()->all();
		
		/* @var ChannelGroup $channel_group */
		foreach ($channel_groups as $channel_group) {
			$participation = 'participate_at_' . $at;
			$channels      = Channel::find()
				->where([$participation => 1, 'channel_group' => $channel_group->number, 'active' => 1])
				->andWhere('ban_until <' . time())
				->andWhere('last_update >' . (time() - (3600 * 24 * 3)))
				->andWhere('bot_id IS NOT NULL')
				->orderBy('RAND()')
				->all();
			
			Tools::debug($channels);
			
			$n            = 0;
			$last_channel = end($channels);
			$last_id      = $last_channel->id;
			/* @var Channel $channel */
			/* @var Channel $item */
			/* @var Channel $special_channel */
			/* @var SpecialExchange $special_channel_req */
			foreach ($channels as $channel) {
				/*$recent_participation = ExchangeParticipant::find()
					->where(['channel_id' => $channel->id])
					->orderBy('id DESC')
					->one();
				
				if ($recent_participation) {
					if ($recent_participation->exchange->date > (time() - 5000)) {
						continue;
					}
				}*/
				
				if ($n == 0) {
					//$text = '<a href="https://t.me/teletabadolapp/">تبادل گر هوشمند تلگرام</a>' . PHP_EOL;
					
					$is_tabadol          = false;
					$special_channel_req = null;
					$special_channel_req = SpecialExchange::find()
						->where(['status' => 2, 'target_group' => $channel_group->number])
						->one(); // وضعیت انجام نشده
					
					if (!$special_channel_req) {
						$special_channel_req = SpecialExchange::findOne(1);
						$is_tabadol          = true;
					} else {
						
						$notification          = new Notification();
						$notification->user_id = $special_channel_req->channel->admin_id;
						$notification->url     = Yii::$app->params['package_name'];
						$notification->title   = 'شرکت در تبادل ویژه';
						$notification->body    = 'تبادل برای کانال ' . $channel->name . ' آغاز شد';
						$notification->date    = time();
						$notification->type    = 9;
						$notification->save();
						
						if ($notification->hasErrors())
							ErrorLog::set($channel->admin_id, __FILE__, __LINE__, json_encode($notification->getErrors()));
						
					}
					
					$special_channel = $special_channel_req->channel;
					
					if ($special_channel->join_link)
						$text = '⭕️ <a href="' . $special_channel->join_link . '/">' . $special_channel->description . '</a>' . PHP_EOL;
					else
						$text = '⭕️ <a href="https://t.me/' . $special_channel->name . '/">' . $special_channel->description . '</a>' . PHP_EOL;
					
					$collection = [];
				}
				
				
				$collection[] = $channel;
				
				ExchangeManage::addLine($text, $channel);
				$n++;
				echo $n;
				
				if ($n == $c OR $channel->id === $last_id) {
					if ($n > 3) {
						$n = 0;
						
						if (!$is_tabadol) {
							$special_channel_req->status = 3; // وضعیت در حال انجام
							$special_channel_req->update();
							$total_coin_earned += $special_channel_req->paid_amount;
							$special_channels_count++;
						}
						
						$exchange                      = new Exchange();
						$exchange->special_channel_id  = $special_channel->id;
						$exchange->special_exchange_id = $special_channel_req->id;
						$exchange->channels_group      = $channel_group->number;
						$exchange->date                = time();
						$exchange->status              = 5; // در حال انجام
						$exchange->info_id             = $info->id;
						$exchange->save();
						
						if ($exchange->hasErrors())
							ErrorLog::set($exchange->id, __FILE__, __LINE__, json_encode($exchange->getErrors()));
						
						if (!$is_tabadol)
							$text .= PHP_EOL . '_____________________________' . PHP_EOL . PHP_EOL . '<a href="https://t.me/teletabadolapp/">اولین نرم افزار تمام اتوماتیک تبادل کانال های تلگرام</a>' . PHP_EOL;
						
						foreach ($collection as $item) {
							usleep(20000);
							
							$telegram = new Telegram($item);
							$result   = $telegram->sendMessage($text, 'HTML');
							
							if ($info->first_request_date == 0)
								$info->first_request_date = time();
							$info->last_request_date = time();
							$same_channels_count++;
							
							if ($result['ok'] == true) {
								$notification          = new Notification();
								$notification->user_id = $item->admin_id;
								$notification->url     = Yii::$app->params['package_name'];
								$notification->title   = 'آغاز تبادل';
								$notification->body    = 'تبادل برای کانال ' . $item->name . ' آغاز شد';
								$notification->date    = time();
								$notification->type    = 10;
								$notification->save();
								
								if ($notification->hasErrors()) {
									ErrorLog::set($item->admin_id, __FILE__, __LINE__, json_encode($notification->getErrors()));
									Tools::debug($notification->getErrors());
								}
								
								$ep              = new ExchangeParticipant();
								$ep->exchange_id = $exchange->id;
								$ep->channel_id  = $item->id;
								$ep->message_id  = $result['result']->message_id;
								$ep->status      = 5; // در حال انجام
								$ep->save();
								
								if ($ep->hasErrors())
									ErrorLog::set($item->admin_id, __FILE__, __LINE__, json_encode($ep->getErrors()));
								
							} else {
								ErrorLog::set($item->admin_id, __FILE__, __LINE__, $result['description']);
								
								if ($result['error_code'] == 403) {
									$violation = Violation::findOne(1);
									
									$channel_violation              = new ChannelViolation();
									$channel_violation->channel_id  = $item->id;
									$channel_violation->reason      = $violation->id;
									$channel_violation->description = $violation->description;
									$channel_violation->date        = time();
									$channel_violation->save();
									
									$notification          = new Notification();
									$notification->user_id = $item->admin_id;
									$notification->url     = Yii::$app->params['package_name'];
									$notification->title   = 'گزارش تخلف';
									$notification->body    = 'کانال ' . $item->name . ' هنگام تبادل مرتکب تخلف شده است';
									$notification->date    = time();
									$notification->type    = 11;
									$notification->save();
									
									if ($notification->hasErrors())
										ErrorLog::set($item->admin_id, __FILE__, __LINE__, json_encode($notification->getErrors()));
									
									
									if ($channel_violation->hasErrors())
										ErrorLog::set($item->admin->id, __FILE__, __LINE__, json_encode($channel_violation->getErrors()));
									
									$ep->violation_id = $channel_violation->id;
									$ep->status       = 8;
									$ep->save();
									
									$info->violated_channels_count++;
									
									if ($ep->hasErrors())
										ErrorLog::set($item->admin->id, __FILE__, __LINE__, json_encode($ep->getErrors()));
									
									// @todo more penalty next time
									$item->ban_until = time() + $violation->first_time_penalty;
									$item->update();
									
									if ($item->hasErrors())
										ErrorLog::set($item->admin->id, __FILE__, __LINE__, json_encode($item->getErrors()));
								}
							}
						}
					}
				}
			}
		}
		
		$info->same_channels_count    = $same_channels_count;
		$info->total_coin_earned      = $total_coin_earned;
		$info->total_coin_back        = 0;
		$info->special_channels_count = $special_channels_count;
		$info->update();
		if ($info->hasErrors())
			ErrorLog::set(1, __FILE__, __LINE__, json_encode($info->getErrors()));
	}
	
	public static function finishExchange()
	{
		$o = Option::findOne(4)->value;  // how many items in a exchange list
		
		$this_hour = (int)date('H');
		/*$end_hour  = ExchangeHours::find()->where(['end_hour' => $this_hour + 1])->one();
		if (!$end_hour) {
			Exchange::updateAll(['status' => 9], ['status' => 5]); // unknown status
			
			return 0;
		}*/
		
		
		$exchanges = Exchange::find()
			->where(['status' => 5])
			->andWhere('date >' . (time() - 3 * 3600))
			->all(); // در حال انجام
		
		/* @var Exchange $exchange */
		foreach ($exchanges as $exchange) {
			if (!isset($info)) {
				$info                      = ExchangeInfo::findOne($exchange->info_id);
				$info->total_coin_back     = 0;
				$info->first_checking_date = 0;
			}
			$exchange_participants = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->all();
			
			/*$end_hour = ExchangeHours::find()->where(['end_hour' => $this_hour])->one();
			if ($end_hour) {
				$exchange->status      = 9;
				$exchange->finish_date = $exchange->date + 3600; // @todo
				$exchange->update();
				
				$special_exchange         = $exchange->specialExchange;
				$special_exchange->status = 9;
				$special_exchange->update();
				
				ExchangeParticipant::updateAll(['status' => 9], ['exchange_id' => $exchange->id]);
				
				continue;
			}*/
			
			$c    = 0; // violated channels counter
			$text = '<a href="https://t.me/teletabadolapp/">تبادل گر هوشمند تلگرام</a>' . PHP_EOL;
			
			$special_channel = $exchange->specialChannel;
			
			ExchangeManage::addLine($text, $special_channel);
			
			/* @var ExchangeParticipant $participant */
			foreach ($exchange_participants as $participant) {
				if ($participant->status == 8) // violated channel
					$c++;
				
				$channel = $participant->channel;
				
				ExchangeManage::addLine($text, $channel);
			}
			
			$exchange_participants = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->all();
			foreach ($exchange_participants as $participant) {
				if ($participant->status == 7) // done before
					continue;
				
				$channel = $participant->channel;
				
				if ($participant->status != 8) {
					usleep(20000);
					
					if ($info->first_checking_date == 0)
						$info->first_checking_date = time();
					$info->last_checking_date = time();
					
					$telegram = new Telegram($channel);
					$result   = $telegram->editMessage($text . '-', $participant->message_id);
					Tools::debug($result);
					
					if ($result['ok'] == false) {
						
						$info->violated_channels_count++;
						
						if ($result['error_code'] == 400) {
							$violation = Violation::findOne(2);
							$c++;
						} elseif ($result['error_code'] == 403) {
							$violation = Violation::findOne(1);
							$c++;
						} else {
							$violation = Violation::findOne(7);
							$c++;
							$violation->description = 'خطای شماره ' . $result['error_code'];
						}
						
						$channel_violation              = new ChannelViolation();
						$channel_violation->channel_id  = $channel->id;
						$channel_violation->reason      = $violation->id;
						$channel_violation->description = $violation->description;
						$channel_violation->date        = time();
						$channel_violation->save();
						
						if ($channel_violation->hasErrors())
							ErrorLog::set($channel->admin->id, __FILE__, __LINE__, json_encode($channel_violation->getErrors()));
						
						$participant->violation_id = $channel_violation->id;
						$participant->status       = 8;
						$participant->update();
						
						if ($participant->hasErrors())
							ErrorLog::set($channel->admin_id, __FILE__, __LINE__, json_encode($participant->getErrors()));
						
						if ($channel->ban_until < time()) {
							// @todo more penalty next time
							$channel->ban_until = time() + $violation->first_time_penalty;
							$channel->update();
						}
						
						if ($channel->hasErrors())
							ErrorLog::set($channel->admin_id, __FILE__, __LINE__, json_encode($channel->getErrors()));
						
						$notification          = new Notification();
						$notification->user_id = $channel->admin_id;
						$notification->url     = Yii::$app->params['package_name'];
						$notification->title   = 'گزارش تخلف';
						$notification->body    = 'کانال ' . $channel->name . ' هنگام تبادل مرتکب تخلف شده است';
						$notification->date    = time();
						$notification->type    = 11;
						$notification->save();
						
						if ($notification->hasErrors())
							ErrorLog::set($channel->admin_id, __FILE__, __LINE__, json_encode($notification->getErrors()));
						
					} else {
						$participant->status = 7;
						$participant->update();
						
						if ($participant->hasErrors())
							ErrorLog::set($channel->admin->id, __FILE__, __LINE__, json_encode($participant->getErrors()));
						
						$notification          = new Notification();
						$notification->user_id = $channel->admin_id;
						$notification->url     = Yii::$app->params['package_name'];
						$notification->title   = 'پایان موفقیت آمیز تبادل لیستی';
						$notification->body    = 'کانال ' . $channel->name . ' با موفقیت در تبادل لیستی با کد پیگیری ' . $exchange->id . ' شرکت کرد';
						$notification->date    = time();
						$notification->type    = 12;
						$notification->save();
						
						if ($notification->hasErrors())
							ErrorLog::set($channel->admin_id, __FILE__, __LINE__, json_encode($notification->getErrors()));
						
					}
					
				}
				
			}
			
			$special_exchange         = $exchange->specialExchange;
			$special_exchange_channel = $special_exchange->channel;
			
			$notification          = new Notification();
			$notification->user_id = $special_exchange_channel->admin_id;
			$notification->url     = Yii::$app->params['package_name'];
			$notification->title   = 'پایان تبادل ویژه';
			$notification->body    = 'تبادل ویژه برای کانال ' . $special_exchange_channel->name . ' به پایان رسید';
			$notification->date    = time();
			$notification->type    = 13;
			$notification->save();
			
			if ($notification->hasErrors())
				ErrorLog::set($special_exchange_channel->admin_id, __FILE__, __LINE__, json_encode($notification->getErrors()));
			
			$participants_count = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->count();
			
			$minus = (int)($o - $participants_count);
			
			$paid_amount_back = (($c + $minus) / $o) * $special_exchange->paid_amount;
			$paid_amount_back = (int)$paid_amount_back;
			
			if ($paid_amount_back > 0) {
				$special_exchange_user        = $special_exchange->channel->admin;
				$special_exchange_user->spent = $special_exchange_user->spent - $paid_amount_back;
				$special_exchange_user->update();
				
				if ($special_exchange_user->hasErrors())
					ErrorLog::set($special_exchange_user->id, __FILE__, __LINE__, json_encode($special_exchange_user->getErrors()));
				
				CoinLog::Set($special_exchange_user->id, $paid_amount_back, 4);
			}
			
			$info->total_coin_back += $paid_amount_back;
			
			$special_exchange->paid_amount_back = (int)$paid_amount_back;
			$special_exchange->status           = 4;
			$special_exchange->update();
			
			if ($special_exchange->hasErrors())
				ErrorLog::set($special_exchange->channel->admin->id, __FILE__, __LINE__, json_encode($special_exchange->getErrors()));
			
			$exchange->status      = 6;
			$exchange->finish_date = $exchange->date + 3600; // @todo
			$exchange->update();
			
			if ($exchange->hasErrors())
				ErrorLog::set($exchange->id, __FILE__, __LINE__, json_encode($exchange->getErrors()));
			
		}
		
		$info->update();
		if ($info->hasErrors())
			ErrorLog::set($exchange->id, __FILE__, __LINE__, json_encode($info->getErrors()));
		
	}
	
	public static function deleteExchange()
	{
		$exchanges = Exchange::find()
			->andWhere('date >' . (time() - 3 * 3600))
			->all();
		
		/* @var Exchange $exchange */
		foreach ($exchanges as $exchange) {
			$exchange_participants = ExchangeParticipant::find()->where(['exchange_id' => $exchange->id])->all();
			
			/* @var ExchangeParticipant $participant */
			foreach ($exchange_participants as $participant) {
				usleep(30000);
				
				$channel  = $participant->channel;
				$telegram = new Telegram($channel);
				$result   = $telegram->deleteMessage($participant->message_id);
				Tools::debug($result);
			}
			
		}
	}
	
	
	public static function addLine(&$text, $channel)
	{
		$text .= PHP_EOL . $channel->description . PHP_EOL;
		if ($channel->join_link)
			$text .= $channel->join_link . PHP_EOL;
		else
			$text .= '@' . $channel->name . PHP_EOL;
		//$text .= "<a href=\"https://t.me/$channel->name/\">$channel->description</a>" . PHP_EOL;
		
	}
}