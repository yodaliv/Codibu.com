<?php

namespace App\Jobs;

use App\Services\GoDaddyService;
use App\Services\LightsailService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use function Composer\Autoload\includeFile;

class InstallSite implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $site;
    private $ip;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($site, $ip)
    {
        $this->site = $site;
        $this->ip = $ip;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info("InstallSite Start");
        $user = User::find($this->site->user_id);
        (new LightsailService($user->aws_account_id))->installSite($this->site, $this->ip);
    }
}
