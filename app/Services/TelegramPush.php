<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\TelegramGroup;
use App\Models\TelegramTiming;
use Carbon\Carbon;
use App\Models\CampaignReport;
use App\Models\CampaignPublishGroup;
use App\Models\PublisherReport;
use App\Models\Tier;
use App\Models\TierReport;

/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;


class TelegramPush{

	private $group_publish;
	
	public function __construct(){
		$this->group_publish = 5;
		return $this->fetchCamapign();
	}

	/*****************
	**
	Campaign Return
	**
	***********/

	public function fetchCamapign(){
		
		$CampaignPPC = Campaign::where('admin_approval','=','1')->orderBy('pay_ppc', 'DESC')->limit(2)->get();
		if(count($CampaignPPC) < 1){ exit; }
		$averageCost = (isset($CampaignPPC[1]))?$CampaignPPC[1]->pay_ppc:$CampaignPPC[0]->pay_ppc;
		$averageCost = $averageCost+0.01;
		update_option_value('average_cost_value',$averageCost);
		$allcampaign = Campaign::where('campaign_status','1')
		->where('admin_approval','1')
		->where('remaing_daily','>',$averageCost)
		->get();
		return $this->send_campaign_for_publishing($allcampaign);

	}

	private function send_campaign_for_publishing($allcampaign){

		$telegram_group_hrs = get_option_value('telegram_group_hrs');
		$averageTime	= strtotime(date("Y-m-d H:i:s", strtotime('-20 minutes')));
		/* 19-01-2022 latest backup with average time condation */
		
		foreach ($allcampaign as $key => $campaign) {

			/*Check Timenig telegram_group_hrs hours */

			$publishGroupData = CampaignPublishGroup::where('publish_time','<=',$averageTime)
			->where('campaign_id','=',$campaign->id)
			->where('clicks','=',0)
			->get();
			
			$publishGroupDataNotClicks = CampaignPublishGroup::where('campaign_id','=',$campaign->id)
			->where('clicks','=',0)
			->pluck('telegram_group_id');

			$checkPublishOrNOT = CampaignPublishGroup::where('campaign_id','=',$campaign->id)->get();

			if(count($publishGroupData) > 0){
				
				/* when time is gone for particualr campign */
				$finalpublish = $this->group_publish-count($publishGroupData);
				
				$publishGroup = CampaignPublishGroup::where('publish_time','<=',$averageTime)
				->where('campaign_id','=',$campaign->id)
				->where('clicks','=',0)
				->pluck('telegram_group_id')
				->toArray();
				
				$this->delete_message_form_telegram_group($publishGroupData);

				$telegramGroup = $this->get_teleram_group($publishGroup);
				if(count($publishGroupData) == $this->group_publish){
					$this->send_message_telegram_group_process($telegramGroup,$campaign);
				}else{
					$this->send_message_telegram_group_process_no_of_groups_publish($telegramGroup,$campaign,$finalpublish);
				}
			}else if(count($checkPublishOrNOT) == 0 ){
				/* Initial Condation when no campaign is published */
				$publishGroup = array(0);
				$telegramGroup = $this->get_teleram_group($publishGroup);
				$this->send_message_telegram_group_process($telegramGroup,$campaign);

			}else{
				$publishGroupDataClicks = CampaignPublishGroup::where('campaign_id','=',$campaign->id)
				->where('clicks','=',1)
				->pluck('telegram_group_id');
				$publishGroupDataNotClicks = CampaignPublishGroup::where('campaign_id','=',$campaign->id)
				->where('clicks','=',0)
				->pluck('telegram_group_id');

				if(count($publishGroupDataNotClicks) == 0){
					/* when all clicks but time is not gone */
					$telegramGroup = $this->get_teleram_group($publishGroupDataNotClicks);
					$this->send_message_telegram_group_process($telegramGroup,$campaign);
				}	else if(count($publishGroupDataNotClicks) < $this->group_publish ){
					/* when someone is clicked and somone is not */
					$finalpublish = $this->group_publish-count($publishGroupDataNotClicks);
					$telegramGroup = $this->get_teleram_group($publishGroupDataNotClicks);
					$this->send_message_telegram_group_process_no_of_groups_publish($telegramGroup,$campaign,$finalpublish);
				}
			}
		}
	}

	private function get_teleram_group($publishGroup){		
		
		$date = date('H:i:s');
		$telegramgroup = TelegramGroup::whereNotIn('id',$publishGroup)
		->whereHas('TelegrmGroupDay', function($query) use ($date) {
			$query->where('day','=',date('l'));
			$query->whereTime('start_time','<=',$date);
			$query->whereTime('end_time','>=',$date);
		})->with(['TelegrmGroupDay' => function($query) use ($date) {
			$query->where('day','=',date('l'));
			$query->whereTime('start_time','<=',$date);
			$query->whereTime('end_time','>=',$date);
		}])
		->where('status','1')
		->where('verify','1')
		->where('admin_status','1')
		->orderBy('no_of_published', 'ASC')
		->get();
		if(count($telegramgroup)>0){
			return $telegramgroup;
		}else{
			return TelegramGroup::whereHas('TelegrmGroupDay', function($query) use ($date) {
				$query->where('day','=',date('l'));
				$query->whereTime('start_time','<=',$date);
				$query->whereTime('end_time','>=',$date);
			})->with(['TelegrmGroupDay' => function($query) use ($date) {
				$query->where('day','=',date('l'));
				$query->whereTime('start_time','<=',$date);
				$query->whereTime('end_time','>=',$date);
			}])
			->where('status','1')
			->where('verify','1')
			->where('admin_status','1')
			->orderBy('no_of_published', 'ASC')
			->get();
		}
	}

	private function send_message_telegram_group_process_no_of_groups_publish($telegramGroup,$campaign,$finalpublish){
		$i = 0;
		foreach ($telegramGroup as $key => $value) {
			if($i == $finalpublish){ break; }
			$frequency_ads	= strtotime(date("Y-m-d H:i:s", strtotime('-'.$value->frequency_of_ads.' '.$value->frequency_type)));
			$publishtime = publish_time_from_publish_group($campaign->id,$value->id);
			if($frequency_ads >= $publishtime){
				$unique = time().$i;
				$this->send_message_on_telegram_group($campaign,$unique,$value);
				$i++;
			}
		}
	}

	private function send_message_telegram_group_process($telegramGroup,$campaign){
		
		$publiherids = array();
		if(!empty($campaign->tier_id) && $campaign->tier_id != 0)
			$tier = Tier::where('id',$campaign->tier_id)->first();			
			if(!empty($tier))
				$publiherids = (!empty($tier->publisher))?explode(",",str_replace("[","",str_replace("]","",$tier->publisher))):array();

		$i = 0;
		foreach ($telegramGroup as $key => $value) {
			if($i == $this->group_publish){ break; }
			$frequency_ads	= strtotime(date("Y-m-d H:i:s", strtotime('-'.$value->frequency_of_ads.' '.$value->frequency_type)));
			$publishtime = publish_time_from_publish_group($campaign->id,$value->id);
			if($frequency_ads >= $publishtime && (in_array($value->publisher_id, $publiherids) || count($publiherids) == 0)){
				$unique = time().$i;
				$this->send_message_on_telegram_group($campaign,$unique,$value);
				$i++;
			}
		}
	}

	private function send_message_on_telegram_group($campaign,$unique,$value){ 
		$campaignURL = $campaign->tracking_url;
		$advertiserId = $campaign->advertiser_id;
		$tierId = $campaign->tire_id;
		$campaignURL = $campaignURL.'/'.$value->publisher_id.'/'.$value->id.'/'.$unique;
		$message = [
			'image' => url('common/images/campaignUploads').'/'.$campaign->banner_image,
			'title' => $campaign->headline,
			'btntxt' => $campaign->button_text,
			'link' => $campaignURL,
			'description' => strip_tags($campaign->description)
		];
		$social_marketing = new SocialMarketing('telegram',
			$message,
			'send',
			$value->publisher_id,
			$advertiserId,
			$value->id,
			$campaign->id,
			$unique,
			'',
			'',
			'',
			'',
			$value->telegram_group
		);

		$response = $social_marketing->sendRequest();

      echo "<pre>";
      print_r($response);
      exit("in telegram push file...");

		if(isset($response["ok"]) && $response["ok"] == 1){

			Campaign::where('id', '=', $campaign->id)
			->update([
				'post_date' => Carbon::now()->toDateString(),
				'time'      => time(),
			]);

			TelegramGroup::where('id', '=', $value->id)
			->update([ 'no_of_published' => $value->no_of_published+1 ]);

			$campaignPublishGroup = new CampaignPublishGroup();
			$campaignPublishGroup->campaign_id = $campaign->id;
			$campaignPublishGroup->telegram_group_id = $value->id;
			$campaignPublishGroup->publish_date = date('Y-m-d');
			$campaignPublishGroup->publish_time = time();
			$campaignPublishGroup->clicks 		= 0;
			$campaignPublishGroup->unique_id = $unique;
			$campaignPublishGroup->save();
			if(!empty($tierId) && $tierId != 0)
				$this->tier_report($campaign->id,$value->publisher_id,$value->id,$tierId);
			$this->publisher_report($campaign->id,$value->publisher_id,$value->id);
			$this->campaign_report($campaign->id,$value->publisher_id,$value->id);
		}
	}


	private function tier_report($campaignid,$publisherid,$group_id,$tier_id){
		$tierReport = TierReport::where('tier_id', '=', $tier_id)
									->where('campaign_id', '=', $campaignid)
									->where('publisher_id', '=', $publisherid)
									->where('group_id', '=', $group_id)
									->where('created_at','>=',Carbon::today())
									->first();

		if (empty($tierReport)){
			$reportdata = new TierReport();
			$reportdata->tier_id   			= $tier_id;
			$reportdata->publisher_id   = $publisherid;
			$reportdata->campaign_id    = $campaignid;
			$reportdata->no_of_publish  = '1';
			$reportdata->group_id       = $group_id;
			$reportdata->save();
		} else {
			$report = $tierReport->no_of_publish;
			TierReport::where('tier_id', '=', $tier_id)
			->where('campaign_id', '=', $campaignid)
			->where('publisher_id', '=', $publisherid)
			->where('group_id', '=', $group_id)
			->where('created_at','>=',Carbon::today())
			->update(['no_of_publish' => $report + 1]);
		}
	}

	private function publisher_report($campaignid,$publisherid,$group_id){
		$users = PublisherReport::where('publisher_id', '=', $publisherid)
		->where('campaign_id', '=', $campaignid)
		->where('group_id', '=', $group_id)
		->where('created_at','>=',Carbon::today())
		->first();

		if (empty($users)){
			$reportdata = new PublisherReport();
			$reportdata->publisher_id   = $publisherid;
			$reportdata->campaign_id    = $campaignid;
			$reportdata->no_of_publish  = '1';
			$reportdata->group_id       = $group_id;
			$reportdata->save();
		} else {
			$report = $users->no_of_publish;
			PublisherReport::where('publisher_id', '=', $publisherid)
			->where('campaign_id', '=', $campaignid)
			->where('group_id', '=', $group_id)
			->where('created_at','>=',Carbon::today())
			->update(['no_of_publish' => $report + 1]);
		}
	}

	private function campaign_report($campaignid,$publisher_id,$telegramGroupId){
		$campaignReport = CampaignReport::where('campaign_id',$campaignid)->where('telegram_group_id',$telegramGroupId)->first();
		if(!empty($campaignReport)){
			CampaignReport::where('id', '=', $campaignReport->id)
			->update(['no_of_published' => $campaignReport->no_of_published + 1]);
		}else{
			$campaignReport = new CampaignReport();
			$campaignReport->campaign_id = $campaignid;
			$campaignReport->telegram_group_id = $telegramGroupId;
			$campaignReport->publisher_id = $publisher_id;
			$campaignReport->no_of_published = 1;
			$campaignReport->save();
		}
	}

	private function delete_message_form_telegram_group($publishGroupData){
		foreach ($publishGroupData as $key => $publishGroup) {
			$camapignrecords 		= Campaignmessage::where('unique_id',$publishGroup->unique_id)->first();
			if(!empty($camapignrecords)){
				$telegram_group_id 	= $camapignrecords->telegram_group_id;
				$group_id 					= $camapignrecords->id;
				$publisher_id 			= $camapignrecords->publisher_id;
				$campmeassage_id 		= $camapignrecords->campaigns_id;
				$messageID 					= $camapignrecords->message_id;

				$groupName 					= TelegramGroup::where('id',$telegram_group_id)->where('publisher_id',$publisher_id)->first();
				$telegramGroupName 	= $groupName->telegram_group;

				if($telegramGroupName){
					$social_marketing = new SocialMarketing('telegram',
						'',
						'delete',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						$telegramGroupName, // telegram group
						'',
						$messageID,//message id
						$group_id // telegram group id
					);
					$response = $social_marketing->sendRequest();

					if(isset($response["ok"]) && $response["ok"] == 1){
						CampaignPublishGroup::where('id', $publishGroup->id)->update(['clicks' => 1]);
					}else{
						continue;
					}
				}
			}
		}
	}
}
