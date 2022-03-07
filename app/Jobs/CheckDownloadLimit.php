<?php

namespace App\Jobs;

use App\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CheckDownloadLimit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("CheckDownloadLimit Start");
        foreach (User::whereHas('sites')->get() as $user) {
            foreach ($user->sites as $site) {
                $createdDay = Carbon::now()->daysInMonth < Carbon::parse($site->created_at)->day ? Carbon::now()->daysInMonth : Carbon::parse($site->created_at)->day;
                if ($createdDay == Carbon::now()->day){
                    $user->downloads = $user->downloads - $site->plan->download_limit > 0 ? $user->downloads - $site->plan->download_limit : 0;
                    $user->save();
                }
            }
        }
    }
}
