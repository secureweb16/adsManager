<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Option;
use App\Models\Campaign;
use App\Models\FundsDetails;
use App\Models\PublisherPayment;
use App\Models\PublisherAccount;
use App\Models\CampaignPublishGroup;
use App\Models\TelegramGroup;
use App\Models\Notification;
use App\Models\Tier;
use App\Models\TierReport;


if(!function_exists('GetNotificationaAdmin')){
	function GetNotificationaAdmin() {
		return Notification::where('admin_status','0')->orderBy('created_at', 'desc')->get();
	}
}

if(!function_exists('GetNotificationaUser')){
	function GetNotificationaUser() {
		return Notification::where('user_status','0')->where('user_id','=',Auth::user()->id)->orderBy('created_at', 'desc')->get();
	}
}

if(!function_exists('CreateNotificatons')){
	function CreateNotificatons($notificationData) {
		$notification = new Notification();
		$notification->user_id 		= $notificationData['user_id'];
		$notification->type 		= $notificationData['type'];
		$notification->foruser 		= $notificationData['foruser'];
		$notification->message 		= $notificationData['message'];
		$notification->admin_message = $notificationData['admin_message'];
		$notification->url 			= $notificationData['url'];
		$notification->admin_url 	= $notificationData['admin_url'];
		$notification->user_status 	= $notificationData['user_status'];
		$notification->admin_status = $notificationData['admin_status'];
		$notification->save();
	}
}

if(!function_exists('update_option_value')){
	function update_option_value($key,$value) {
		$option =  Option::where('key',$key)->first();
		if(!empty($option)){
			Option::where('key', $key)->update([
				'value' => $value
			]);
		}else{
			$option = new Option();
			$option->key = $key;
			$option->value = $value;
			$option->save();
		}
	}
}


if(!function_exists('get_option_value')){	
	function get_option_value($key) {
		$option =  Option::where('key',$key)->first();
		return (isset($option->value))?$option->value:'';
	}
}


if(!function_exists('get_campaign_name')){	
	function get_campaign_name($id) {
		$campaign =  Campaign::where('id',$id)->first();
		return (isset($campaign->campaign_name))?$campaign->campaign_name:'';
	}
}


if(!function_exists('get_total_funds')){
	function get_total_funds($id) {
		$advertiserfunds = FundsDetails::where('user_id',$id)->first();
		return (isset($advertiserfunds->remaning_funds) && $advertiserfunds->remaning_funds != '')?$advertiserfunds->remaning_funds:0;
	}
}

if(!function_exists('get_publisher_payout')){
	function get_publisher_payout($id) {
		$publisherPayment = PublisherPayment::where('publisher_id',$id)->first();
		return (isset($publisherPayment->payable_amount) && $publisherPayment->payable_amount != '')?$publisherPayment->payable_amount:0;
	}
}


if(!function_exists('publisher_mearchant_id')){
	function publisher_mearchant_id($id) {
		$publisherAccount = PublisherAccount::where('user_id',$id)->first();
		return $publisherAccount;
	}
}

if(!function_exists('wallet_address')){
	function wallet_address($id) {
		$publisherAccount = PublisherAccount::where('user_id',$id)->first();
		return (isset($publisherAccount->wallet_address) && $publisherAccount->wallet_address != '')?$publisherAccount->wallet_address:'';
	}
}

if(!function_exists('publish_time_from_publish_group')){
	function publish_time_from_publish_group($campaign_id,$telegram_id) {
		$campaignPublishGroup = CampaignPublishGroup::where('campaign_id','=',$campaign_id)
		->where('telegram_group_id','=',$telegram_id)
		->orderBy('created_at', 'DESC')
		->first();
		return (isset($campaignPublishGroup->publish_time) && $campaignPublishGroup->publish_time != '')?$campaignPublishGroup->publish_time:'';
	}
}

if(!function_exists('frequency_time_from_telegram_group')){
	function frequency_time_from_telegram_group($id) {
		$telegramGroup = TelegramGroup::where('id',$id)->first();
		return (isset($telegramGroup->frequency_of_ads) && $telegramGroup->frequency_of_ads != '')?$telegramGroup->frequency_of_ads:'';
	}
}

if(!function_exists('telegram_group_name')){
	function telegram_group_name($id) {
		$telegramGroup = TelegramGroup::where('id',$id)->first();
		return (isset($telegramGroup->telegram_group) && $telegramGroup->telegram_group != '')?$telegramGroup->telegram_group:'';
	}
}

if(!function_exists('UserName')){
	function UserName($id) {
		$user = User::where('id',$id)->first();
		return (isset($user->first_name) && $user->first_name != '')?$user->first_name.' '.$user->last_name:'';
	}
}

if(!function_exists('UserEmail')){
	function UserEmail($id) {
		$user = User::where('id',$id)->first();
		return (isset($user->email) && $user->email != '')?$user->email:'';
	}
}

if(!function_exists('PayoutPersentage')){
	function PayoutPersentage($id) {
		$user = User::where('id',$id)->first();
		return (isset($user->earn_percentage) && $user->earn_percentage != '')?$user->earn_percentage:'';
	}
}

if(!function_exists('get_tier_total_publish')){
	function get_tier_total_publish($id) {
		$noOfPublish = TierReport::where('tier_id',$id)->sum('no_of_publish');
		return $noOfPublish;
	}
}

if(!function_exists('get_tier_total_clicks')){
	function get_tier_total_clicks($id) {
		$noOfClicks = TierReport::where('tier_id',$id)->sum('no_of_clicks');
		return $noOfClicks;
	}
}