<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Plan;
use App\Models\Site;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    /**
     * check the coupon
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkCoupon(Request $request): \Illuminate\Http\JsonResponse
    {
        $data = checkCoupon($request->all());
        $data["price"] = $data["price"]."$";
        $data["discount"] = $data["discount"]."$";

        return response()->json($data);
    }
}
