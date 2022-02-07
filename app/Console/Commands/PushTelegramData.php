<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/*Service*/
use App\Services\TelegramPush;

class PushTelegramData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pushtelegram:cron';

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

      new TelegramPush();
        // return Command::SUCCESS;
    }
  }
