<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/*Service*/
use App\Services\PaymentAutoPay;

class Payout extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payout:cron';

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
        echo date('Y-m-d H:i:s l');
        // new PaymentAutoPay();
        // return 0;
    }
}
