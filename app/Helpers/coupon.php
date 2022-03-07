<?php


use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

if (!function_exists('calculateDiscountAmount')) {

    function checkCoupon($request)
    {
        $coupon_code     = $request['coupon_code'];
        $plan            = Plan::find($request["plan_id"]);
        $price           = $plan->discount_percentage > 0
            ? $plan->price_after_discount
            : $plan->price;
        $is_match_coupon = Coupon::where('coupon_code', $coupon_code)->first();
        if (empty($is_match_coupon)) {
            return ['errors' => 'Coupon does not match!!!', 'price' => $price, 'discount' => '0'];
        }

        $expiry_date = $is_match_coupon->expire_date;
        $discount    = $is_match_coupon->discount;
        $condition    = $is_match_coupon->condition_discount;

        if (Carbon::now()->toDateString() > $expiry_date) {
            return ['errors' => 'Coupon is expired!!', 'price' => $price, 'discount' => '0'];
        }

        $coupon_availability = $is_match_coupon->coupon_availability;
        if ($coupon_availability == '1') { //one_time_use_coupon
            $one_time_use_coupon = Site::where('coupon_code', $coupon_code)->first();
            if (empty($one_time_use_coupon)) {
                return calculateDiscountAmount($discount, $condition, $price);
            } else {
                return ['errors' => 'Coupon already used..', 'price' => $price, 'discount' => '0'];
            }
        } elseif ($coupon_availability == '2') { //per_account_coupon
            $per_account_coupon = Site::where('user_id', \auth()->id())->where('coupon_code', $coupon_code)->first();
            if (empty($per_account_coupon)) {
                return calculateDiscountAmount($discount, $condition, $price);
            } else {
                return ['errors' => 'Coupon not available.', 'price' => $price, 'discount' => '0'];
            }
        } else {
            return calculateDiscountAmount($discount, $condition, $price);
        }
    }
}
if (!function_exists('calculateDiscountAmount')) {

    function calculateDiscountAmount($discount, $condition, $price)
    {
        if ($condition == 1) {
            $discount = (($price * $discount) / 100);
            $grand_price = $price - number_format($discount, 2);

        } else {
            $grand_price = $price - $discount;
            $grand_price = number_format($grand_price, 2);
        }
        return ['status' => true, 'price' => $grand_price, 'discount' => $discount, 'success'=>'Coupon have matched successfully'];
    }
}
