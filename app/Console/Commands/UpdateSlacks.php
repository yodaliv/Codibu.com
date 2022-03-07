<?php

namespace App\Console\Commands;

use Aws\Ec2\Ec2Client;
use Aws\Rds\RdsClient;
use Aws\Sts\StsClient;
use Illuminate\Console\Command;
use App\Models\Site;
use App\Models\Notification;
use Illuminate\Support\Facades\Storage;
use Aws\CloudFormation\CloudFormationClient;

class UpdateSlacks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprankon:update_stacks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download SQL demos';

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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        set_time_limit(0);
        ini_set('memory_limit', '2048M');
        $client = new StsClient([
            'profile' => 'default',
            'region'  => 'us-east-1',
            "version" => 'latest'
        ]);
        $roleToAssumeArn = 'arn:aws:iam::336386245591:role/OrganizationAccountAccessRole';
        $assumeRole = $client->assumeRole([
            'RoleArn' => $roleToAssumeArn,
            'RoleSessionName' => 'session1'
        ]);

        $client = new CloudFormationClient([
            'region' => 'us-east-1',
            "version" => 'latest',
            'credentials' => [
                'key' => $assumeRole['Credentials']['AccessKeyId'],
                'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                'token' => $assumeRole['Credentials']['SessionToken']
            ]
        ]);


        $result = $client->listStacks();
        $stacks = $result['StackSummaries'];
        $statues = [
            'CREATE_IN_PROGRESS' => 'building',
            'CREATE_FAILED' => 'failed',
            'CREATE_COMPLETE' => 'completed',
            'ROLLBACK_IN_PROGRESS' => 'failed',
            'ROLLBACK_FAILED' => 'failed',
            'ROLLBACK_COMPLETE' => 'deleted',
            'DELETE_IN_PROGRESS' => 'deleted',
            'DELETE_FAILED' => 'deleted',
            'DELETE_COMPLETE' => 'deleted',
            'UPDATE_IN_PROGRESS' => 'building',
            'UPDATE_COMPLETE_CLEANUP_IN_PROGRESS' => 'building',
            'UPDATE_COMPLETE' => 'completed',
            'UPDATE_ROLLBACK_IN_PROGRESS' => 'failed',
            'UPDATE_ROLLBACK_FAILED' => 'failed',
            'UPDATE_ROLLBACK_COMPLETE_CLEANUP_IN_PROGRESS' => 'failed',
            'UPDATE_ROLLBACK_COMPLETE' => 'deleted'
        ];

        foreach($stacks as $stack) {
            if ($stack['StackStatus'] == 'DELETE_COMPLETE'){
                continue;
            }
            $client = CloudFormationClient::factory([
                'region' => 'us-east-1',
                "version" => 'latest',
                'credentials' => [
                    'key' => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token' => $assumeRole['Credentials']['SessionToken']
                ]
            ]);
            $resources = $client->listStackResources([
                'StackName' => $stack['StackName']
            ]);

            $resources = $resources->toArray();
            $ec2 = Ec2Client::factory([
                'region' => 'us-east-1',
                "version" => 'latest',
                'credentials' => [
                    'key' => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token' => $assumeRole['Credentials']['SessionToken']
                ]
            ]);
            $rds = RdsClient::factory([
                'region' => 'us-east-1',
                "version" => 'latest',
                'credentials' => [
                    'key' => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token' => $assumeRole['Credentials']['SessionToken']
                ]
            ]);

            $ec2_result = $ec2->describeInstances([
                'InstanceIds' => [$resources ['StackResourceSummaries'][2]['PhysicalResourceId']]
            ]);
            $rds_result = $rds->describeDBInstances([
                'DBInstanceIdentifier' => $resources['StackResourceSummaries'][5]['PhysicalResourceId']
            ]);

            $es2Status = $ec2_result['Reservations'][0]['Instances'][0]['State']['Name'];
            $rdsStatus = $rds_result['DBInstances'][0]['DBInstanceStatus'];
            if($es2Status == "running" && $rdsStatus == "available") {
                $update = ['status' => $statues[$stack['StackStatus']]];
                $site = Site::where('stack_id', $stack['StackId'])->first();
                if ($site && $site->status != $statues[$stack['StackStatus']]) {

                    if ($statues[$stack['StackStatus']] == 'completed') {
                        $result = $client->describeStacks(array(
                            'StackName' => $stack['StackName']
                        ));
                        $update['server_ip'] = $ec2_result['Reservations'][0]['Instances'][0]['PublicIpAddress'];
                    }

                    $notification = new Notification;
                    $notification->message = "Your site {$site->title} status is updated to <b>{$statues[$stack['StackStatus']]}</b>";
                    $notification->user_id = $site->user->id;
                    $notification->save();
                    $site->update($update);
                }
            }
        }

    }

}
