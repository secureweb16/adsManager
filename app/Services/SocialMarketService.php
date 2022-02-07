<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\TelegramGroup;
use Carbon\Carbon;
use App\Models\CampaignReport;
use App\Models\CampaignPublishGroup;

/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;

class SocialMarketService{

	protected $campaign;

	public function __construct(){

	}

	/*****************
	**
	Campaign Return
	**
	***********/
	public function fetchCamapign(){

		$minmum_cpc = get_option_value('average_min_CPC_bid');

		$remaing = ($minmum_cpc != '')?$minmum_cpc:0;

		$CampaignPPC = Campaign::where('campaign_status','1')->where('admin_approval','1')
		->where('remaing_daily','>',0)
		->orderBy('pay_ppc', 'DESC')
		->limit(2)
		->get();

		$averageCost = (isset($CampaignPPC[1]))?$CampaignPPC[1]->pay_ppc:$CampaignPPC[0]->pay_ppc;
		$averageCost = $averageCost+0.01;
		update_option_value('average_cost_value',$averageCost);

		// CampaignPublishGroup::
		
		return Campaign::where('campaign_status','1')->where('admin_approval','1')
		->where('remaing_daily','>',$averageCost)
		->where('post_date','<',Carbon::now()->toDateString())
		->orWhere('post_date', '=', null)
		->get();
	}

	/*****************
	**
	Telegram Group
	**
	***********/

	public function fetchPublisherTelegramGroup(){	
		return TelegramGroup::where('status','1')->orderBy('no_of_published', 'ASC')->limit(2)->get();
	}

	public function postTheDataOnTelegrm($allCampaign,$telegramGroup){

		foreach ($allCampaign as $campaign) {
			$publishtime = time();
			$campaignID = $campaign->id;
			foreach ($telegramGroup as $key => $value) {
				$unique = time();
				$campaignURL = $campaign->tracking_url.'/'.$value->publisher_id.'/'.$value->id.'/'.$unique;
				$social_marketing = new SocialMarketing('telegram',
					$campaignURL,
					'send',
					$value->publisher_id,
					$campaign->advertiser_id,
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

				if(isset($response["ok"]) && $response["ok"] == 1){
					Campaign::where('id', '=', $campaignID)
					->update([
						'post_date' => Carbon::now()->toDateString(),
						'time'      => time(),
					]);

					$campaignPublishGroup = new CampaignPublishGroup();
					$campaignPublishGroup->campaign_id = $campaignID;
					$campaignPublishGroup->telegram_group_id = $value->id;
					$campaignPublishGroup->publish_date = date('Y-m-d');
					$campaignPublishGroup->publish_time = $publishtime;
					$campaignPublishGroup->clicks = 0;
					$campaignPublishGroup->save();

					TelegramGroup::where('id', '=', $value->id)
					->update([ 'no_of_published' => $value->no_of_published+1 ]);
					$campaignReport = CampaignReport::where('campaign_id',$campaign->id)->where('telegram_group_id',$value->id)->first();
					if(!empty($campaignReport)){
						CampaignReport::where('id', '=', $campaignReport->id)           
						->update(['no_of_published' => $campaignReport->no_of_published + 1]);
					}else{
						$campaignReport = new CampaignReport();
						$campaignReport->campaign_id = $campaign->id;
						$campaignReport->telegram_group_id = $value->id;
						$campaignReport->publisher_id = $value->publisher_id;
						$campaignReport->no_of_published = 1;
						$campaignReport->save();
					}
				}
			}
		}
	}


	/*****************
	**
	Fetch new Telegram Group To post
	**
	***********/

	public function fetchNewCamapignToPost(){
	/*$this->deleteMidnight();
	$this->dailyBudgetUpdate();*/
	  $currentTime =Carbon::now();	
	  $allgroups = CampaignPublishGroup::all();	
	  if(empty($allgroups->toArray())){
		  	$campaign = Campaign::select('id')->where('admin_approval','=','1')->where('campaign_status','=','1')->get();
		  	$telegramgroup = $this->fetchPublisherTelegramGroup();
		  	foreach ($campaign as $key => $value) {
		  		$this->postNewDataOnTelegrm($value->id,$telegramgroup);
		  	}
		  	exit();
	  }else{
	  
	  	$i=0;
	  	$publishTimeNew='';
	    foreach($allgroups as $key=>$publishGroup){
	    	$adsFrequency = TelegramGroup::where('id',$publishGroup->telegram_group_id)->get();
	    	$freuqenceyOfAds = $adsFrequency[0]->frequency_of_ads;
	    	$created_time	 = $publishGroup->created_at->toDateTimeString();
	    	$currenttime = strtotime($currentTime);
	    	$publishTime = strtotime($created_time);
	    	$totalSecondsDiff = abs($currenttime-$publishTime);
	    	$totalHoursDiff   = $totalSecondsDiff/60/60;
	    	$publishTimeold =$publishGroup->publish_time;

	    	/*if($publishTimeold == $publishTimeNew){
	    		$publishGroupCampign[$publishGroup->campaign_id]['publishtime'][$publishTimeold][] = $publishGroup->telegram_group_id;
	    	}*/	

	        if($publishGroup->clicks == 0 ){
	        	$publishGroupCampign[$publishGroup->campaign_id]['unique'][] = $publishGroup->unique_id;		
	        }

	        if(($totalHoursDiff > $freuqenceyOfAds) && ($totalHoursDiff > 2) && ($publishGroup->clicks == 0)){
	    		$publishGroupCampign[$publishGroup->campaign_id]['uniquetwohours'][] = $publishGroup->unique_id;	
	        }


	        if($totalHoursDiff < $freuqenceyOfAds){
	    		$publishGroupCampign[$publishGroup->campaign_id]['group'][] = $publishGroup->telegram_group_id;	
	        }

	    	if($publishGroup->clicks == 1 ){
	    		$publishGroupCampign[$publishGroup->campaign_id]['clicks'][] = $publishGroup->clicks;
	    	}	
	    	if($totalHoursDiff < $freuqenceyOfAds){
	    			$publishGroupCampign[$publishGroup->campaign_id]['created_at'][] = $publishGroup->created_at->toDateTimeString();	
	    	}
	    		$publishTimeNew =$publishGroup->publish_time;
	    	$i++;		
	    } 
         $k=0;
         echo "<pre>";
         print_r($publishGroupCampign);
         //exit;
	    foreach ($publishGroupCampign as $key => $data) {
	    	$totalcount = 0;
	    	$totalcount1 = 0;
	    	    	////   If all groups are empty ///
	    	if(!isset($data['group']) && empty($data['group'])){
	    		echo "I AM HOURS LATE ";
	    		echo "here";
	    		if(isset($data['unique'])){
		    		foreach($data['unique'] as $unique){
		    	   	 $this->deleteCampaignRecord($unique);
		    	    }
	    		}
	    		$groupPublished = array();
                $telegram = $this->fetchPublisherTelegramGroup($groupPublished);	
                 echo "<pre>";
                 print_r($telegram->toArray());
	    	    $this->postNewDataOnTelegrm($key,$telegram);
	    	  
	    	}

	    	if(@$data['clicks']){
	    		echo $totalcount = count($data['clicks']);
	    	}
	    	if(@$data['group']){
	    		echo $totalcount1 = count($data['group']);
	    	}
	    	$groupPublished = @$data['group'];

	    	$currentTime =Carbon::now();
	    	$totalHoursDiff = 0;
	        $d2 = strtotime($currentTime);
	    	if(isset($data['created_at']) && (!empty($data['created_at']))){
	    	    $d1 = strtotime(end($data['created_at']));
	    		$totalSecondsDiff = abs($d2-$d1);
	    		$totalHoursDiff   = $totalSecondsDiff/60/60;
	        }
	        //echo $totalHoursDiff;
	    	//// when all groups clicked //
            
	        if(isset($data['clicks']) && isset($data['group']) && $totalHoursDiff < 2  && ($totalcount1 <= $totalcount)){
	        	echo "clicks counrt";
	    			//go for new group//
	        	if(isset($data['uniquetwohours'])){
	        		foreach($data['uniquetwohours'] as $unique){
	    	   	       $this->deleteCampaignRecord($unique);
	    	       }
	        	}
	    		$telegram = $this->newGroupAllocation($groupPublished);	
	    		$this->postNewDataOnTelegrm($key,$telegram);
	    	}
	    	/////// when 2hours are exausted ////
	    
	    	if((isset($data['group'])) && (!empty($data['group'])) && $totalHoursDiff > 2 && isset($data['unique']) && (!empty($data['unique'])) ){
	    	  // DELETE THE CAMPAIGN AND CREATE THE NEW ONE
	    		echo "new clicks";
	    	   foreach($data['unique'] as $unique){
	    	   	 $this->deleteCampaignRecord($unique);
	    	   }
	    		//exit;
	    	$telegram = $this->newGroupAllocation($groupPublished);
	    	$this->postNewDataOnTelegrm($key,$telegram);

	    	}	
	    	$k++;

	    }	
      }
}

/* delete at the 12:00 AM */
   	public function deleteMidnight(){
   	  $deleteCampaign = CampaignPublishGroup::select('unique_id')->where('clicks','=',0)->get();
      foreach($deleteCampaign as $key=>$val){
        $this->deleteCampaignRecord($val->unique_id);
      }
   	  $allDataEmpty =CampaignPublishGroup::truncate();
   	  echo "all data truncated";
   	}
/* update the daily budget at midnight */
 	public function dailyBudgetUpdate(){
 	   $updateCampaign = Campaign::where('admin_approval','=','1')->get();
 	   foreach($updateCampaign as $updateval){
          $updatedCampignData = Campaign::find($updateval->id);
          $remainingBudget = $updatedCampignData->remaing_total;
          $dailpay = $updatedCampignData->pay_daily;

          if($remainingBudget > $dailpay){
              $updatedCampignData->remaing_daily = $dailpay;
              $updatedCampignData->remaing_total = $remainingBudget - $dailpay;
          }else{
          	  $updatedCampignData->remaing_daily = $remainingBudget;	
              $updatedCampignData->remaing_total = 	0;
          }	
          $updatedCampignData->save();
 	   }
 	}
  


   	private function deleteCampaignRecord($uniqueid){
		$camapignrecords 		= Campaignmessage::where('unique_id',$uniqueid)->first();		
		$telegram_group_id 	    = $camapignrecords->telegram_group_id;
		$group_id 			    = $camapignrecords->id;
		$publisher_id 			= $camapignrecords->publisher_id;
		$campmeassage_id 		= $camapignrecords->campaigns_id;
		$messageID 				= $camapignrecords->message_id;
		$groupName 				= TelegramGroup::where('id',$telegram_group_id)->where('publisher_id',$publisher_id)->first();
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
		    $camapignrecords = CampaignPublishGroup::where('unique_id',$uniqueid)->first();
			if(isset($camapignrecords)){
				$camapignrecords->clicks = 1;
				$camapignrecords->save();	
			
		    }
	    }
	}

	public function newGroupAllocation($groupPublished){
        return TelegramGroup::where('status','1')
        						    ->whereNotIn('id', $groupPublished)
        							  ->orderBy('no_of_published', 'ASC')	
        							  ->limit(2)
        							  ->get();
	}

	public function postNewDataOnTelegrm($allCampaign,$telegramGroup){
      $campaign = Campaign::find($allCampaign);
      $average_click_cost = get_option_value('average_cost_value');
      if($average_click_cost < $campaign->remaing_daily){
     	 echo $allCampaign;
     	 echo "<br>";
      	 echo "Group:";
		  //foreach ($allCampaign as $campaign) {
			$publishtime = time();
			echo $campaignID = $campaign->id;
			echo "Campign_id";
			echo "<br>";
			$i=0;
			foreach ($telegramGroup as $key => $value) {
				echo $value->id;
				$unique = time().$i;
				$campaignURL = $campaign->tracking_url.'/'.$value->publisher_id.'/'.$value->id.'/'.$unique;
				$social_marketing = new SocialMarketing('telegram',
					$campaignURL,
					'send',
					$value->publisher_id,
					$campaign->advertiser_id,
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
                echo "resposne";
                echo "<pre>";
                print_r($response);
				if(isset($response["ok"]) && $response["ok"] == 1){
					Campaign::where('id', '=', $campaignID)
					->update([
						'post_date' => Carbon::now()->toDateString(),
						'time'      => time(),
					]);
					$campaignPublishGroup = new CampaignPublishGroup();
					$campaignPublishGroup->campaign_id = $campaignID;
					$campaignPublishGroup->telegram_group_id = $value->id;
					$campaignPublishGroup->publish_date = date('Y-m-d');
					$campaignPublishGroup->publish_time = $publishtime;
					$campaignPublishGroup->unique_id = $unique;
					$campaignPublishGroup->clicks = 0;
					$campaignPublishGroup->save();

					TelegramGroup::where('id', '=', $value->id)
					->update([ 'no_of_published' => $value->no_of_published+1 ]);
					$campaignReport = CampaignReport::where('campaign_id',$campaign->id)->where('telegram_group_id',$value->id)->first();
					if(!empty($campaignReport)){
						CampaignReport::where('id', '=', $campaignReport->id)           
						->update(['no_of_published' => $campaignReport->no_of_published + 1]);
					}else{
						$campaignReport = new CampaignReport();
						$campaignReport->campaign_id = $campaign->id;
						$campaignReport->telegram_group_id = $value->id;
						$campaignReport->publisher_id = $value->publisher_id;
						$campaignReport->no_of_published = 1;
						$campaignReport->save();
					}
				}
				$i++;
			}
		}
	}
}