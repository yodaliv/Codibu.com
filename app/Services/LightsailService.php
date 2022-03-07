<?php


namespace App\Services;


use App\Models\Bundle;
use App\Models\Plan;
use App\Models\Site;
use Aws\Lightsail\LightsailClient;
use Aws\Sts\StsClient;
use Illuminate\Support\Facades\Log;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

class LightsailService
{
    protected $client;

    public function __construct($iam_id)
    {
        $client          = new StsClient([
            'profile' => 'default',
            'region'  => 'us-east-1',
            "version" => 'latest'
        ]);
        $roleToAssumeArn = 'arn:aws:iam::'.$iam_id.':role/OrganizationAccountAccessRole';
        $assumeRole      = $client->assumeRole([
            'RoleArn'         => $roleToAssumeArn,
            'RoleSessionName' => 'session1'
        ]);

        $this->client = new LightsailClient([
            'region'      => 'us-east-1',
            "version"     => 'latest',
            'credentials' => [
                'key'    => $assumeRole['Credentials']['AccessKeyId'],
                'secret' => $assumeRole['Credentials']['SecretAccessKey'],
                'token'  => $assumeRole['Credentials']['SessionToken']
            ]
        ]);
    }

    public function getBundles()
    {
        /*collect(collect((new LightsailService())->getBundles()['bundles'])->where('supportedPlatforms',["LINUX_UNIX"] )->all())->each(function ($item, $key) {
            Bundle::create([
                "price" =>$item['price'],
                "cpuCount" =>$item['cpuCount'],
                "diskSizeInGb" =>$item['diskSizeInGb'],
                "bundleId" =>$item['bundleId'],
                "instanceType" =>$item['instanceType'],
                "isActive" =>$item['isActive'],
                "name" =>$item['name'],
                "power" =>$item['power'],
                "ramSizeInGb" =>$item['ramSizeInGb'],
                "transferPerMonthInGb" =>$item['transferPerMonthInGb']
            ]);
        });*/
        return $this->client->getBundles();
    }
    public function createInstance(Site $site)
    {

        return $this->client->createInstances([
            'availabilityZone' => 'us-east-1a',
            'blueprintId' => 'wordpress',
            'bundleId' => $site->plan->bundle->bundleId,
            'instanceNames' => [$site->instance_name],
            'keyPairName' => 'cloudFormationKeyPair',
        ]);

    }

    public function stopInstance(Site $site)
    {
        $site->update(['status'=>'stopping']);
        return $this->client->stopInstance([
            'instanceName' => $site->instance_name,
        ]);
    }

    public function startInstance(Site $site)
    {
        $site->update(['status'=>'starting']);
        $this->client->startInstance([
            'instanceName' => $site->instance_name,
        ]);
    }

    public function getInstance($instance_name)
    {
        return $this->client->getInstance([
            'instanceName' => $instance_name,
        ]);
    }

    public function deleteInstance(Site $instance)
    {
        return $this->client->deleteInstance([
            'instanceName' => $instance->instance_name,
        ]);
    }
    public function deleteStaticIp(Site $instance)
    {
        return $this->client->releaseStaticIp([
            'staticIpName' => $instance->static_ip_name,
        ]);
    }
    public function createStaticIp($name)
    {
        return $this->client->allocateStaticIp([
            'staticIpName' => $name,
        ]);
    }

    public function attachStaticIp($site)
    {
        return $this->client->attachStaticIp([
            'instanceName' => $site->instance_name,
            'staticIpName' => $site->static_ip_name,
        ]);
    }

    public function getStaticIp($name)
    {
        return $this->client->getStaticIp([
            'staticIpName' => $name,
        ]);
    }

    public function installSite(Site $site, $ipAddress)
    {
        ini_set('max_execution_time', 0);
        $prefix = $site->demo->blog_prefix;
        $demo_url = str_replace("codibu","toprankon",$site->demo->url);
        $network_url = $site->demo->network->url;
        $site_title = $site->title;
        $name = $site->user->name;
        $email = $site->user->email;
        $password = $site->admin_password;
        $key1 = new RSA();
        $key1->loadKey(file_get_contents(storage_path('app/cloudFormationKeyPair.pem')));
        $ssh1 = new SSH2($ipAddress);
        if (!$ssh1->login("bitnami", $key1)) {
            throw new Exception('Login Failed', E_WARNING);
        } else {

            $ssh1->exec("curl -o /opt/bitnami/wordpress/wp-content/themes/themes.zip ". $network_url . "/wp-content/themes/themes.zip");
            $ssh1->exec("curl -o  /opt/bitnami/wordpress/wp-content/plugins/plugins.zip ". $network_url . "/wp-content/plugins/plugins.zip");
            $ssh1->exec("curl -o /opt/bitnami/wordpress/wp-content/" . $prefix . ".zip ". $network_url . "/wp-content/uploads/sites/" . $prefix . ".zip");
            $ssh1->exec("curl -o /opt/bitnami/wordpress/wp-content/db.sql ". $network_url . "/exporter/dumps/" . $demo_url . ".sql");
        }

        //////////////////////////////////


        $key2 = new RSA();
        $key2->loadKey(file_get_contents(storage_path('app/cloudFormationKeyPair.pem')));
        $ssh2 = new SSH2($ipAddress);
        if (!$ssh2->login("bitnami", $key2)) {
            throw new Exception('Login Failed', E_WARNING);
        } else {
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar search-replace wp_ wp_" . $prefix . "_");
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar db import /opt/bitnami/wordpress/wp-content/db.sql");
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar search-replace toprankon codibu --all-tables");
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar core update-db");
            $ssh2->exec("unzip -o /opt/bitnami/wordpress/wp-content/themes/themes.zip -d /opt/bitnami/wordpress/wp-content/themes/");
            $ssh2->exec("unzip -o /opt/bitnami/wordpress/wp-content/plugins/plugins.zip -d /opt/bitnami/wordpress/wp-content/plugins/");
            $ssh2->exec("unzip -o /opt/bitnami/wordpress/wp-content/" . $prefix . ".zip -d /opt/bitnami/wordpress/wp-content");
            $ssh2->exec("cp -r /opt/bitnami/wordpress/wp-content/" . $prefix . "/. /opt/bitnami/wordpress/wp-content/uploads/.");
            $ssh2->exec("rm -rf /opt/bitnami/wordpress/wp-content/" . $prefix);
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar option update blogname " . $site_title);
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar option update admin_email " . $email);
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar user update 1 --display_name=" . $name . " --user_email=" . $email . " --user_pass=" . $password);
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar db query 'RENAME TABLE wp_users TO wp_'" . $prefix . "'_users'");
            $ssh2->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar db query 'RENAME TABLE wp_usermeta TO wp_'" . $prefix . "'_usermeta'");

        }
        ///////////////////////////////////////////////////////////////////////////////////
        $key3 = new RSA();
        $key3->loadKey(file_get_contents(storage_path('app/cloudFormationKeyPair.pem')));
        $ssh3 = new SSH2($ipAddress);
        if (!$ssh3->login("bitnami", $key3)) {
            throw new Exception('Login Failed', E_WARNING);
        } else {
            $ssh3->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar config set table_prefix wp_" . $prefix . "_");
            $ssh3->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar search-replace wp_" . $prefix . "_". $prefix . "_ wp_" . $prefix . "_");
            $ssh3->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar rewrite flush");
            $ssh3->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar plugin install woocommerce --activate");
            $ssh3->exec("cd /opt/bitnami/wordpress && php /opt/bitnami/wp-cli/bin/wp-cli.phar plugin activate --all");
            $ssh3->exec("cd /opt/bitnami/wordpress/wp-content && chmod -R 777 ai1wm-backups");
            $ssh3->exec("cd /opt/bitnami/wordpress/wp-content && chmod -R 777 themes");
            $ssh3->exec("cd /opt/bitnami/wordpress/wp-content && chmod -R 777 plugins");
            $ssh3->exec("cd /opt/bitnami/wordpress/wp-content && chmod -R 777 uploads");
            $ssh3->exec("cd /opt/bitnami/wordpress && chmod -R 777 wp-content");
            $ssh3->exec("cd /opt/bitnami/wordpress && chmod -R 777 uploads");
            $ssh3->exec("cd /opt/bitnami/wordpress/wp-content && chmod -R 777 ai1wm-backups");
            $ssh3->exec("cd /opt/bitnami/wordpress/wp-content/plugins/all-in-one-wp-migration && chmod -R 777 storage");

            sleep(2);

            $ssh3->exec('cd /opt/bitnami/php/etc/conf.d && sudo touch custom.user.ini');
            $ssh3->exec('sudo chown bitnami:bitnami /opt/bitnami/php/etc/conf.d/custom.user.ini');
            $ssh3->exec('sudo printf "post_max_size=1000M\nupload_max_filesize=1000M\nmax_input_vars=6000\nmax_execution_time=600\n" >> /opt/bitnami/php/etc/conf.d/custom.user.ini');
            $ssh3->exec('sudo /opt/bitnami/ctlscript.sh restart');

            sleep(60);
            $site->status = 'completed';
            $site->server_ip = $ipAddress;
            $site->save();
        }
    }
}
