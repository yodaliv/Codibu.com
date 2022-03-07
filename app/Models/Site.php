<?php

namespace App\Models;

use App\Mail\UserWithsite;
use App\Services\AmazonPayService;
use App\Services\GoDaddyService;
use App\Services\LightsailService;
use App\Services\PaypalService;
use App\Services\StripeService;
use Aws\Sts\StsClient;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Aws\CloudFormation\CloudFormationClient;
use App\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class Site extends Model
{
    //

    protected $fillable = [
        "title", "slug", "admin_password", "db_pass", "domain",
        "domain_type", "user_id", "coupon_code", "coupon_discount", "total_price", "demo_id", "plan_id", "subscription_id", "platform", "status", "theme_type",
        "status", "server_ip","old_server_ip","instance_name"];

    /**
     * return website score, global rank & page speed data from mysql2 database
     *
     * @var string[]
     */
    //protected $appends = ['site_info'];

    public function getSiteInfoAttribute(): array
    {
        $site_info       = ['score' => 0, 'global_rank' => 0, 'page_speed' => 0];
        $check_site_info = RecentHistory::on('mysql2')->where('domain_name', $this->domain)
            ->orderByDesc('id')->first();
        if ($check_site_info) {
            $response  = unserialize(base64_decode($check_site_info->other));
            $site_info = [
                'score'       => $response[0],
                'global_rank' => number_format($response[1]),
                'page_speed'  => $response[2],
            ];
        }
        return $site_info;
    }

    /**
     * The plugins that belong to the site.
     */
    public function plugins()
    {
        return $this->belongsToMany('App\Models\Plugin', 'site_plugins');
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function demo()
    {
        return $this->belongsTo(Demo::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paymentHistories(): HasMany
    {
        return $this->hasMany(PaymentHistory::class)->orderBy('end_date', 'desc');
    }

    public function lastPaymentHistory(): HasOne
    {
        return $this->hasOne(PaymentHistory::class)->orderByDesc('created_at');
    }

    public function recheck($cformation)
    {
        if (($this->status == 'queued' || $this->status == 'building')
            && Carbon::now()->diffInMinutes($this->created_at) >= 9) {
        }

    }


    public function all_plugins()
    {
        $site_plugins   = $this->plugins()->get();
        $filter_plugins = [];
        foreach ($site_plugins as $plugin) {
            $Version                       = $plugin->versions()->orderBy('version', 'desc')->first();
            $filter_plugins[$plugin->slug] = [
                'plugin'  => $Version->s3_url(),
                'slug'    => $plugin->slug,
                'version' => $Version->version
            ];
        }
        $theme_plugins = $this->theme->plugins()->get();
        foreach ($theme_plugins as $plugin) {
            $Version = $plugin->versions()->orderBy('version', 'desc')->first();
            if (!array_key_exists($plugin->slug, $filter_plugins)) {
                $filter_plugins[$plugin->slug] = [
                    'plugin'  => $Version->s3_url(),
                    'slug'    => $plugin->slug,
                    'version' => $Version->version
                ];
            }
        }

        return $filter_plugins;
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($site) {

            // Purchase Godaddy Domain
            if($site->domain_type == 'purchase_request'){
                (new GoDaddyService())->purchaseDomain($site);
            }

            $site->instance_name =  Str::slug($site->title ." ". $site->id, '-');
            $site->static_ip_name =  "StaticIp-". $site->id;
            $site->save();

            (new LightsailService(auth()->user()->aws_account_id))->createStaticIp($site->static_ip_name);

            (new LightsailService(auth()->user()->aws_account_id))->createInstance($site);

            //Mail::to(auth()->user())->send(new UserWithsite($site, auth()->user()));
        });

        static::updated(function ($site) {
            Log::alert($site->status);
        });

        static::deleted(function ($site) {
            $user = User::find($site->user_id);
            (new LightsailService($user->aws_account_id))->deleteInstance($site);
            (new LightsailService($user->aws_account_id))->deleteStaticIp($site);
            (new GoDaddyService())->deleteDnsRecords($site);
            switch ($site->platform){
                case 'Stripe' :
                    (new StripeService())->cancelSubscription($site->user->stripe_id, $site->subscription_id);
                    break;
                case 'PayPal' :
                    (new PaypalService())->cancelSubscription($site->subscription_id, 'Site Deleted');
                    break;
                case 'Amazon' :
                    (new AmazonPayService())->cancelSubscription($site->subscription_id, 'Site Deleted');
                    break;
                default:
            }

        });
    }

    public function getStatusClassAttribute()
    {
        $classes = [
            'queued'    => 'info',
            'building'  => 'info',
            'failed'    => 'danger',
            'completed' => 'primary',
            'deleted'   => 'danger',
            'running'   => 'primary'
        ];

        return $classes[$this->status];
    }

}
