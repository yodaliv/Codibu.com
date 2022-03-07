<?php

namespace App\Jobs;

use App\Services\AwsService;
use App\User;
use Aws\Organizations\OrganizationsClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SetAwsOrgAccountIdInUserModelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $account_id;
    private $user_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account_id, $user_id)
    {
        $this->account_id =$account_id;
        $this->user_id =$user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('SetAwsOrgAccountIdInUserModelJob start');
        (new AwsService())->importKeyPair($this->account_id);
        sleep(30);
        User::where('id',$this->user_id)->update(['aws_account_id' => $this->account_id]);
        Log::info($this->account_id);
    }
}
