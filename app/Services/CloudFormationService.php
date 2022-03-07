<?php


namespace App\Services;


use App\Models\Demo;
use App\Models\Plan;
use App\Models\Site;
use App\Models\Theme;
use Aws\CloudFormation\CloudFormationClient;
use Aws\Ec2\Ec2Client;
use Aws\Exception\AwsException;
use Aws\Rds\RdsClient;
use Aws\Sts\StsClient;
use Illuminate\Support\Facades\Storage;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;
use function Symfony\Component\String\s;

class CloudFormationService
{
    public function createStack(Site $site, Plan $plan)
    {
        $json = 'aws-wp-demo.json';
        $client = new StsClient([
            'profile' => 'default',
            'region' => 'us-east-1',
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
        try {
            $result = $client->createStack(array(
                // StackName is required
                'StackName' => 'wordpress-' . $site->id,
                'TemplateBody' => Storage::disk('local')->get($json),
                'Parameters' => array(
                    array(
                        'ParameterKey' => 'LightsailInstanceName',
                        'ParameterValue' => 'wordpress-' . $site->id,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'BundleId',
                        'ParameterValue' => 'micro_2_0',
                        'UsePreviousValue' => false,
                    )
                ),
                'TimeoutInMinutes' => 50,
            ));
            return $result->toArray();
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }

    public function restart($id)
    {
        $site = Site::find($id);
        $client = new StsClient([
            'profile' => 'default',
            'region' => 'us-east-1',
            "version" => 'latest'
        ]);

        $roleToAssumeArn = 'arn:aws:iam::336386245591:role/OrganizationAccountAccessRole';

        try {
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
            $resources = $client->listStackResources([
                'StackName' => $site->stack_id,
            ]);
            $result = $resources->toArray();
            $ec2 = new Ec2Client(array(
                'region' => 'us-east-1',
                "version" => 'latest',
                'credentials' => [
                    'key' => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token' => $assumeRole['Credentials']['SessionToken']
                ]
            ));
            $rds = new RdsClient(array(
                'region' => 'us-east-1',
                "version" => 'latest',
                'credentials' => [
                    'key' => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token' => $assumeRole['Credentials']['SessionToken']
                ]
            ));

            $ec2->startInstances([
                'InstanceIds' => [$result['StackResourceSummaries'][2]['PhysicalResourceId']],
            ]);
            $rds->startDBInstance([
                'DBInstanceIdentifier' => $result['StackResourceSummaries'][5]['PhysicalResourceId']
            ]);
            $site->update(['status' => 'building']);
            $site->save();
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }

    public function terminate($id)
    {
        $site = Site::find($id);
        $client = new StsClient([
            'profile' => 'default',
            'region' => 'us-east-1',
            "version" => 'latest'
        ]);

        $roleToAssumeArn = 'arn:aws:iam::336386245591:role/OrganizationAccountAccessRole';

        try {
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
            $server_details = $client->describeStacks(array(
                'StackName' => $site->stack_id,
            ));
            $resources = $client->listStackResources([
                'StackName' => $site->stack_id,
            ]);
            $result = $resources->toArray();
            $ec2 = new Ec2Client(array(
                'region' => 'us-east-1',
                "version" => 'latest',
                'credentials' => [
                    'key' => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token' => $assumeRole['Credentials']['SessionToken']
                ]
            ));
            $rds = new RdsClient(array(
                'region' => 'us-east-1',
                'version' => 'latest',
                'credentials' => [
                    'key' => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token' => $assumeRole['Credentials']['SessionToken']
                ]
            ));

            $ec2->stopInstances([
                'InstanceIds' => [$result['StackResourceSummaries'][2]['PhysicalResourceId']],
            ]);
            $rds->stopDBInstance([
                'DBInstanceIdentifier' => $result['StackResourceSummaries'][5]['PhysicalResourceId']
            ]);
            $site->old_server_ip = $site->server_ip;
            $site->server_ip = '';
            $site->status = 'stopping';
            $site->save();
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }

    }

    public function installsite()
    {

    }
}
