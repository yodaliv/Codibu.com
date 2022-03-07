<?php

namespace App\Models;

use App\Services\StripeService;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    /**
     * get coupons availability name
     *
     * @param $coupon_availability
     * @return string
     */
    public function getCouponAvailabilityName($coupon_availability): string
    {
        switch ($coupon_availability) {
            case "1":
                $value = "1-time use only";
                break;
            case "2":
                $value = "1-time use per account";
                break;
            case "3":
                $value = "unlimited";
        }
        return $value;
    }
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::created(function ($coupon) {
            (new StripeService())->createCoupon($coupon);
        });
    }
}
