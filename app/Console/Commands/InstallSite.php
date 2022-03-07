<?php

namespace App\Console\Commands;

use App\Models\Site;
use App\Services\GoDaddyService;
use App\Services\LightsailService;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class InstallSite extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toprankon:installsite';

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
        Site::where('status', 'building')->get()->each(function (Site $site) {

            $user = User::find($site->user_id);
            $instance = (new LightsailService($user->aws_account_id))->getInstance($site->instance_name);
            $createdAt = date("Y-m-d H:i:s",strtotime($site->created_at." +4 minutes"));
            $new = date("Y-m-d H:i:s",strtotime(now()));
            if($instance["instance"]["state"]["name"] == "running" && $createdAt < $new){

                $staticIp = (new LightsailService($user->aws_account_id))->getStaticIp($site->static_ip_name);

                if(!$staticIp["staticIp"]["isAttached"]){
                    (new LightsailService($user->aws_account_id))->attachStaticIp($site);
                }
                sleep(30);
                if((new GoDaddyService())->dnsRecordsCount($site->domain) < 2){
                    (new GoDaddyService())->attachHostingIp($site->domain, $staticIp["staticIp"]["ipAddress"]);
                }
                dispatch(new \App\Jobs\InstallSite($site, $staticIp["staticIp"]["ipAddress"]));

            }

            if($instance["instance"]["state"]["name"] == "running" && $site->status == 'starting'){
                $site->update(['status'=>'completed']);
            }

            if($instance["instance"]["state"]["name"] == "stopped" && $site->status == 'stopping'){
                $site->update(['status'=>'stopped']);
            }


        });

        Site::where('status', 'stopping')->get()->each(function (Site $site) {

            $user = User::find($site->user_id);
            $instance = (new LightsailService($user->aws_account_id))->getInstance($site->instance_name);
            if($instance["instance"]["state"]["name"] == "stopped"){
                $site->update(['status'=>'stopped']);
                Log::info('stopped instance');
            }
        });

        Site::where('status', 'starting')->get()->each(function (Site $site) {

            $user = User::find($site->user_id);
            $instance = (new LightsailService($user->aws_account_id))->getInstance($site->instance_name);
            if($instance["instance"]["state"]["name"] == "running"){
                $site->update(['status'=>'completed']);
                Log::info('completed instance');
            }
        });
    }
}
