<?php


namespace App\Services;

use Aws\Ec2\Ec2Client;
use Aws\Lightsail\LightsailClient;
use Aws\Organizations\OrganizationsClient;
use Aws\Sts\StsClient;

class AwsService
{
    public function createAccount($user)
    {
        $client = new OrganizationsClient([
            'region'  => 'us-east-1',
            "version" => 'latest'
        ]);
        $result = $client->createAccount([
            'AccountName' => $user->name,
            'Email' => $user->id.'@codibus.com',
            'IamUserAccessToBilling' => 'ALLOW',
            'RoleName' => 'OrganizationAccountAccessRole',
            'Tags' => [
                [
                    'Key' => 'app',
                    'Value' => 'codibu',
                ],
            ],
        ]);
    }

    public function importKeyPair($id)
    {
        $client = new StsClient([
            'profile' => 'default',
            'region'  => 'us-east-1',
            "version" => 'latest'
        ]);

        $roleToAssumeArn = 'arn:aws:iam::'.$id.':role/OrganizationAccountAccessRole';

        try {
            $result = $client->assumeRole([
                'RoleArn' => $roleToAssumeArn,
                'RoleSessionName' => 'session1'
            ]);
            $lightsailClient = new LightsailClient([
                'region'  => 'us-east-1',
                "version" => 'latest',
                'credentials' =>  [
                    'key'    => $result['Credentials']['AccessKeyId'],
                    'secret' => $result['Credentials']['SecretAccessKey'],
                    'token'  => $result['Credentials']['SessionToken']
                ]
            ]);

            $result = $lightsailClient->getKeyPairs();

            if (collect($result["keyPairs"])->where("name", "cloudFormationKeyPair")->first() == null){
            $lightsailClient->importKeyPair([
                'keyPairName' => 'cloudFormationKeyPair',
                'publicKeyBase64' => '---- BEGIN SSH2 PUBLIC KEY ----
Comment: "imported-openssh-key"
AAAAB3NzaC1yc2EAAAADAQABAAABAQCsUo2VONlRrVSjyOY4ZgPxWEfa/F1FfRSH
o76bpYZ433jfXdrE8CZ8aYMJ54BoexkvpkFgrEJmeRH7fAPzLROVKza6btOA/MDW
ykG9WlPfdOiaXLrH/8TOR87/fPV+EJSb4EZTPu1uZOs7KIHE13CK3tdwQcn9x5FK
oRYZ1Kutr9weuW8n3Vu2LmmenJ9nXK+VMQSYZE428hj0Q2sB1Oa1uKG1aXg+Sf3a
wWXv0eRGRJLwvE1Z/g9qtoDWI4j8mdOyURiOQgAMjUi2dpiBnrcToQAPnvEU4jBP
CClbgbQhSn+BIavNHCnmJ4lWio38Hqrz0RwImWYBrz0fJ0IIKBJt
---- END SSH2 PUBLIC KEY ----'
                            ]);

        }
        } catch (AwsException $e) {
            // output error message if fails
            error_log($e->getMessage());
        }
    }
}
