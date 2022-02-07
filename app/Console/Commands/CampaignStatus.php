<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/*Service*/
use App\Services\CampaignStatusUpdate;

class CampaignStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaignstatus:cron';

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
        new CampaignStatusUpdate();
        // return 0;
    }
}
