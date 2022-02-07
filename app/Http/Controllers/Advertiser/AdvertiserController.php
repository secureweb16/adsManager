<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Campaign;
use App\Models\CampaignReport;
use App\Models\FundsDetails;
use App\Models\PublisherReport;
use Auth;
use Mail;
use App\Mail\SupportMail;
use Carbon\Carbon;


class AdvertiserController extends Controller
{
	public function index()
	{
		
		$userid =  Auth::user()->id;
		$campaign_active = Campaign::where('campaign_status', '=', '1')
			->where('admin_approval', '=', '1')->where('advertiser_id', '=', $userid)->count();
		$campaign_inactive= Campaign::where('admin_approval', '=', '0')->where('advertiser_id', '=', $userid)->count();
		$funds = FundsDetails::where('user_id', '=', $userid)->first();
		$fundsamount = (!empty($funds))?$funds->remaning_funds:0;
		$alldata = array(
			'campaign_active' => $campaign_active,
			'campaign_inactive' => $campaign_inactive,
			'remaning_funds' => $fundsamount,
		);
		   /*all data*/
        if(isset($_GET['daterange']) && $_GET['daterange'] !='' && $_GET['daterange'] !='Select the date to see the clicks'){
        	$feildVal = $_GET['daterange'];
            $daterange = $_GET['daterange'];
            $dates = explode(" - ",$daterange);            
            $fromDate = date('Y-m-d',strtotime($dates[0]));
            $toDate = date('Y-m-d',strtotime($dates[1]));            
			$noOfClicks = $this->get_camapign_clicks_onDate($userid,$fromDate,$toDate);
		}else{
			$feildVal = 'Select the date to see the clicks';
			$noOfClicks = $this->get_camapign_clicks($userid);
		}
		return view('advertiser.dashboard',compact('alldata','noOfClicks','feildVal'));
	}

	private function get_camapign_clicks($userid){
		$campaignID = Campaign::where('advertiser_id','=',$userid)->pluck('id');
		return CampaignReport::whereIn('campaign_id',$campaignID)->sum('no_of_clicks');
	}

	private function get_camapign_clicks_onDate($userid,$fromDate,$toDate){
		
		$campaignID = Campaign::where('advertiser_id','=',$userid)->pluck('id');		
        return PublisherReport::whereIn('campaign_id',$campaignID)
        ->whereDate('created_at','>=',$fromDate)
        ->whereDate('created_at','<=',$toDate)
        ->sum('no_of_clicks');
		
      
	}


}
