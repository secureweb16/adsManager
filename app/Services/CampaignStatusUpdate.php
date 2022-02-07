<?php

namespace App\Services;

use App\Models\Campaign;
use Carbon\Carbon;
use App\Models\Notification;

class CampaignStatusUpdate{
	
	public function __construct(){
		$this->get_campaigns();
	}

	private function get_campaigns(){
		
		$average_cost = get_option_value('average_cost_value');
		$allcampaigns = Campaign::where('campaign_status','=','1')->where('remaing_daily','<',$average_cost)->get();		
		foreach ($allcampaigns as $key => $value) {
			$this->create_notification($value->advertiser_id,$value->campaign_name,$value->id);
			Campaign::where('id', $value->id)->update(['campaign_status' => '2']);
		}
	}

	private function create_notification($userid,$name,$campaignId){
		$notification = new Notification();
      	$notification->user_id		= $userid;
      	$notification->type 		= 'campaign_stop';
      	$notification->foruser		= 'advertiser';
      	$notification->message 		= $name.' campaign stop due to insufficient daily funds';
      	$notification->admin_message = $name.' campaign stop due to insufficient daily funds';
      	$notification->url 			= '';//url('/advertiser/campaigns/edit').'/'.encrypt($campaignId);
      	$notification->admin_url 	= '';
      	$notification->user_status  = '0';
      	$notification->admin_status = '0';
      	$notification->save();
	}

}