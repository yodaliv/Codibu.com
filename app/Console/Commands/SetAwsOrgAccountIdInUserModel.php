<?php

namespace App\Console\Commands;

use App\Services\AwsService;
use App\User;
use Aws\AwsClient;
use Aws\Organizations\OrganizationsClient;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SetAwsOrgAccountIdInUserModel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprankon:addiamkeypair';

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
        $client = new OrganizationsClient([
            'region' => 'us-east-1',
            "version" => 'latest'
        ]);
        $result = $client->listAccounts();

        foreach (User::where('aws_account_id', null)->get() as $user) {
            foreach ($result['Accounts'] as $account) {
                $explode_data = explode('@', $account['Email']);
                if ($explode_data[0] == $user->id){
                    dispatch(new \App\Jobs\SetAwsOrgAccountIdInUserModelJob($account['Id'], $user->id));
                }
            }
        }
    }
}
