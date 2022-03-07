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
use Illuminate\Support\Facades\Log;

class EC2StatusCheck implements ShouldQueue
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
        $sites = Site::all();

        $cfcCredential = array(
            'region'  => 'us-east-1',
            "version" => 'latest'
        );
        foreach ($sites as $key =>$site) {

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
                $ec2 = $ec2->describeInstances([
                    'InstanceIds' => [$result['StackResourceSummaries'][2]['PhysicalResourceId']]
                ]);

                $rds = $rds->describeDBInstances([
                    'DBInstanceIdentifier' => $result['StackResourceSummaries'][5]['PhysicalResourceId']
                ]);

                $es2Status = $ec2['Reservations'][0]['Instances'][0]['State']['Name'];
                $rdsStatus = $rds['DBInstances'][0]['DBInstanceStatus'];
                if($es2Status == "stopped" && $rdsStatus == "stopped"){
                    $site->status = "stopped";
                    $site->save();
                }
                /*if($es2Status == "running" && $rdsStatus == "available"){
                    $site->status = "completed";
                    $site->save();
                }*/
            }

        }
    }
}
