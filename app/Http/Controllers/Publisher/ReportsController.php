<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublisherReport;
use App\Models\TelegramGroup;
use Auth;
use stdClass;
use Illuminate\Support\Facades\DB;
class ReportsController extends Controller
{
  private $no_of_publish;
  private $no_of_clicks;
  private $group_revenue;

  public function __construct(){
    $this->no_of_publish  = 0;
    $this->no_of_clicks   = 0;
    $this->group_revenue  = 0;    
  }

  public function index()
  {
    $userid =  Auth::user()->id;
    $publisherReport = PublisherReport::where('publisher_id',$userid)->get()->groupBy('group_id');
    $finalReport = array();
    
    foreach ($publisherReport as $telegram_group_id => $reports) {
      $i = 1;
      $this->no_of_publish = $this->no_of_clicks = $this->group_revenue = 0;
      foreach ($reports as $value) {
        $this->no_of_publish += $value->no_of_publish;
        $this->no_of_clicks += $value->no_of_clicks;
        $this->group_revenue += $value->user_amount;
        $i++;
      }
      $finalReport[] =array(
        'telegram_group_name' => telegram_group_name($telegram_group_id),
        'no_of_publish' => $this->no_of_publish,
        'no_of_clicks'  => $this->no_of_clicks,
        'group_revenue' => $this->group_revenue,
      );
    }    
    return view('publisher.reports.index',compact('finalReport'));
  }

  public function reports_data(Request $request)
  {
    $userid =  Auth::user()->id;
    $from_date = $request->from_date;
    $to_date   = $request->to_date;
    $publisherreport = PublisherReport::where('publisher_id',$userid)
    ->whereDate('created_at','>=',$from_date)
    ->whereDate('created_at','<=',$to_date)
    ->get()->groupBy('group_id');

    $finalReport = array();    

    foreach ($publisherreport as $telegram_group_id => $publisherReposrt) {
      $i = 1;
      $this->no_of_publish = $this->no_of_clicks = $this->group_revenue = 0;
      foreach ($publisherReposrt as $value) {
        $this->no_of_publish += $value->no_of_publish;
        $this->no_of_clicks += $value->no_of_clicks;
        $this->group_revenue += $value->user_amount;
        $i++;
      }

      $finalReport[] =array(
        'telegram_group_name' => telegram_group_name($telegram_group_id),
        'no_of_publish' => $this->no_of_publish,
        'no_of_clicks'  => $this->no_of_clicks,
        'group_revenue' => $this->group_revenue,
      );
    }
    return view('publisher.reports.index',compact('finalReport','from_date','to_date'));
  } 
}
