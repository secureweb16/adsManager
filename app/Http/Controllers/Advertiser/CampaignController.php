<?php //testing   
namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\CampaignTracking;
use App\Models\FundsDetails;
use App\Models\PublisherReport;
use App\Models\CampaignFund;
use App\Models\CampaignPublishGroup;
use App\Models\TelegramGroup;
use App\Models\Tier;
use Auth;
use DB;
use App\Notifications\CampaignCreateNotification as campaignNotification;

/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;


class CampaignController extends Controller
{ 

  public function index()
  {
    $advertiser_id = Auth::user()->id;  
    if(isset($_GET['daterange']) && $_GET['daterange'] !='' && $_GET['daterange'] !='Select the date'){
      $feildVal = $_GET['daterange'];
      $daterange = $_GET['daterange'];
      $dates = explode(" - ",$daterange);
      $fromDate = date('Y-m-d',strtotime($dates[0]));
      $toDate = date('Y-m-d',strtotime($dates[1]));

      $data = array(
        'fromDate' => $fromDate,
        'toDate' => $toDate,
      );

      $campaigns = Campaign::orderBy('id','desc')->where('advertiser_id',$advertiser_id)    
      ->withSum(['get_campaign_report' => function($query) use ($data) {
        $query->whereDate('created_at','>=',$data['fromDate']);
        $query->whereDate('created_at','<=',$data['toDate']);
      }],'no_of_clicks')
      ->withSum(['get_campaign_report' => function($query) use ($data) {
        $query->whereDate('created_at','>=',$data['fromDate']);
        $query->whereDate('created_at','<=',$data['toDate']);
      }],'total_amount')   
      ->get();

    }else{    
      $feildVal = "Select the Date";         
      $campaigns = Campaign::orderBy('id','desc')->where('advertiser_id',$advertiser_id)
      ->withSum('get_campaign_report','no_of_clicks')
      ->withSum('get_campaign_report','total_amount') 
      ->get();
    }
    return view('advertiser.campaign.list', compact('campaigns','feildVal'));
  }

  public function add_campaign_view()
  {
    $alltier = Tier::get();
    return view('advertiser.campaign.create',compact('alltier'));
  }  

  public function add_campaign(Request $request)
  {

    $data = $request->get('campaign_tire');
    $mincpc = ($data == 0)?get_option_value('average_min_CPC_bid'):$this->tire_cpc_controller($request->get('campaign_tire'));

    $request->validate([
      'campaign_name'   => 'required',
      'campaign_type'   => 'required',
      'headline'        => 'required',
      'button_text'     => 'required',
      'campaign_budget' => 'required|numeric|between:0,9999.9999',
      'pay_ppc'         => 'required|numeric|between:0,9999.9999|min:'.$mincpc,
      'landing_url'     => 'required|url',
      'description'     => 'nullable|min:4|max:1024',
      'banner_image'    => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ],
    [
      'pay_ppc.required' => 'The CPC must be at least '.$mincpc,
    ]);

    $advertiser_id    = Auth::user()->id;

    $image = $request->file('banner_image');
    $imageName = time().'.'.$request->banner_image->extension(); 
    $destinationPath  = base_path('public/common/images/campaignUploads');
    
    $fundsAmount = $request->get('campaign_budget'); 
    $chekFunds = FundsDetails::where('user_id',$advertiser_id)->where('remaning_funds','>=',$fundsAmount)->first();    
    $campaign_budget = (!empty($chekFunds))?$request->get('campaign_budget'):0;

    if($campaign_budget != 0){

      $image->move($destinationPath, $imageName);

      $traking_id = $this->generateRandomString();
      $utmf = time();

      $payDaily = ($campaign_budget >= $request->get('pay_daily'))?$request->get('pay_daily'):$campaign_budget;

      $campaign   = new  Campaign();
      $campaign->headline         = $request->get('headline');
      $campaign->tire_id          = $request->get('campaign_tire');
      $campaign->campaign_name    = $request->get('campaign_name');
      $campaign->campaign_type    = implode(',',$request->get('campaign_type'));
      $campaign->advertiser_id    = $advertiser_id;
      $campaign->campaign_budget  = $campaign_budget;
      $campaign->pay_ppc          = $request->get('pay_ppc');
      $campaign->pay_daily        = $payDaily;
      $campaign->remaing_total    = $campaign_budget-$payDaily;
      $campaign->remaing_daily    = $request->get('pay_daily');
      $campaign->landing_url      = $request->get('landing_url');
      $campaign->tracking_url     = url('/telegram').'/'.$traking_id.'/'.$utmf;
      $campaign->description      = $request->get('description');
      $campaign->banner_image     = $imageName;
      $campaign->button_text      = $request->get('button_text');      
      $campaign->admin_approval   = '0';
      $campaign->campaign_status  = '0';
      $campaign->save();

      $this->campaign_tracking($campaign->id,$request->get('landing_url'),$traking_id,$utmf);

      $details = [
        'name' => Auth::user()->first_name.' '.Auth::user()->last_name,
        'campaign_name' => $request->get('campaign_name'),
        'campaign_url' => url('/admin/campaigns'),
      ];
      $type = 'campaign_create';
      $msg  = 'Campaign Created successfully';
      $admsg = Auth::user()->first_name.' '.Auth::user()->last_name.' create a new campaign';
      $url  =   url('/advertiser/campaigns/edit').'/'.encrypt($campaign->id);
      $this->create_notification($type,$msg,$url,$admsg);

      // (new campaignNotification())->toMail($details);

      if(!empty($chekFunds)){
        $this->campaign_funds($campaign->id,$campaign_budget);
        $remaningfunds = $chekFunds->remaning_funds-$campaign_budget;
        FundsDetails::where('user_id', $advertiser_id)->update(['spent_funds' => $campaign_budget,'remaning_funds'=>$remaningfunds]);
        return redirect()->route('advertiser.campaigns.edit',encrypt($campaign->id))->with('message', 'Campaign created!');
      }      
    }
    return redirect()->route('advertiser.campiagns')->with('error','Campaign is not created due to funds insufficient!');
  }

  private function campaign_tracking($campaignid,$landing_url,$traking_id,$utmf){

    $campaignTracking = new CampaignTracking();
    $campaignTracking->campaign_id = $campaignid;
    $campaignTracking->traking_id  = $traking_id;
    $campaignTracking->utmf        = $utmf;
    $campaignTracking->landing_url = $landing_url;
    $campaignTracking->save();
  }

  private function campaign_funds($campaignid,$campaign_budget){
    $campaignFund = new CampaignFund();
    $campaignFund->campaign_id    = $campaignid;
    $campaignFund->funds_amount   = $campaign_budget;      
    $campaignFund->funds_status   = 'Credit';
    $campaignFund->save();
  }

  public function generateRandomString($length = 50) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }

  public function list(){
    $campaigns = Campaign::all();
    return view('advertiser.campaign.list', compact('campaigns'));
  }

  public function edit($id){
    $id = decrypt($id);
    $campaigns = Campaign::findOrFail($id);
    $alltier = Tier::get();
    return view('advertiser.campaign.edit', compact('campaigns','alltier'));
  }

  public function update_campaign(Request $request)
  {

    $data =$request->get('campaign_tire');
    $mincpc = ($data == 0)?get_option_value('average_min_CPC_bid'):$this->tire_cpc_controller($request->get('campaign_tire'));

    $request->validate([
      'campaign_name'   => 'required',
      'campaign_type'   => 'required',
      'headline'        => 'required',
      'button_text'     => 'required',
      'campaign_budget' => 'numeric|between:0,9999.9999',
      'pay_ppc'         => 'required|numeric|between:0,9999.9999|min:'.$mincpc,
      'landing_url'     => 'required|url',
      'description'     => 'nullable|min:4|max:1024',
      'banner_image'    => 'required_if:campaign_banner,==,""|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ],
    [
      'pay_ppc'         => 'The CPC must be at least '.$mincpc,
    ]
  );

    $image = $request->file('banner_image');
    $advertiser_id    = Auth::user()->id;
    if(!empty($image)){
      $imageName        = time().'.'.$request->banner_image->extension();
      $destinationPath  = base_path('public/common/images/campaignUploads');
      $image->move($destinationPath, $imageName);
      $this->remove_image($request->get('campaign_banner'));
    }else{
      $imageName = $request->get('campaign_banner');
    }

    $campaignId = $request->get('campaignId');
    
    $campaign   = Campaign::find($campaignId);
    $payDaily = ($campaign->campaign_budget >= $request->get('pay_daily'))?$request->get('pay_daily'):$campaign->campaign_budget;

    $campaign->headline         = $request->get('headline');       
    $campaign->tire_id          = $request->get('campaign_tire');
    $campaign->campaign_name    = $request->get('campaign_name');
    $campaign->campaign_type    = implode(',',$request->get('campaign_type'));
    $campaign->pay_ppc          = $request->get('pay_ppc');
    $campaign->pay_daily        = $payDaily;
    $campaign->remaing_total    = $campaign->remaing_total-($payDaily-$campaign->pay_daily);
    $campaign->landing_url      = $request->get('landing_url');
    $campaign->description      = $request->get('description');
    $campaign->banner_image     = $imageName;
    $campaign->button_text      = $request->get('button_text');    
    $campaign->admin_approval   = '0';
    $campaign->save();
    
    $type = 'campaign_update';
    $msg  = 'Campaign Update successfully';
    $admsg = Auth::user()->first_name.' '.Auth::user()->last_name.' update a campaign';
    $url  =   url('/advertiser/campaigns/edit').'/'.encrypt($campaignId);
    $this->create_notification($type,$msg,$url,$admsg);    
    return redirect()->back()->with('message', 'Campaign Update!');
    // return redirect()->route('advertiser.campiagns')->with('message', 'Campaign Update!');    
  }

  public function delete($id){
    $id = decrypt($id);
    $publishcampaign = CampaignPublishGroup::where('clicks',0)->where('campaign_id',$id)->get();
    $this->delete_message_on_telegram($publishcampaign);
    $campaigns = Campaign::findOrFail($id);
    $campaigns->delete();
    return redirect()->route('advertiser.campiagns')->with('message', 'Campaign Deleted!');
  }

  private function delete_message_on_telegram($publishcampaign){
    foreach ($publishcampaign as $key => $publishGroup) {              
      $camapignrecords   = Campaignmessage::where('unique_id',$publishGroup->unique_id)->first();
      $telegram_group_id = $camapignrecords->telegram_group_id;
      $group_id          = $camapignrecords->id;
      $publisher_id      = $camapignrecords->publisher_id;
      $campmeassage_id   = $camapignrecords->campaigns_id;
      $messageID         = $camapignrecords->message_id;
      $groupName         = TelegramGroup::where('id',$telegram_group_id)->where('publisher_id',$publisher_id)->first();
      $telegramGroupName = $groupName->telegram_group;

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

  public function remove_image($image_name){
    $destinationPath  = base_path('public/common/images/campaignUploads');
    $unlinkImage = $destinationPath."/".$image_name;
    if(file_exists($unlinkImage)){ unlink($unlinkImage); }
  }

  public function campaign_status_update(Request $request){
    $campaign = Campaign::find($request->campin_id); 
    $campaign->campaign_status = $request->status;
    $campaign->save();
  }

  public function add_campaign_funds_view(){
    $advertiser_id = Auth::user()->id;
    $campaigns = Campaign::orderBy('id','desc')->where('advertiser_id',$advertiser_id)->get();
    return view('advertiser.campaignfunds.create',compact('campaigns'));
  }

  public function add_campaign_funds(Request $request) {
    $request->validate([
      'campaign_id'   => 'required',
      'funds_amount' => 'required|numeric|between:0,9999.9999'
    ]);

    $campaignId = $request->get('campaign_id');
    $fundsAmount = $request->get('funds_amount');
    $advertiser_id = Auth::user()->id;
    $chekFunds = FundsDetails::where('user_id',$advertiser_id)->where('remaning_funds','>=',$fundsAmount)->first();

    if(!empty($chekFunds)){
      $campaignFund = new CampaignFund();
      $campaignFund->campaign_id    = $campaignId;
      $campaignFund->funds_amount   = $fundsAmount;
      $campaignFund->funds_status   = 'Credit';
      $campaignFund->save();
      $remaningfunds = $chekFunds->remaning_funds-$fundsAmount;
      FundsDetails::where('user_id', $advertiser_id)->update(['spent_funds' => $fundsAmount,'remaning_funds'=>$remaningfunds]);
      return redirect()->route('advertiser.campiagns')->with('message', 'Funds added in campaign!');
    }else{
      return redirect()->route('advertiser.campaigns.funds.add')->with('message', 'Funds insfficient!');
    }
  }

  public function trash_campaign(){
    $advertiser_id = Auth::user()->id;
    $campaigns = Campaign::where('advertiser_id',$advertiser_id)->onlyTrashed()->get();
    return view('advertiser.campaign.trash', compact('campaigns'));
  }

  public function restore_campaign($id){
    $campaign = Campaign::onlyTrashed()->find(decrypt($id));
    $campaign->restore();
    return redirect()->route('advertiser.campaigns.trash')->with('message', 'Campaign restore successfully!');
  }

  public function delete_campaign_permanent($id){
    $id = decrypt($id);
    $campaignTrashed = Campaign::onlyTrashed()->find($id);
    $amount = $campaignTrashed->remaing_total+$campaignTrashed->remaing_daily;
    $advertiser_id    = Auth::user()->id;
    $chekFunds = FundsDetails::where('user_id',$advertiser_id)->first();
    $spentfunds = $chekFunds->spent_funds-$amount;
    $remaning   = $chekFunds->remaning_funds+$amount;
    FundsDetails::where('user_id', $advertiser_id)->update([
      'spent_funds' => $spentfunds,
      'remaning_funds'=>$remaning
    ]);
    $this->remove_image($campaignTrashed->banner_image);
    Campaign::onlyTrashed()->find($id)->forceDelete();
    return redirect()->route('advertiser.campaigns.trash')->with('error', 'Campaign Delete Permanently!');
  }

  private function create_notification($type,$message,$url,$admsg){
    $notificationData = [
      'user_id'       => Auth::user()->id,
      'type'          => $type,
      'foruser'       => 'avertiser',
      'message'       => $message,
      'admin_message' => $admsg,
      'url'           => $url,
      'admin_url'     => '',
      'user_status'   => '0',
      'admin_status'  => '0'
    ];
    CreateNotificatons($notificationData);
  }

  public function remove_anchar_tag(){
    echo preg_replace("/<\/?a( [^>]*)?>/i", "", $_REQUEST['editorContent']);
  }

  public function tire_cpc(Request $request){
    $tier = Tier::findOrFail($request->get('tireid'));
    return $tier->minimun_cpc;
  }

  public function tire_cpc_controller($id){
    $tier = Tier::findOrFail($id);
    return $tier->minimun_cpc;
  }

}
?>