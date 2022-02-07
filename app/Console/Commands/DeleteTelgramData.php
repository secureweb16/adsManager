<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PublisherGroup;
use App\Models\PublishingCampaign;

/*secureweb/socialmarketing*/
use Secureweb\Socialmarketing\SocialMarketing;
use Secureweb\Socialmarketing\Models\Campaignmessage;

class DeleteTelgramData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deletetelgram:cron';

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
      $campaignmessage = Campaignmessage::get()->groupBy('publisher_group_id');

      foreach ($campaignmessage as $publisher_group_id => $message) {
        $groupName = PublisherGroup::where('id',$publisher_group_id)->first();
        foreach ($message as $key => $value) {
          $average_cost = get_option_value('average_cost_'.$value->publisher_id);
          $publishingdata = PublishingCampaign::where('publisher_id',$value->publisher_id)->where('remaing_funds','<',$average_cost)->first();
         if(!empty($publishingdata)){
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
              $groupName->telegram_group_link,
              '',
              $value->message_id,
              $value->id
            );          
            $response = $social_marketing->sendRequest();
          }
        }
      }
        // return Command::SUCCESS;
    }
  }
