<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PublisherPayment;
use App\Models\PublisherReport;
use App\Models\Tier;
use App\Models\TierReport;
use Auth;

class PublisherController extends Controller
{
	public function index()
	{	
		$userid = Auth::user()->id;

		if(isset($_GET['daterange']) && $_GET['daterange'] !='' && $_GET['daterange'] !='Select the date'){

			$feildVal = $_GET['daterange'];

			$filterReport = $this->get_data_filter_date($_GET['daterange'],$userid);

			$totalAmount = $filterReport['totalAmount'];

			$noOfClicks  = $filterReport['noOfClicks'];

			$noOfPublish = $filterReport['noOfPublish'];

		}else{

			$feildVal = 'Select the date';

			$totalAmount = PublisherReport::where('publisher_id',$userid)->sum('user_amount');

			$noOfClicks  = PublisherReport::where('publisher_id',$userid)->sum('no_of_clicks');

			$noOfPublish = PublisherReport::where('publisher_id',$userid)->sum('no_of_publish');

		}
		
		$averageCPC = ($noOfClicks != 0)?number_format($totalAmount/$noOfClicks,2):0;		
		
		return view('publisher.dashboard',compact('totalAmount','noOfClicks','averageCPC','noOfPublish','feildVal'));		

	}

	public function bot_setup_instruction(){

		return view('publisher.bot-setup');

	}


	private function get_data_filter_date($daterange,$userid){
		
		$publisherReport = $this->publisher_report($daterange,$userid);

		$tierReport = $this->tier_report($daterange,$userid);

		/*return array(
			'totalAmount' => $publisherReport['totalAmount']+$tierReport['totalAmount'],
			'noOfClicks' 	=> $publisherReport['noOfClicks']+$tierReport['noOfClicks'],
			'noOfPublish' => $publisherReport['noOfPublish']+$tierReport['noOfPublish'],
		);*/

		return array(
			'totalAmount' => $publisherReport['totalAmount'],
			'noOfClicks' 	=> $publisherReport['noOfClicks'],
			'noOfPublish' => $publisherReport['noOfPublish'],
		);

	}

	private function publisher_report($daterange,$userid){

		$dates = explode(" - ",$daterange);
		
		$fromDate = date('Y-m-d',strtotime($dates[0]));
		
		$toDate = date('Y-m-d',strtotime($dates[1]));
		
		$totalAmount = PublisherReport::where('publisher_id',$userid)
		->whereDate('created_at','>=',$fromDate)
		->whereDate('created_at','<=',$toDate)
		->sum('user_amount');

		$noOfClicks  = PublisherReport::where('publisher_id',$userid)
		->whereDate('created_at','>=',$fromDate)
		->whereDate('created_at','<=',$toDate)
		->sum('no_of_clicks');

		$noOfPublish = PublisherReport::where('publisher_id',$userid)
		->whereDate('created_at','>=',$fromDate)
		->whereDate('created_at','<=',$toDate)
		->sum('no_of_publish');

		return array(
			'totalAmount' => $totalAmount,
			'noOfClicks' 	=> $noOfClicks,
			'noOfPublish' => $noOfPublish,
		);

	}

	private function tier_report($daterange,$userid){
		
		$dates = explode(" - ",$daterange);
		
		$fromDate = date('Y-m-d',strtotime($dates[0]));
		
		$toDate = date('Y-m-d',strtotime($dates[1]));

		$totalAmount = TierReport::where('publisher_id',$userid)
		->whereDate('created_at','>=',$fromDate)
		->whereDate('created_at','<=',$toDate)
		->sum('user_amount');
		
		$noOfClicks  = TierReport::where('publisher_id',$userid)
		->whereDate('created_at','>=',$fromDate)
		->whereDate('created_at','<=',$toDate)
		->sum('no_of_clicks');
		
		$noOfPublish = TierReport::where('publisher_id',$userid)
		->whereDate('created_at','>=',$fromDate)
		->whereDate('created_at','<=',$toDate)
		->sum('no_of_publish');

		return array(
			'totalAmount' => $totalAmount,
			'noOfClicks' 	=> $noOfClicks,
			'noOfPublish' => $noOfPublish,
		);

	}

}
