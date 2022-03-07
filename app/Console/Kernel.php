<?php

namespace App\Console;

use App\Jobs\CheckDownloadLimit;
use App\Jobs\EC2Reboot;
use App\Jobs\EC2StatusCheck;
use App\Jobs\GenerateSiteScore;
use App\Jobs\InstallSite;
use App\Jobs\PaymentHistoryJob;
use App\Jobs\SetAwsOrgAccountIdInUserModelJob;
use App\Jobs\SiteRenewalNotifyJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use App\Jobs\CheckUpdates;
use App\Jobs\DownloadUpdates;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     * /opt/cpanel/ea-php73/root/usr/bin/php
     *
     * @var array
     */
    protected $commands = [
        Commands\DownloadUpdates::class,
        Commands\CheckUpdates::class,
        Commands\ProcessThemes::class,
        Commands\ImportDemos::class,
        Commands\UpdateSlacks::class,
        Commands\DownloadNetworkPlugins::class,
        Commands\InstallSite::class,
        Commands\SetAwsOrgAccountIdInUserModel::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('toprankon:check')->weekly();
        $schedule->command('network:download')->monthly();
        $schedule->command('toprankon:download')->monthly();
        $schedule->command('toprankon:update_stacks')->everyMinute();
        $schedule->command('toprankon:installsite')->everyTwoMinutes();
        $schedule->command('toprankon:addiamkeypair')->everyTwoMinutes();
        $schedule->job(new CheckDownloadLimit())->daily();
        $schedule->job(new PaymentHistoryJob())->everyMinute();
//        $schedule->job(new GenerateSiteScore())->everyMinute();
        //$schedule->job(new SiteRenewalNotifyJob())->daily();


        //
        //$schedule->command('toprankon:update_info')->daily();*/
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        // $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
