<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;

class UpdateCampaignDailyBudget extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatecampaigndailybudget:cron';

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
        $updateCampaign = Campaign::where('admin_approval','=','1')->get();
        foreach($updateCampaign as $updateval){
          $updatedCampignData = Campaign::find($updateval->id);
          $remainingBudget = $updatedCampignData->remaing_total;
          $dailpay = $updatedCampignData->pay_daily;

          if($remainingBudget > $dailpay){
              $updatedCampignData->remaing_daily = $dailpay;
              $updatedCampignData->remaing_total = $remainingBudget - $dailpay;
          }else{
              $updatedCampignData->remaing_daily = $remainingBudget;    
              $updatedCampignData->remaing_total =  0;
          } 
           $updatedCampignData->campaign_status = 1;
          $updatedCampignData->save();
        }
        // return 0;
    }
}
