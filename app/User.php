<?php

namespace App;

use App\Models\Plan;
use App\Services\AwsService;
use App\Services\CpanelApi;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Site;
use App\Models\PaymentHistory;
use App\Models\Notification;
use Stripe;
use App\Models\Ticket;
use App\Models\Comment;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at',
        'remember_token',
        'email_verified_at',
        'two_factor_code',
        'two_factor_expires_at',
        'provider',
        'provider_id',
        'stripe_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'email_verified_at',
        'two_factor_expires_at',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class, 'user_id');
    }
    public function payment_history(): HasMany
    {
        return $this->hasMany(PaymentHistory::class, 'user_id');
    }
    public function latestSite(): HasOne
    {
        return $this->hasOne(Site::class, 'user_id')->latest();
    }
    public function info(): HasOne
    {
        return $this->hasOne(UserInfo::class, 'user_id');
    }
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function getSlugAttribute(): string
    {
        $name = explode(' ', $this->name);
        return $name[0][0] . (isset($name[1])
                ? $name[1][0]
                : '');
    }

    public function getPaymentMethodsAttribute(): array
    {

        $methods = array();

        if ($this->stripe_id != null) {
            $paymentMethods = Stripe::paymentMethods()->all([
                'type'     => 'card',
                'customer' => $this->stripe_id,
            ]);
            foreach ($paymentMethods['data'] as $method) {
                array_push($methods, [
                    'id'        => $method['id'],
                    'brand'     => $method['card']['brand'],
                    'last_four' => $method['card']['last4'],
                    'exp_month' => $method['card']['exp_month'],
                    'exp_year'  => $method['card']['exp_year'],
                    'holder'    => $method['billing_details']['name']
                ]);
            }
        } else {
            $methods = [];
        }

        return $methods;
    }

    public function generateTwoFactorCode()
    {
        $this->timestamps            = false;
        $this->two_factor_code       = rand(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();
    }

    public function resetTwoFactorCode()
    {
        $this->timestamps            = false;
        $this->two_factor_code       = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }

    /**
     * A user can have many tickets
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * A user can have many comments
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the user that created ticket
     * @param User $user_id
     */
    public static function getTicketOwner($user_id)
    {
        return static::where('id', $user_id)->firstOrFail();
    }

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'sites', 'user_id', 'plan_id');
    }
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($user) {
            (new AwsService())->createAccount($user);
            (new CpanelApi())->createEmail($user);
        });
    }
}

