<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Models\User;
use App\Models\RegistrationToken;
use App\Models\CampaignTracking;
use App\Models\Campaign;
/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;
use App\Models\CampaignFund;
use App\Models\TelegramGroup;
use App\Models\TrackRecords;
use App\Models\PublisherPayment;
use App\Models\PublisherReport;
use App\Models\PublisherReportLog;
use App\Models\CampaignReport;
use App\Models\CampaignPublishGroup;
use App\Models\CampaignClicksIpTracking;
use App\Notifications\UserRegistration as UserRegistration;
use Hash;
use Session;
use DB;
use Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class UserController extends Controller
{
	public function register(Request $request)
	{

		$validated = $request->validate(
			[
				'first_name' 				=> 'required',
				'email' 						=> 'required|unique:users',
				'password' 					=> 'required|min:6',
				'confirm_password' 	=> 'required|same:password',
				'user_role'					=> 'required',
				/*'phone_number'		=> 'required|min:10|max:10',*/
				'plate_form'				=> 'required_if:user_role,==,2',
			],
			[
				'plate_form.required_if' 			=> 'The type of platform link is required when user type is Publishers!',
			]
		);

		$user = new  User();
		$user->first_name 		= $request->get('first_name');
		$user->last_name 			= $request->get('last_name');
		$user->email 					= $request->get('email');
		$user->password 			= Hash::make($request->get('password'));
		$user->user_role 			= $request->get('user_role');
		$user->telegram_link 	= $request->get('telegram_link');
		$user->plate_form 		= ($request->get('user_role') == 2)?implode(',',$request->get('plate_form')):'';
		$user->user_status 		= 0;
		$user->save();

		$userid = $user->id;
		$token = time();

		$registrationToken = new  RegistrationToken();
		$registrationToken->user_id = $userid;
		$registrationToken->token = $token;
		$registrationToken->save();

		$details = [
			'name' => $request->get('first_name'),
			'email' => $request->get('email'),
			'usertoken' => url('/verify-email').'/'.$token,
		];
		
		$name = ($request->get('user_role') == 2)?'Publisher':'Advertiser';
		$url = ($request->get('user_role') == 2)?'publishers':'advertisers';
		$notification = new Notification();
		$notification->user_id      = $userid;
		$notification->type         = 'user_create';
		$notification->foruser      = 'any_user';
		$notification->admin_message = 'New '.$name.' is created';
		$notification->admin_url 		= url('admin').'/'.$url;
		$notification->user_status  = '1';
		$notification->admin_status = '0';
		$notification->save();

		(new UserRegistration())->toMail($details);
		// return redirect()->back()->with('message', 'User Register successfully');
		return redirect()->back()->with('message', 'User Register Please verify your email!');

	}

	public function verify_email($token){

		$checktoken = RegistrationToken::Where('token',$token)->first();
		if(!empty($checktoken)){
			$id = $checktoken->id;
			$user_id = $checktoken->user_id;
			$registrationtoken = RegistrationToken::find($id);
			
			if( $checktoken->status == 0 ) {
				$user = User::find($user_id);
				$user->user_status = 1;
				$user->save();
				$registrationtoken->status = 1;
				$registrationtoken->save();

				$notification = new Notification();
				$notification->user_id      = $user_id;
				$notification->type         = 'user_verify';
				$notification->foruser      = 'any_user';
				$notification->admin_message = $user->first_name.' '.$user->last_name.' is verifed';
				$notification->user_status  = '1';
				$notification->admin_status = '0';
				$notification->save();

				if (Auth::loginUsingId($user_id)) {
					return redirect('/dashboard');
				}
			}
			return redirect()->route('login')->with('message', 'You are allready verified.');
		}else{
			return redirect()->route('login')->with('error', 'User not exist');
		}

	}

	public function login(Request $request){
		$request->validate([
			'email' => 'required',
			'password' => 'required',
		]);

		$credentials = $request->only('email', 'password');
		$checkEmail = User::where([['email', '=', $request->get('email')]])->first();
		if(empty($checkEmail)){
			return redirect()->route('login')->with('error', 'Email dose not exist');
		}

		$checkStatus = User::where([['email', '=', $request->get('email')],['user_status','=',1]])->first();	
		
		if(!empty($checkStatus)){			
			if (Auth::attempt($credentials)) {
				return redirect('/dashboard');
			}else{
				return redirect()->back()->with('error', 'Password doest match please check');	
			}	
		}else{
			return redirect()->route('login')->with('error', 'Please verify your email');
		}
	}

	public function telegrm_track($trackingid,$utmf,$publisher_id,$telegram_group_id,$uniqueid){
	
/*			$path = base_path('public/logs/').'track_log.log';
    	$file = fopen($path, 'a');
    		fwrite($file, "\n\r========================Time ====================\n\r");
    	fwrite($file, print_r(date('d-m-Y h:i:s'),true));
fwrite($file, print_r($trackingid,true));
fwrite($file, print_r($publisher_id,true));
fwrite($file, print_r($utmf,true));
    	  fclose($file);*/

		$trackingData 	= CampaignTracking::where('traking_id',$trackingid)->where('utmf',$utmf)->first();
		$landing_url 		= $trackingData->landing_url;
		$campaign_id 		= $trackingData->campaign_id;
		$average_click_cost = get_option_value('average_cost_value');
		$campaigndata 	= Campaign::findOrFail($campaign_id);
		$totalcost 			= $campaigndata->campaign_budget;
		$remaing_total 	= ($campaigndata->remaing_total) - $average_click_cost;
		$remaing_daily 	= ($campaigndata->remaing_daily) - $average_click_cost;
		$detuction_cost = ($campaigndata->remaing_daily >= $average_click_cost)?$average_click_cost:$campaigndata->remaing_daily;

		if($trackingData){
			$checkip = $this->update_campaign_ip_tracking($campaign_id);
			if($checkip == 1){
				$this->create_notification($campaigndata->advertiser_id,$publisher_id,$campaigndata->campaign_name,$detuction_cost,$telegram_group_id);			
				$this->updateTelagramGroups($telegram_group_id);
				$this->updateCampaignCost($campaign_id,$remaing_daily);
				$this->insertTrackingRecord($publisher_id,$telegram_group_id,$campaign_id,$totalcost,$average_click_cost,$remaing_total,$utmf,$detuction_cost);
				$this->deleteCampaignRecord($uniqueid);
				$this->update_publisher_report($publisher_id,$campaign_id,$detuction_cost,$telegram_group_id);
				$this->update_capmaign_report($publisher_id,$campaign_id,$telegram_group_id);
				$this->update_publisher_payments($publisher_id,$detuction_cost);
			}
			return Redirect::to($landing_url);
		}

	}

	private function create_notification($advertiser_id,$publisher_id,$campaign_name,$detuction_cost,$telegram_group_id){
		$groupTelegram = TelegramGroup::find($telegram_group_id);
		$data = [
			[
				'user_id'				=> $advertiser_id,
				'type'					=> 'capaign_clicked',
				'foruser'				=> 'advertiser',
				'message'				=> $campaign_name.' campaign has clicked.',
				'admin_message'	=> $campaign_name.' campaign has clicked.',
				'url'						=> '',
				'admin_url'			=> '',
				'user_status'		=> '0',
				'admin_status'	=> '1',
				'created_at'		=> date('Y-m-d h:i:s'),
				'updated_at'		=> date('Y-m-d h:i:s')
			],
			[
				'user_id'				=> $advertiser_id,
				'type'					=> 'funds_debited',
				'foruser'				=> 'advertiser',
				'message'				=> $campaign_name.' daily funds '.$detuction_cost.' has been debited.',
				'admin_message'	=> '',
				'url'						=> '',
				'admin_url'			=> '',
				'user_status'		=> '0',
				'admin_status'	=> '1',
				'created_at'		=> date('Y-m-d h:i:s'),
				'updated_at'		=> date('Y-m-d h:i:s')
			],
			[
				'user_id'				=> $publisher_id,
				'type'					=> 'campaign_clicks',
				'foruser'				=> 'publisher',
				'message'				=> $groupTelegram->telegram_group.' group cliks '.$campaign_name.' campaign.',
				'admin_message'	=> '',
				'url'						=> '',
				'admin_url'			=> '',
				'user_status'		=> '0',
				'admin_status'	=> '1',
				'created_at'		=> date('Y-m-d h:i:s'),
				'updated_at'		=> date('Y-m-d h:i:s')
			],
			[
				'user_id'				=> $publisher_id,
				'type'					=> 'funds_credit',
				'foruser'				=> 'publisher',
				'message'				=> $detuction_cost.' has been credit.',
				'admin_message'	=> '',
				'url'						=> '',
				'admin_url'			=> '',
				'user_status'		=> '0',
				'admin_status'	=> '1',
				'created_at'		=> date('Y-m-d h:i:s'),
				'updated_at'		=> date('Y-m-d h:i:s')
			]
		];
		Notification::insert($data);
	}

	private function update_capmaign_report($publisherid,$campaign_id,$telegram_group_id){
		$camReport = CampaignReport::where('campaign_id', $campaign_id)
		->where('telegram_group_id', $telegram_group_id)
		->where('publisher_id', $publisherid)
		->first();

		CampaignReport::where('campaign_id', $campaign_id)
		->where('telegram_group_id', $telegram_group_id)
		->where('publisher_id', $publisherid)
		->update(['no_of_clicks' => $camReport->no_of_clicks+1]);
	}

	private function updateCampaignPublishGroup($uniqueid){
		CampaignPublishGroup::where('unique_id', $uniqueid)->update(['clicks' => 1]);
	}

	private function updateTelagramGroups($telegram_group_id){
		$groupTelegram = TelegramGroup::find($telegram_group_id);
		$groupTelegram->no_of_clicks = ($groupTelegram->no_of_clicks != '')?$groupTelegram->no_of_clicks:0+1;
		$groupTelegram->save();
	}

	private function deleteCampaignRecord($uniqueid){

		$camapignrecords 		= Campaignmessage::where('unique_id','=',$uniqueid)
		->where('created_at', '<=', Carbon::now()->subMinutes(20)->toDateTimeString())
		->first();
	
		if(!empty($camapignrecords)){
			$this->updateCampaignPublishGroup($uniqueid);
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
			}
		}
	}

	private function insertTrackingRecord($publisher_id,$telegram_group_id,$campaign_id,$totalcost,$average_click_cost,$remaing_total,$utmf,$detuction_cost){

		$ClickTrack = new  TrackRecords();
		$ClickTrack->publisher_id   = $publisher_id;
		$ClickTrack->group_id       = $telegram_group_id;
		$ClickTrack->campaign_id    = $campaign_id;
		$ClickTrack->clicks         = 1;
		$ClickTrack->total_cost     = $totalcost;
		$ClickTrack->remaining_cost = $remaing_total;
		$ClickTrack->deducted_cost  = $detuction_cost;
		$ClickTrack->average_cost   = $average_click_cost;
		$ClickTrack->utmf           = $utmf;
		$ClickTrack->save();
	}


	private function updateCampaignCost($campaign_id,$remaing_daily){		
		$remaing_daily = ($remaing_daily < 0)?0:$remaing_daily;
		$updateCostCampaign = Campaign::where('id', $campaign_id)->update(['remaing_daily' => $remaing_daily]);		
	}


	private function update_publisher_report($publisherid,$campaign_id,$averagecost,$telegram_group_id){

		$paymentdata = PublisherReport::where('publisher_id','=',$publisherid)
		->where('campaign_id','=',$campaign_id)
		->where('group_id','=',$telegram_group_id)
		->where('created_at','>=',Carbon::today())
		->first();

		$persentage = PayoutPersentage($publisherid);
		$userPayment = ( $persentage != '')?$persentage:get_option_value('publisher_payout');
		$adminPayment = 100 - $userPayment;

		$user_amount = number_format(($averagecost*$userPayment)/100,2);
		$admin_amount = number_format(($averagecost*$adminPayment)/100,2);

		if(empty($paymentdata)){
			$publisherReport = new PublisherReport();
			$publisherReport->publisher_id = $publisherid;			
			$publisherReport->campaign_id = $campaign_id;			
			$publisherReport->campaign_id = $campaign_id;			
			$publisherReport->no_of_publish = 1;			
			$publisherReport->no_of_clicks = 1;			
			$publisherReport->user_amount = $user_amount;
			$publisherReport->admin_amount = $admin_amount;
			$publisherReport->payable_amount = $user_amount;
			$publisherReport->total_amount = $averagecost;
			$publisherReport->save();
			$publisherReportID = $publisherReport->id;
		}else{
			$cpcId = $paymentdata->id;
			$clicks = $paymentdata->no_of_clicks+1;
			$payableAmount = $paymentdata->payable_amount+$user_amount;
			$totalAmount = $paymentdata->total_amount+$averagecost;
			PublisherReport::where('id', $cpcId)->update([ 				
				'no_of_clicks'		=> $clicks,
				'payable_amount'	=> $payableAmount,
				'total_amount'		=> $totalAmount,
				'user_amount'		=> $paymentdata->user_amount+$user_amount,
				'admin_amount'		=> $paymentdata->admin_amount+$admin_amount,
			]);
			$publisherReportID = $cpcId;
		}
		$this->update_publisher_report_log($publisherReportID,$user_amount,$admin_amount,$persentage);
	}

	private function update_publisher_report_log($publisherReportID,$user_amount,$admin_amount,$persentage){
		$publisherReportLog = new PublisherReportLog();
		$publisherReportLog->publisher_report_id = $publisherReportID;
		$publisherReportLog->user_amount 	= $user_amount;
		$publisherReportLog->admin_amount = $admin_amount;
		$publisherReportLog->persentage 	= $persentage;
		$publisherReportLog->save();
	}

	private function update_publisher_payments($publisherid,$averagecost){

		$paymentpayments = PublisherPayment::where('publisher_id','=',$publisherid)->first();
		$persentage = PayoutPersentage($publisherid);
		$userPayment = ( $persentage != '')?$persentage:get_option_value('publisher_payout');
		$adminPayment = 100 - $userPayment;

		$user_amount = number_format(($averagecost*$userPayment)/100,2);
		$admin_amount = number_format(($averagecost*$adminPayment)/100,2);
		if(empty($paymentpayments)){
			$publisherPayment = new PublisherPayment();
			$publisherPayment->publisher_id = $publisherid;
			$publisherPayment->user_amount = $user_amount;
			$publisherPayment->admin_amount = $admin_amount;
			$publisherPayment->payable_amount = $user_amount;
			$publisherPayment->total_amount = $averagecost;
			$publisherPayment->save();
		}else{
			PublisherPayment::where('id', $paymentpayments->id)->update([
				'payable_amount'	=>$paymentpayments->payable_amount+$user_amount,
				'total_amount'		=>$paymentpayments->total_amount+$averagecost,
				'user_amount'		=>$paymentpayments->user_amount+$user_amount,
				'admin_amount'		=>$paymentpayments->admin_amount+$admin_amount,
			]);
		}
	}

	private function update_campaign_ip_tracking($campaign_id){		
		$strtotime24 = strtotime('-24 hours');
		$IpCheck = CampaignClicksIpTracking::where('campaign_id','=',$campaign_id)
					->where('ip_address','=',request()->getClientIp())
					->whereDate('created_at','>=',Carbon::today())
					->first();
		if(empty($IpCheck)){
			$campaignClicksIpTracking = new CampaignClicksIpTracking();
			$campaignClicksIpTracking->campaign_id  = $campaign_id;
			$campaignClicksIpTracking->ip_address   = request()->getClientIp();
			$campaignClicksIpTracking->time   		= time();
			$campaignClicksIpTracking->save();
			return 1;
		}else{
			return 0;
		}
	}


	public function send_support_email(Request $request){
		$data = ['details'=> $request->get('value')];
		// $toEmail = get_option_value('adminstrater_email');
		$emails = ['to' => get_option_value('adminstrater_email'),'reply' => Auth::user()->email];
		Mail::send('emails.support', $data, function ($message) use ($emails){
			$message->to($emails['to']);
			$message->subject('Moonlaunch Media');
			$message->replyTo($emails['reply']);
		});
		echo 'true';
	}

	/*public function send_support_email_get(){	
		$toEmail = 'swt.test2018@gmail.com';
		$data = ['details'=> 'Hello'];
		$emails = ['to' => get_option_value('adminstrater_email'),'reply' => Auth::user()->email];
		// $replayemail =  Auth::user()->email;
		Mail::send('emails.support', $data, function ($message) use ($emails){		
		    $message->to($emails['to']);
		    $message->subject('Moonlaunch Media');
		    $message->replyTo($emails['reply']);
		});
	}*/

	public function update_notification(Request $request){
		$notification_id = $request->get('notification_id');
		Notification::where('id', $notification_id)->update(['user_status' => '1']);
		echo 'true';
	}

	public function update_notification_admin(Request $request){
		$notification_id = $request->get('notification_id');
		Notification::where('id', $notification_id)->update(['admin_status' => '1']);
		echo 'true';
	}

	public function curlapiCall($url,$data){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_TIMEOUT => 30000,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => json_encode($data),
			CURLOPT_HTTPHEADER => array(
				// Set here requred headers
				"accept: */*",
				"accept-language: en-US,en;q=0.8",
				"content-type: application/json",
			),
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
		 return   json_decode(json_encode(array('status'=>0,'error'=>$err)));
		} else {
		   return json_decode($response);
		}
	}

	public function loginfromMarketplace(Request $request){
		$request->validate([
			'email' => 'required',
			'uniquekey' => 'required',
		]);
		if(empty($request->get('email'))){
			return redirect()->route('login')->with('error', 'Something went wrong!');	
		}

		if(empty($request->get('uniquekey'))){
			return redirect()->route('login')->with('error', 'Something went wrong!');	
		}

		$data = array('email'=>$request->get('email'));
		$userVerify =  $this->curlapiCall('https://marketplace.moonlaunch.media/api/getexistuser',$data);
		$uniquekey ="";

		if($userVerify && $userVerify->status == 1 && $userVerify->user){
		 /*$uniquekey =  $userVerify->user->id."auth@123#";*/
		  $uniquekey = hash_hmac('sha256', $userVerify->user->id."auth@123#",'08f2ff7cf5c59Marketplace');

		  if($uniquekey != base64_decode($request->get('uniquekey'))){
		    return redirect()->route('login')->with('error', 'Something went wrong.');
		  }
		}

		if($userVerify && $userVerify->status == 0){
			return redirect()->route('login')->with('error', 'Something went wrong!');
		}

		if($userVerify && $userVerify->status == 1){
			$credentials = $request->only('email');
			$checkEmail = User::where([['email', '=', $request->get('email')]])->first();
			/*new register then auto login*/
			if(empty($checkEmail)){
				$user = new  User();	
				$user->first_name 		= $request->get('first_name');
				$user->last_name 			= $request->get('last_name');
				$user->email 					= $request->get('email');
				$user->password 			= $request->get('password');
				$user->user_role 			= ($request->get('user_role') == 1)?3:2;
				$user->user_status 		= 1;
				$user->save();

				if($user->id) {
					Auth::logout();
					Auth::loginUsingId($user->id);
					return redirect('/dashboard');
				}else{
					return redirect()->route('login')->with('error', 'Something went wrong!');	
				}
			}	else {
				/* auto login*/
				$checkStatus = User::where([['email', '=', $request->get('email')],['user_status','=',1],['user_role','!=',1]])->first();
				if(!empty($checkStatus)){
					Auth::logout();		
					if (Auth::loginUsingId($checkStatus->id)) {
						return redirect('/dashboard');
					} else {
						return redirect()->route('login')->with('error', 'Something went wrong!');	
					}	
				} else {
					return redirect()->route('login')->with('error', 'Please verify your email!');
				}
			}
		}
	}
}
