<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
      Commands\PushTelegramData::class,
      Commands\Payout::class,
      Commands\TruncateCampaignPublishGroup::class,
      Commands\UpdateCampaignDailyBudget::class,
  ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {      
      // $schedule->command('pushtelegram:cron')->everyMinute();
      $schedule->command('pushtelegram:cron')->everyTenMinutes();
      $schedule->command('truncatecampaignpublishgroup:cron')->daily();
      // $schedule->command('payout:cron')->weeklyOn(4, '23:50');
      
      $schedule->command('publisherreport:cron')->weekly()->thursdays()->at('23:50');

      // $schedule->command('publisherreport:cron')->weeklyOn(4,'23:50');
      $schedule->command('updatecampaigndailybudget:cron')->daily();
      $schedule->command('campaignstatus:cron')->hourly();
  
  }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
