<?php

namespace App\Jobs;

use App\Models\Site;
use Aws\CloudFormation\CloudFormationClient;
use Aws\Ec2\Ec2Client;
use Aws\Rds\RdsClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

class EC2Reboot implements ShouldQueue
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
        $sites = Site::whereNotNull(['server_ip', 'old_server_ip'])->get();

        $cfcCredential = array(
            'region'  => 'us-east-1',
            "version" => 'latest'
        );

        foreach ($sites as $site) {
            $client = CloudFormationClient::factory($cfcCredential);

            $ec2 = Ec2Client::factory($cfcCredential);

            $rds = RdsClient::factory($cfcCredential);

            $stack = $client->describeStacks([
                'StackName' =>  $site->stack_id,
            ]);

            if ($stack->toArray()["Stacks"][0]["StackStatus"] == "CREATE_COMPLETE") {

                $resources = $client->listStackResources([
                    'StackName' => $site->stack_id,
                ]);

                $result = $resources->toArray();

                $ec2_result = $ec2->describeInstances([
                    'InstanceIds' => [$result['StackResourceSummaries'][2]['PhysicalResourceId']]
                ]);

                $rds_result = $rds->describeDBInstances([
                    'DBInstanceIdentifier' => $result['StackResourceSummaries'][5]['PhysicalResourceId']
                ]);

                $es2Status = $ec2_result['Reservations'][0]['Instances'][0]['State']['Name'];
                $rdsStatus = $rds_result['DBInstances'][0]['DBInstanceStatus'];
                if ($es2Status == "running" && $rdsStatus == "available") {
                    $key = new RSA();
                    $key->loadKey(file_get_contents(storage_path('app/test19_new.pem')));
                    $ssh = new SSH2($site->server_ip);
                    if (!$ssh->login("ec2-user", $key)) {
                        throw new Exception('Login Failed', E_WARNING);
                    } else {
                        $ssh->exec("cd /var/www/html && php /home/ec2-user/wp-cli.phar search-replace " . $site->old_server_ip . " " . $site->server_ip);
                    }

                    $ec2->rebootInstances([
                        'InstanceIds' => [$result['StackResourceSummaries'][2]['PhysicalResourceId']],
                    ]);

                    $rds->rebootDBInstance([
                        'DBInstanceIdentifier' => $result['StackResourceSummaries'][5]['PhysicalResourceId']
                    ]);

                    $site->status = "completed";
                    $site->old_server_ip = null;
                    $site->save();
                }
            }
        }
    }
}
