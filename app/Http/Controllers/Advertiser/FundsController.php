<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hexters\CoinPayment\CoinPayment;
use Auth;
use DB;
use App\Models\CoinpaymentTransaction;
use App\Models\FundsAdvertisersLogs;
use App\Models\FundsDetails;
use App\Models\User;

use AshAllenDesign\LaravelExchangeRates\ExchangeRate;
use Guzzle\Http\Exception\ClientErrorResponseException;
use carbon\Carbon;
use App\Models\Notification;


class FundsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 	

    	/*ModelName::find(id)->value('column_name');*/
    	/*$email = Auth::user()->email;
    	$fundsAdviser = CoinpaymentTransaction::where('buyer_email',$email)->get();
    	$allCurency = CoinpaymentTransaction::pluck('coin','coin')->all();
    	$allCurency = implode(",",$allCurency);

    	$apikey = 'a638c1a73a616852508cf940128ae0b1';
    	$json = file_get_contents("http://api.coinlayer.com/api/live?access_key=$apikey&target=USD&symbols=$allCurency");

    	$curencyprice = json_decode($json, true);
    	$curencyprice = $curencyprice['rates'];

    	return view('advertiser.funds.list',compact('fundsAdviser','curencyprice'));*/
      return redirect()->route('advertiser.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	return view('advertiser.funds.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   

    	$name = Auth::user()->first_name." ".Auth::user()->last_name;    	
    	$email = Auth::user()->email;
    	$transaction['order_id'] = uniqid(); 
    	$transaction['amountTotal'] = $request->amount;
    	$transaction['note'] = 'Transaction note';
    	$transaction['buyer_name'] = $name;
    	$transaction['buyer_email'] = $email;
			$transaction['redirect_url'] = url('/advertiser/funds/success'); // When Transaction was comleted
			$transaction['cancel_url'] = url('/advertiser/funds/cancle'); // When user click cancel link

			$transaction['items'][] = [
				'itemDescription' => 'Add Funds',
			  'itemPrice' => (FLOAT) $request->amount, // USD
			  'itemQty' => (INT) 1,
			  'itemSubtotalAmount' => (FLOAT) $request->amount // USD
			];
			
			$paymentUrl = CoinPayment::generatelink($transaction);      
  
			return view('advertiser.funds.payment-view',compact('paymentUrl'));
		}

    public function funds_success(){
      // return view('advertiser.funds.success');
      $notification = new Notification();
      $notification->user_id      = Auth::user()->id;
      $notification->type         = 'add_funds';
      $notification->foruser      = 'avertiser';
      $notification->message      = 'Payments is under process. It will effect soon!';
      $notification->user_status  = '0';
      $notification->admin_status = '1';
      $notification->save();
    	echo "<h3 style='color:#fff;'>Payment finish! Payment is show in you acocunt soon.</h3>";      
    }

    public function funds_cancle(){
      $notification = new Notification();
      $notification->user_id      = Auth::user()->id;
      $notification->type         = 'add_funds';
      $notification->foruser      = 'avertiser';
      $notification->message      = 'Payments is cancled.';
      $notification->user_status  = '0';
      $notification->admin_status = '1';
      $notification->save();
    	echo "<h3 style='color:#fff;'>Transection Cancle</h3>";

    }

    public function payment_webhook_responce(Request $request){
    // public function payment_webhook_responce(){


    	$path = base_path('public/logs/').'paymetlog'.date('d_m_Y').'.log';
    	$file = fopen($path, 'a');


    	fwrite($file, "========================Time ====================\n\r");
    	fwrite($file, print_r(date('d-m-Y h:i:s'),true));


    	$merchant_id = env('COINPAYMENT_MARCHANT_ID');//"d26d420bc8b86467dee3edc096bc94f2";
    	$ipn_secret = env('COINPAYMENT_IPN_SECRET');
    	$debug_email = env('coinpayment_ipn_debug_email');

    	fwrite($file, "\n\r======================== REQUEST data ====================\n\r");
    	fwrite($file, print_r($_REQUEST,true));


    	$txn_id = $_REQUEST['txn_id'];


    	$coinPayments = CoinpaymentTransaction::where('txn_id',$txn_id)->first();
    	$coinPaymentsId = $coinPayments->id;

    	fwrite($file, "\n\r======================== Coin Payments Id ID ( $coinPaymentsId ) ====================\n\r");

    	$fundsAdvertiser = new FundsAdvertisersLogs();
    	$fundsAdvertiser->amount1 					= $_REQUEST['amount1'];
    	$fundsAdvertiser->amount2 					= $_REQUEST['amount2'];
    	$fundsAdvertiser->buyer_name 				= $_REQUEST['buyer_name'];
    	$fundsAdvertiser->currency1 				= $_REQUEST['currency1'];
    	$fundsAdvertiser->currency2 				= $_REQUEST['currency2'];
    	$fundsAdvertiser->email 						= $_REQUEST['email'];
    	$fundsAdvertiser->fee 							= $_REQUEST['fee'];
    	$fundsAdvertiser->ipn_id 						= $_REQUEST['ipn_id'];
    	$fundsAdvertiser->ipn_mode 					= $_REQUEST['ipn_mode'];
    	$fundsAdvertiser->ipn_type 					= $_REQUEST['ipn_type'];
    	$fundsAdvertiser->ipn_version 			= $_REQUEST['ipn_version'];
    	$fundsAdvertiser->received_amount 	= $_REQUEST['received_amount'];
    	$fundsAdvertiser->received_confirms = $_REQUEST['received_confirms'];
    	$fundsAdvertiser->send_tx 					= (isset($_REQUEST['send_tx']))?$_REQUEST['send_tx']:'';
    	$fundsAdvertiser->status 						= $_REQUEST['status'];
    	$fundsAdvertiser->status_text 			= $_REQUEST['status_text'];
    	$fundsAdvertiser->txn_id 						= $_REQUEST['txn_id'];
    	$fundsAdvertiser->merchant 					= $_REQUEST['merchant'];
    	$fundsAdvertiser->transectionid 		= $coinPaymentsId;
    	$fundsAdvertiser->save();


    	fwrite($file, "\n\r======================== txn_id ( $txn_id ) ====================\n\r");


    	$coinpaymentsdata = CoinpaymentTransaction::find($coinPaymentsId);


  		$order_curency = $coinPayments->currency_code; //USD
  		$order_total =  $coinPayments->amount_total_fiat;


  		if(!isset($_REQUEST['ipn_mode']) || $_REQUEST['ipn_mode'] != 'hmac') {
  			$this->edie('IPN Mode is NOt HMAC',$file);
  		}

  		if(!isset($_SERVER['HTTP_HMAC']) || empty($_SERVER['HTTP_HMAC'])) {
  			$this->edie('No HMAC Signature Sent',$file);
  		}

  		$request = file_get_contents("php://input");


  		if($request == false || empty($request)) {
  			$this->edie('Error in reading Post Data',$file);
  		}

  		if(!isset($_REQUEST['merchant']) || $_REQUEST['merchant'] != trim($merchant_id)) {
  			$this->edie('No Or incorrect merchant id',$file);
  		}

  	 // $hmac = hash_hmac("sha512",$request,trim($ipn_secret));

  	 // if(!hash_equals($hmac,$_SERVER['HTTP_HMAC'])){
  	 //    $this->edie('HMAC Signature does not match',$file);
  	 // }

      $amount1 		= floatval($_REQUEST['amount1']); // IN USD
      $amount2 		= floatval($_REQUEST['amount2']); // IN BTC
      $currency1 	= $_REQUEST['currency1']; //USD
      $currency2 	= $_REQUEST['currency2']; //BTC
      $status			= intval($_REQUEST['status']);
      $status_text = $_REQUEST['status_text'];

      fwrite($file, "\n\r======================== status ( $status ) ====================\n\r");


      if($currency1 != $order_curency){
      	$this->edie('Currency Missmatch',$file);
      }


      if($status >= 100 || $status == 2){
      	
        $coinpaymentsdata->status = $status;

        $user = User::where('email',$_REQUEST['email'])->first();
        $fundsdetails = FundsDetails::where('user_id',$user->id)->first();
        
        $unitAmount   = ($_REQUEST['amount2']/$_REQUEST['amount1']);
        $totalamount  = ($_REQUEST['received_amount']/$unitAmount); 
        
        fwrite($file, "\n\r======================== fundsdetails ====================\n\r");
        fwrite($file, print_r($fundsdetails,true));

        if(empty($fundsdetails)){
          $fundsdetails = new FundsDetails();
          $fundsdetails->user_id = $user->id;
          $fundsdetails->total_funds = $totalamount;
          $fundsdetails->spent_funds = 0;
          $fundsdetails->remaning_funds = $totalamount;
          $fundsdetails->save();
        }else{
          $total_funds = $fundsdetails->total_funds+$totalamount;
          $remaning_funds = $fundsdetails->remaning_funds+$totalamount;
          FundsDetails::where('user_id', $user->id)->update(['total_funds' => $total_funds,'remaning_funds'=>$remaning_funds]);
        }
        $notification = new Notification();
        $notification->user_id      = $user->id;
        $notification->type         = 'add_funds';
        $notification->foruser      = 'avertiser';
        $notification->message      = '$'.$totalamount.' Added successfullly';
        $notification->admin_message = $user->first_name.' '.$user->last_name.' $'.$totalamount.' Added successfullly';
        $notification->user_status  = '0';
        $notification->admin_status = '0';
        $notification->save();
        fwrite($file, "\n\r======================== Success ====================\n\r");

      }else if($status < 0){
      	$coinpaymentsdata->status = $status;
      	fwrite($file, "\n\r======================== Error ====================\n\r");
      }else {
      	$coinpaymentsdata->status = $status;
      	fwrite($file, "\n\r======================== Pendding ====================\n\r");
      }
      $coinpaymentsdata->save();

      fclose($file);
    }

  private function edie($error_msg,$file){
  	fwrite($file, "\n\r======================== EROOR REPORT ====================\n\r");
  	fwrite($file, print_r($error_msg,true));
  }

}
