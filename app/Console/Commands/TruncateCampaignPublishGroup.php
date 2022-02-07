<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CampaignPublishGroup;
use App\Models\TelegramGroup;

/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;


class TruncateCampaignPublishGroup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncatecampaignpublishgroup:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $publishcampaign = CampaignPublishGroup::where('clicks',0)->get();
      $this->delete_message_on_telegram($publishcampaign);
      return 0;
    }

    private function delete_message_on_telegram($publishcampaign){
      foreach ($publishcampaign as $key => $publishGroup) {              
        $camapignrecords        = Campaignmessage::where('unique_id',$publishGroup->unique_id)->first();        
        $telegram_group_id  = $camapignrecords->telegram_group_id;
        $group_id                   = $camapignrecords->id;
        $publisher_id           = $camapignrecords->publisher_id;
        $campmeassage_id        = $camapignrecords->campaigns_id;
        $messageID                  = $camapignrecords->message_id;

        $groupName                  = TelegramGroup::where('id',$telegram_group_id)->where('publisher_id',$publisher_id)->first();
        $telegramGroupName  = $groupName->telegram_group;

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
      CampaignPublishGroup::truncate();
    }
  }
