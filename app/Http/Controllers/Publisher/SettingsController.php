<?php

namespace App\Http\Controllers\Publisher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PublisherAccount;
use App\Models\PublisherAccountLog;
use App\Models\TelegramGroup;
use App\Models\TelegramTiming;
use App\Models\PublisherAccountRecord;
use Auth;
/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
      // dd(env('COINPAYMENT_PUBLIC_KEY'));
      // $checkwallletaddress = $this->check_coin_wallet_address('0x873D4AC32a42C9bDd5AED11D6985295b1B511BDC');
      // $response = json_decode($checkwallletaddress);
      // echo "<pre>";
      // print_r($response);
      // exit;
      return view('publisher.settings.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
      // return view('publisher.settings.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
       $request->validate([
        'wallet_address' => 'required',
      ]);
      $user_id = Auth::user()->id;
      $account = PublisherAccount::where('user_id',$user_id)->first();
     
        if(empty($account)){
          $settingdata= new PublisherAccount();
          $settingdata->user_id = $user_id;
          $settingdata->wallet_address = $request->get('wallet_address');
          $settingdata->verify ='0';
          $settingdata->save();
          $publisheraccountrecord = new PublisherAccountRecord();
          $publisheraccountrecord->user_id = $user_id;
          $publisheraccountrecord->account_id = $settingdata->id;
          $publisheraccountrecord->wallet_address = $request->get('wallet_address');
          $publisheraccountrecord->verify_times = 1;
          $publisheraccountrecord->verify = '0';
          $publisheraccountrecord->save();
        }else{

          $accountRecord = PublisherAccountRecord::where('account_id',$account->id)->where('user_id',$user_id)->count();          
          $wallet = PublisherAccountRecord::where('account_id',$account->id)->where('user_id',$user_id)
          ->where('wallet_address',$request->get('wallet_address'))->first();
          if(empty($wallet)){
            $publisheraccountrecord = new PublisherAccountRecord();
            $publisheraccountrecord->account_id = $account->id;
            $publisheraccountrecord->wallet_address = $request->get('wallet_address');
            $publisheraccountrecord->verify_times = $accountRecord+1;
            $publisheraccountrecord->verify = '0';
            $publisheraccountrecord->resign = $request->get('resign');
            $publisheraccountrecord->save();  
          }
          $account1 = PublisherAccount::findOrFail($account->id);
          $account1->wallet_address = $request->get('wallet_address');
          $account1->verify = '0';
          $account1->locked = '0';
          $account1->save();
        }
          // $publisherAccountLog = new PublisherAccountLog();
          // $publisherAccountLog->user_id = $user_id;
          // $publisherAccountLog->wallet_address = $request->get('wallet_address');
          // $publisherAccountLog->save();
          return redirect()->route('publisher.settings.index')->with(['message'=> 'Wallet Address adeed succesfully. Please click on verify']);
      
    }

      public function payment_update(Request $request){
        $user_id = Auth::user()->id;
        $account = PublisherAccount::where('user_id',$user_id)->where('verify','0')->first();
        $checkwallletaddress = $this->check_coin_wallet_address($account->wallet_address);
        $response = json_decode($checkwallletaddress); 
            
        if($response->error == 'That is not a valid address for that coin!'){
          echo 'That is not a valid address for that coin!';
        }else{
          PublisherAccount::where('id', $account->id)->update([ 'verify' => '1','locked'=>'0']);
          PublisherAccountRecord::where('user_id', $user_id)->where('account_id', $account->id)
          ->where('wallet_address', $account->wallet_address)->update([ 'verify' => '1']);
          echo 'please check your wallet. If you received the '.$response->result->amount.' amount please click on locked.';
        }
      }

      public function payment_locked(Request $request){
        $user_id = Auth::user()->id;
        $account = PublisherAccount::where('user_id',$user_id)->where('verify','1')->first();
        PublisherAccount::where('id', $account->id)->update([ 'locked'=>'1']);
      }

    private function check_coin_wallet_address($wallet_address){

      $request_data = [        
        'cmd'           => 'create_withdrawal',
        'key'           => env('COINPAYMENT_PUBLIC_KEY'),
        'amount'        => 0.00001,        
        'currency'      => 'BUSD.BEP20',
        'currency2'     => 'USD',
        'address'       => $wallet_address,
        'version'       => '1',
        'auto_confirm'  => '1',
        'note'          => 'verification amount',
      ];   
      // print_r($request_data);
      $data = http_build_query( $request_data, null, '&');
      $hmac = hash_hmac('sha512',$data,env('COINPAYMENT_PRIVATE_KEY'));
      return $response = $this->hit_curl_function($data,$hmac);
      
    }

    private function hit_curl_function($data,$hmac) {
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://www.coinpayments.net/api.php',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_HTTPHEADER => array(
          'HMAC: '.$hmac
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }

    public function telegram_group_view(){
      $publisher_id = Auth::user()->id;
      $groups = TelegramGroup::where('publisher_id',$publisher_id)->get();
      return view('publisher.settings.telegram.index',compact('groups'));
    }

    public function telegram_group(Request $request)
    {
      $request->validate([
        'telegram_group' => 'required|unique:telegram_groups',
        'frequency_of_ads' => 'required',
        'frequency_type' => 'required',
        'hours_type' => 'required',
        'days' => 'required_if:hours_type,==,Custom Days',
      ]);

      $groupcheck = explode('/',$request->get('telegram_group'));      
      if(count($groupcheck) > 1){
        return redirect()->back()->with('error', 'just use what’s after the link');
      }

      if($request->get('hours_type') == 'All Days' && $request->get('from_all_days') == '11:00 PM'){
        return redirect()->back()->with('error', 'Start date not be 11:30 PM');
      }
      
      if($request->get('hours_type') == 'All Days' && $request->get('to_all_days') == '00:00 AM'){
        return redirect()->back()->with('error', 'End date not be 00:00 AM');
      }

    
      $publisher_id = Auth::user()->id;
      $default = TelegramGroup::where('default_group','Yes')->where('publisher_id',$publisher_id)->first();
      
      $telegramGroup = new TelegramGroup();
      $telegramGroup->publisher_id     = $publisher_id;
      $telegramGroup->telegram_group   = $request->get('telegram_group');      
      $telegramGroup->no_of_published  = 0;
      $telegramGroup->frequency_of_ads = $request->get('frequency_of_ads');
      $telegramGroup->frequency_type = $request->get('frequency_type');
      $telegramGroup->default_group    = (empty($default))?'Yes':'No';
      $telegramGroup->status           = '1';
      $telegramGroup->verify           = '0';
      $telegramGroup->hours_type       = $request->get('hours_type');
      $telegramGroup->save();
      
      $alldays = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');

      $telegramid = $telegramGroup->id;
      if($request->get('hours_type') == 'Custom Days'){
        foreach($request->get('days') as $key => $value){
          $telegrmTiming = new TelegramTiming();
          $telegrmTiming->telegram_group_id = $telegramid;
          $telegrmTiming->day = $value;
          $telegrmTiming->start_time = date('Y-m-d H:i:s',strtotime($request->get('from_time')[$key]));
          $telegrmTiming->end_time = date('Y-m-d H:i:s',strtotime($request->get('to_time')[$key]));
          $telegrmTiming->save();
        }
      }else{
         foreach($alldays as $key => $value){
            $telegrmTiming = new TelegramTiming();
            $telegrmTiming->telegram_group_id = $telegramid;
            $telegrmTiming->day = $value;
            $telegrmTiming->start_time = date('Y-m-d H:i:s',strtotime($request->get('from_all_days')));
            $telegrmTiming->end_time = date('Y-m-d H:i:s',strtotime($request->get('to_all_days')));
            $telegrmTiming->save();
          }
      }
      return redirect()->route('publisher.settings.telegram.group')->with('message', 'Group Added!');

    }

    public function telegram_group_add_view(){
      return view('publisher.settings.telegram.create');
    }

    public function telegram_group_edit_view($id){
      $group = TelegramGroup::with(['TelegrmGroupDay'])->findOrFail(decrypt($id));
      $daysArray = $alldays = [];
      foreach($group->TelegrmGroupDay as $key => $value){
        $daysArray[$value->day] =  array(
            'from' => date('H:i A',strtotime($value->start_time)),
            'to'   => date('H:i A',strtotime($value->end_time)),
        );
        $alldays[] = $value->day;
      }
      return view('publisher.settings.telegram.edit',compact('group','daysArray','alldays'));
    }

    public function telegram_group_update(Request $request){
      
      $publisher_id = Auth::user()->id;
      if($request->get('key') == 'verify'){
        $groupName = TelegramGroup::findOrFail($request->get('telegrm_group'));
        $telegramgroup = $groupName->telegram_group;
        $response = $this->check_group_bot_admin($telegramgroup);
        
        if(isset($response['ok']) && $response['ok'] == 1){
          TelegramGroup::where('id', $request->get('telegrm_group'))->update([
            $request->get('key') => $request->get('value')
          ]);
          echo 'success';
        }else{
          echo 'error';
        }
        exit;
      }

      if($request->get('value') == 'Yes' ){
        TelegramGroup::where('default_group', 'Yes')->where('publisher_id', $publisher_id)->update([
          'default_group' => 'No'
        ]);
        TelegramGroup::where('id', $request->get('telegrm_group'))->update([
          'default_group' => 'Yes'
        ]);
      }else{
        TelegramGroup::where('id', $request->get('telegrm_group'))->update([
          $request->get('key') => $request->get('value')
        ]);
      }
      
    }

    private function check_group_bot_admin($groupName){
      $social_marketing = new SocialMarketing('telegram',
        '',
        'checkMemberAccess',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        '',
        $groupName
      );
      return $social_marketing->sendRequest();
    }

    public function updatefreq(Request $request){
      $request->validate([
        'frequency_of_ads' => 'required',
        'hours_type' => 'required',
        'frequency_type' => 'required',
        'days' => 'required_if:hours_type,==,Custom Days',
      ]);
      
      if($request->get('hours_type') == 'All Days' && $request->get('from_all_days') == '11:00 PM'){
        return redirect()->back()->with('error', 'Start date not be 11:30 PM');
      }
      if($request->get('hours_type') == 'All Days' && $request->get('to_all_days') == '00:00 AM'){
        return redirect()->back()->with('error', 'End date not be 00:00 AM');
      }
     /* TelegramGroup::where('id', $request->get('group_id'))->update([
        'telegram_group' => $request->get('telegram_group'),
        'frequency_of_ads' => $request->get('frequency_of_ads'),
        'hours_type' => $request->get('hours_type')
      ]);  */

      $telegramid = $request->get('group_id');
      $telegramGroup = TelegramGroup::findOrFail($telegramid);
      $telegramGroup->telegram_group    = $request->get('telegram_group');
      $telegramGroup->frequency_of_ads  = $request->get('frequency_of_ads');
      $telegramGroup->frequency_type    = $request->get('frequency_type');
      $telegramGroup->hours_type        = $request->get('hours_type');
      $telegramGroup->from_all_days     = date('Y-m-d H:i:s',strtotime($request->get('from_all_days')));
      $telegramGroup->to_all_days       = date('Y-m-d H:i:s',strtotime($request->get('to_all_days')));
      $telegramGroup->save();
      $alldays = array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
      TelegramTiming::where('telegram_group_id', $telegramid)->delete();
      if($request->get('hours_type') == 'Custom Days'){
        foreach($request->get('days') as $key => $value){
          $telegrmTiming = new TelegramTiming();
          $telegrmTiming->telegram_group_id = $telegramid;
          $telegrmTiming->day = $value;
          $telegrmTiming->start_time = date('Y-m-d H:i:s',strtotime($request->get('from_time')[$key]));
          $telegrmTiming->end_time = date('Y-m-d H:i:s',strtotime($request->get('to_time')[$key]));
          $telegrmTiming->save();
        }
      }else{
        foreach($alldays as $key => $value){
          $telegrmTiming = new TelegramTiming();
          $telegrmTiming->telegram_group_id = $telegramid;
          $telegrmTiming->day = $value;
          $telegrmTiming->start_time = date('Y-m-d H:i:s',strtotime($request->get('from_all_days')));
          $telegrmTiming->end_time = date('Y-m-d H:i:s',strtotime($request->get('to_all_days')));
          $telegrmTiming->save();
        }
      }
      return redirect()->route('publisher.settings.telegram.group')->with('message', 'Group update!');
    }

    public function telegram_group_delete($id){      
      $group = TelegramGroup::findOrFail(decrypt($id));
      if($group->default_group == 'Yes'){
        return redirect()->back()->with('message', 'Default group can not be Delete');
      }else{
        $group->delete();
        TelegramTiming::where('telegram_group_id', decrypt($id))->delete();
        return redirect()->back()->with('message', 'Group Deleted!');
      }
    }

  
    
  }
