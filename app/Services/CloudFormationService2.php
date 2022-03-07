<?php


namespace App\Services;


use App\Models\Demo;
use App\Models\Plan;
use App\Models\Site;
use App\Models\Theme;
use Aws\CloudFormation\CloudFormationClient;
use Aws\Ec2\Ec2Client;
use Aws\Rds\RdsClient;
use Aws\Sts\StsClient;
use Illuminate\Support\Facades\Storage;
use function Symfony\Component\String\s;

class CloudFormationService
{
    public function createStack(Site $site, Plan $plan)
    {
        $slug = Theme::clean($site->slug);
        $json = 'aws-wp-demo.json';
        $demo = Demo::find($site->demo_id);
        $import = $demo->network->url . '/exporter/dumps/' . $demo->url . '.sql';
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

        try {
            $result = $client->createStack(array(
                // StackName is required
                'StackName' => $slug . '-' . $site->id,
                'TemplateBody' => Storage::disk('local')->get($json),
                'Parameters' => array(
                    array(
                        'ParameterKey' => 'EC2InstanceName',
                        'ParameterValue' => $site->slug . '_' . $site->id,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'InstanceType',
                        'ParameterValue' => $plan->ec2_instance_type != null ? $plan->ec2_instance_type : 't2.micro',
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'EC2InstanceStorage',
                        'ParameterValue' => $plan->ec2_storage != null ? $plan->ec2_storage : 10,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'RDSInstanceType',
                        'ParameterValue' => $plan->rds_instance_type != null ? $plan->rds_instance_type : 'db.t2.micro',
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'DBAllocatedStorage',
                        'ParameterValue' => $plan->ec2_storage  != null ? $plan->ec2_storage : 10,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'RDSInstanceName',
                        'ParameterValue' => "{$slug}{$site->id}",
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'MySQLUserName',
                        'ParameterValue' => 'administrator',
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'MySQLPassword',
                        'ParameterValue' => $site->db_pass,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'DatabaseName',
                        'ParameterValue' => 'toprankon',
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'KeyName',
                        'ParameterValue' => 'test19',
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WordPressTitle',
                        'ParameterValue' => $site->title,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WordPressDomain',
                        'ParameterValue' => $site->domain,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WordPressURL',
                        'ParameterValue' => $site->domain,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WordPressUserName',
                        'ParameterValue' => 'admin',
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WordPressUserPassword',
                        'ParameterValue' => $site->admin_password,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WordPressUserEmail',
                        'ParameterValue' => auth()->user()->email,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'ExtraSQL',
                        'ParameterValue' => url("/wp_users.sql"),
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WordPressTheme',
                        'ParameterValue' => $import,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'WpPrefex',
                        'ParameterValue' => isset($demo) ? $demo->blog_prefix : 1,
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'DemoDomain',
                        'ParameterValue' => isset($demo) ? $demo->url : '',
                        'UsePreviousValue' => false,
                    ),
                    array(
                        'ParameterKey' => 'NetworkURL',
                        'ParameterValue' => isset($demo) ? $demo->network->url : '',
                        'UsePreviousValue' => false,
                    ),
                    // ... repeated
                ),
                'DisableRollback' => false,
                'TimeoutInMinutes' => 50,
                'NotificationARNs' => [],
                'Capabilities' => ['CAPABILITY_IAM', 'CAPABILITY_NAMED_IAM'],
                'ResourceTypes' => []
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
            'region'  => 'us-east-1',
            "version" => 'latest'
        ]);

        $roleToAssumeArn = 'arn:aws:iam::336386245591:role/OrganizationAccountAccessRole';

        try {
            $assumeRole = $client->assumeRole([
                'RoleArn' => $roleToAssumeArn,
                'RoleSessionName' => 'session1'
            ]);
            $client = new CloudFormationClient([
                'region'  => 'us-east-1',
                "version" => 'latest',
                'credentials' =>  [
                    'key'    => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token'  => $assumeRole['Credentials']['SessionToken']
                ]
            ]);
            $resources = $client->listStackResources([
                'StackName' => $site->stack_id,
            ]);
            $result = $resources->toArray();
            $ec2 = new Ec2Client(array(
                'region'  => 'us-east-1',
                "version" => 'latest',
                'credentials' =>  [
                    'key'    => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token'  => $assumeRole['Credentials']['SessionToken']
                ]
            ));
            $rds = new RdsClient(array(
                'region'  => 'us-east-1',
                "version" => 'latest',
                'credentials' =>  [
                    'key'    => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token'  => $assumeRole['Credentials']['SessionToken']
                ]
            ));

            $ec2->startInstances([
                'InstanceIds' => [$result['StackResourceSummaries'][2]['PhysicalResourceId']],
            ]);
            $rds->startDBInstance([
                'DBInstanceIdentifier' => $result['StackResourceSummaries'][5]['PhysicalResourceId']
            ]);
            $site->update(['status'=>'building']);
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
            'region'  => 'us-east-1',
            "version" => 'latest'
        ]);

        $roleToAssumeArn = 'arn:aws:iam::336386245591:role/OrganizationAccountAccessRole';

        try {
            $assumeRole = $client->assumeRole([
                'RoleArn' => $roleToAssumeArn,
                'RoleSessionName' => 'session1'
            ]);
            $client = new CloudFormationClient([
                'region'  => 'us-east-1',
                "version" => 'latest',
                'credentials' =>  [
                    'key'    => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token'  => $assumeRole['Credentials']['SessionToken']
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
                'region'  => 'us-east-1',
                "version" => 'latest',
                'credentials' =>  [
                    'key'    => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token'  => $assumeRole['Credentials']['SessionToken']
                ]
            ));
            $rds = new RdsClient(array(
                'region'  => 'us-east-1',
                'version' => 'latest',
                'credentials' =>  [
                    'key'    => $assumeRole['Credentials']['AccessKeyId'],
                    'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                    'token'  => $assumeRole['Credentials']['SessionToken']
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
}
