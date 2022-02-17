<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Notification;
use App\Models\PublisherReport;
use Hash;
use Auth;

class AdminController extends Controller
{
  public function index(){

    $campaign_active= Campaign::where('campaign_status', '=', '1')->count();

    $campaign_inactive= Campaign::where('campaign_status', '=', '0')->count();

    $publisher_active= User::where('user_role', '=','2')->where('user_status', '=', '1')->count();

    $advertiser_active= User::where('user_role', '=','3')->where('user_status', '=', '1')->count();
    
    if(isset($_GET['daterange']) && $_GET['daterange'] !='' && $_GET['daterange'] !='Select the date'){

      $feildVal = $_GET['daterange'];

      $publisherData = $this->publisher_report_date_filter($_GET['daterange']);

      $total_revenue = $publisherData['total_revenue'];

      $total_clciks = $publisherData['total_revenue'];

    }else{

      $feildVal = 'Select the date';

      $total_revenue = PublisherReport::sum('total_amount');

      $total_clciks  = PublisherReport::sum('no_of_clicks');

    }

    $token_reflection = ((4*$total_revenue)/100);

    $NFT_reflection = ((1*$total_revenue)/100);

    $avg_cpc = ($total_clciks > 0)?@$total_revenue/@$total_clciks:0;
    
    $alldata = array(
      'campaign_active' => $campaign_active,
      'campaign_inactive' => $campaign_inactive,
      'advertiser_active' => $advertiser_active,
      'publisher_active' => $publisher_active,
      'total_revenue' => number_format($total_revenue, 2),
      'clicks' => $total_clciks,
      'token_reflection' => number_format($token_reflection, 2),
      'NFT_reflection' => number_format($NFT_reflection, 2),
      'avg_cpc' => number_format($avg_cpc, 2),
      'feildVal' => $feildVal,
    );

    return view('admin.dashboard',compact('alldata'));

  }

  private function publisher_report_date_filter($daterange){

    $dates = explode(" - ",$daterange);

    $fromDate = date('Y-m-d',strtotime($dates[0]));

    $toDate = date('Y-m-d',strtotime($dates[1]));

    $total_revenue = PublisherReport::whereDate('created_at','>=',$fromDate)
    ->whereDate('created_at','<=',$toDate)
    ->sum('total_amount');

    $total_clciks  = PublisherReport::whereDate('created_at','>=',$fromDate)
    ->whereDate('created_at','<=',$toDate)
    ->sum('no_of_clicks');

    return array(
      'total_revenue' => $total_revenue,
      'total_clciks'  => $total_clciks,
    );

  }

}
