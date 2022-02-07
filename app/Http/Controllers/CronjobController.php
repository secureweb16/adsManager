<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Campaign;
use App\Models\TelegramGroup;
use App\Models\Option;
use App\Models\CampaignReport;
use Carbon\Carbon;
use App\Models\CampaignPublishGroup;
use App\Models\PublisherAccount;
use App\Models\PublisherPayment;

/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;

/*Service*/
use App\Services\SocialMarketService;
use App\Services\PaymentAutoPay;

class CronjobController extends Controller
{

  public function payout_payments(){

    echo "<pre>";
    new PaymentAutoPay();
    
    exit;
    $publisherPayments = PublisherPayment::where('payable_amount','>',0)->get()
    // ->toArray()
    ;
    echo "<pre>";
    print_r($publisherPayments);    

  }

	public function push_telegram(){

    $socialMarketService = new SocialMarketService();    
    $allCampaign = $socialMarketService->fetchCamapign();    
    $telegramGroup = $socialMarketService->fetchPublisherTelegramGroup();
    // $socialMarketService->postTheDataOnTelegrm($allCampaign,$telegramGroup);
    $fetchcampigns = $socialMarketService->fetchNewCamapignToPost();

    print_r($fetchcampigns);
    // print_r($telegramGroup);
     exit;
	}

	function delete_telegram(){

	echo "<pre>";



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
              'bannernetworkparick', // telegram group
              '',
              31,//message id
              25 // telegram group id
            );    
				echo "<pre>";
        $response = $social_marketing->sendRequest();

        print_r( $social_marketing);
        print_r( $response);
		
	

	}


}




