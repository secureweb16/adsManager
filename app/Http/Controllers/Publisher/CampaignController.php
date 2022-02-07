<?php
namespace App\Http\Controllers\Publisher;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublisherCampaignAssign;
use Auth;
class CampaignController extends Controller
{
  public function index(){
     $publisherID =  Auth::user()->id;
     $publisherCampaigns = PublisherCampaignAssign::where('publisher_id',$publisherID)->with('getPublisherCampaign')->get();
     
	  	return view('publisher.campaign.index',compact('publisherCampaigns'));
	  }
}
?>