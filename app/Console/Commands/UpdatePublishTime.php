<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CampaignDurationDetail;

class UpdatePublishTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updatepublishtime:cron';

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
        CampaignDurationDetail::where('duration_type', '=', 'Per Day')
          ->update([
            'next_publish_time' => time(),
            'used_duration' => 0,
          ]);
        // return Command::SUCCESS;
    }
}
