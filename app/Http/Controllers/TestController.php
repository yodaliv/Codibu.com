<?php

namespace App\Http\Controllers;

use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;

class TestController extends Controller
{
    public function __invoke()
    {
        $key3 = new RSA();
        $key3->loadKey(file_get_contents(storage_path('app/cloudFormationKeyPair.pem')));
        $ssh3 = new SSH2('54.88.77.37');

        if (!$ssh3->login("bitnami", $key3)) {
            throw new Exception('Login Failed', E_WARNING);
        } else {
            echo 'cd /opt/bitnami/wordpress && awk \'NR==3{print "ini_set(\'"post_max_size"\', \'"1000M"\');\nini_set(\'"post_max_size"\', \'"1000M"\');\nini_set(\'"max_input_vars"\', \'"6000"\');\nini_set(\'"max_execution_time"\', \'"600"\');\n\n"}1\' wp-config.php > wp-config && mv wp-config wp-config.php';
            $ssh3->exec('cd /opt/bitnami/wordpress && awk \'NR==3{print "ini_set(\'"post_max_size"\', \'"1000M"\');\nini_set(\'"post_max_size"\', \'"1000M"\');\nini_set(\'"max_input_vars"\', \'"6000"\');\nini_set(\'"max_execution_time"\', \'"600"\');\n\n"}1\' wp-config.php > wp-config && mv wp-config wp-config.php');
        }
    }
}
