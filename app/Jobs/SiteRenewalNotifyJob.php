<?php

namespace App\Jobs;

use App\Mail\SiteRenewalNotifyMail;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SiteRenewalNotifyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new message instance.
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
        Log::info("SiteRenewalNotifyJob Start");
        $sites = Site::with("paymentHistories","user")->get();
        foreach ($sites as $site) {
            if ($site->lastPaymentHistory) {
                $expire_date = Carbon::parse($site->lastPaymentHistory->end_date);
                $now_date    = Carbon::now();
                $diff        = $expire_date->diffInDays($now_date);
                if ($diff < 1320) {
                    $email = new SiteRenewalNotifyMail($site, $diff);
                    Mail::to($site->user)->send($email);
                    Log::info('send mail');
                }
            }
        }
    }
}
