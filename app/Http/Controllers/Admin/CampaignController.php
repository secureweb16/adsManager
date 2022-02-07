<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\Controller as Controller;
use App\Models\User;
use App\Models\Campaign;
use App\Models\TelegramGroup;
use App\Models\CampaignPublishGroup;
use App\Models\Notification;
use App\Models\CampaignReport;
use App\Models\PublisherReport;
use Hash;
use Auth;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;
use App\Notifications\CampaignStatusNotification as StatusNotification;



class CampaignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $campaigns = Campaign::all();
      return view('admin.campaign.list', compact('campaigns'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function campaign_approvel(Request $request){

      $details = array();
      $campaignURL = '';
      $details['status'] = 'Declined';
      $campaignID = $request->campin_id;
      $campaign = Campaign::find($campaignID);
      $campaign->admin_approval = $request->status;
      $details['advertiser_id'] = $campaign->advertiser_id;

      if($request->status == 1){
        $publishtime = time();
        $campaign->campaign_status = '1';
        $details['status'] = 'Approved';
      }

      $url  =   url('/advertiser/campaigns/edit').'/'.encrypt($campaignID);
      $notificationData = [
        'user_id'       => $campaign->advertiser_id,
        'type'          => 'camapign_status_change',
        'foruser'       => 'avertiser',
        'message'       => 'Campaign '.$details['status'],
        'admin_message' => $campaign->campaign_name.' has been '.$details['status'],
        'url'           => $url,
        'admin_url'     => '',
        'user_status'   => '0',
        'admin_status'  => '0'
      ];
      CreateNotificatons($notificationData);
      $campaign->save();
      $details['campaign_name'] = $campaign->campaign_name;
      // (new StatusNotification())->toMail($details);      
    }


    public function campaign_view(Request $request){
      $campaign = Campaign::find($request->campin_id);
      $returndata = array(
        'campaign_name'     => $campaign->campaign_name,
        'campaign_budget'   => $campaign->budget,
        'pay_ppc'           => $campaign->pay_ppc,
        'pay_daily'         => $campaign->pay_daily,
        'landing_url'       => $campaign->landing_url,
        'description'       => $campaign->description,
        'banner_image'      => URL::to('/common/images/campaignUploads/').'/'.$campaign->banner_image,
        'admin_approval'    => ($campaign->admin_approval == 0)?'Pending':(($campaign->admin_approval == 1)?'Approved':'Declined')
      );
      echo json_encode($returndata);
    }

    public function approved_list(){
      $campaigns = Campaign::where('admin_approval',"=",'1')->get();
      return view('admin.campaign.list', compact('campaigns'));
    }

    public function pending_list(){
      $campaigns = Campaign::where('admin_approval','=','0')->get();
      return view('admin.campaign.list', compact('campaigns'));
    }

  }
