<?php

namespace App\Services;

use App\Models\PublisherPayment;
use App\Models\PayoutDetails;

class PaymentAutoPay{

	private $publicKey;
	private $privateKey;

	public function __construct(){				
		$this->publicKey = env('COINPAYMENT_PUBLIC_KEY');
		$this->privateKey = env('COINPAYMENT_PRIVATE_KEY');
		return $this->get_publisher();
	}

	/***************************************************
	*****************
	*****************
	* Get Publisher *
	*****************
	*****************
	****************************************************/

	private function get_publisher(){		
		$payments =  PublisherPayment::whereHas('publisher_account', function($q){
			$q->where('wallet_address','!=','');
		})->with(['publisher_account'])->where('payable_amount','>',0)->get();		
		$this->pay_publisher_amount($payments);
	}


	/***************************************************
	************************
	************************
	* Pay Publisher Amount *
	************************
	************************
	****************************************************/

	private function pay_publisher_amount($payments){		
		foreach ($payments as $key => $value) {
			$this->payment_process($value->id,$value->publisher_id,$value->payable_amount,$value->publisher_account->wallet_address);
		}
	}


	/***************************************************
	*******************
	*******************
	* Payment Process *
	*******************
	*******************
	****************************************************/

	private function payment_process($payment_id,$publisher_id,$amount,$wallet_address){

		$path = base_path('public/logs/').'Payout_logs.log';
		$file = fopen($path, 'a');

		fwrite($file, "======================== Time ====================\n\r");
		fwrite($file, print_r(date('d-m-Y h:i:s'),true));
		
		$request_data = [
			'format' 				=> 'json',
			'cmd' 					=> 'create_withdrawal',
			'key' 					=> $this->publicKey,
			'amount' 				=> $amount,
			'currency' 			=> 'BUSD',
			'currency2' 		=> 'USD',
			'address' 			=> $wallet_address,
			'version' 			=> '1',
			'auto_confirm' 	=> '1',
			'note' 					=> 'Auto Payout',
		];

		fwrite($file, "======================== Request Data ====================\n\r");
		fwrite($file, print_r($request_data,true));

		$data = http_build_query( $request_data, null, '&');
		$hmac = hash_hmac('sha512',$data,$this->privateKey);
		$response = $this->hit_curl_function($data,$hmac);
		$response = json_decode($response);

		fwrite($file, "======================== Response ====================\n\r");
		fwrite($file, print_r($response,true));


		$payoutDetails = new PayoutDetails();
		$payoutDetails->publisher_id = $publisher_id;
		$payoutDetails->error = $response->error;

		$admin_msg 	= '';

		if(isset($response->result->status) && $response->result->status == 1){
			$publisherpayment = PublisherPayment::find($payment_id);
			$publisherpayment->payable_amount = $publisherpayment->payable_amount-$amount;
			$publisherpayment->paid_amount 		= $publisherpayment->paid_amount+$amount;
			$publisherpayment->save();
			$payoutDetails->trs_id = $response->result->id;
			$payoutDetails->status = $response->result->status;
			$payoutDetails->amount = $amount;
			$payoutDetails->curency_amount = $response->result->amount;
			$user_msg 	= 'You are received $'.$amount;
			$admin_msg 	= UserName($publisher_id).' has get $'.$amount;
			$this->create_notification($user_msg,$admin_msg,$publisher_id,'0');
		}else{
			$this->create_notification($payoutDetails->error,$payoutDetails->error,$publisher_id,'0');			
		}
		$payoutDetails->save();		
		fclose($file);
	}

	/***************************************************
	***********************
	***********************
	* Create Notification *
	***********************
	***********************
	****************************************************/	

	private function create_notification($user_msg,$admin_msg,$user_id,$adminstatus){
		$notificationData = [
			'user_id'       => $user_id,
			'type'          => 'payout',
			'foruser'       => 'publisher',
			'message'       => $user_msg,
			'admin_message' => $admin_msg,
			'url'           => '',
			'admin_url'     => '',
			'user_status'   => '0',
			'admin_status'  => $adminstatus
		];
		CreateNotificatons($notificationData);
	}

	/***************************************************
	*********************
	*********************
	* Hit Curl Function *
	*********************
	*********************
	****************************************************/

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
}