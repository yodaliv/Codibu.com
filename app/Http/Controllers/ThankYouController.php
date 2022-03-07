<?php

namespace App\Http\Controllers;

use App\Repository\SiteCreateRepository;

class ThankYouController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function __invoke()
    {
        if(session()->has('site_create_array.subscription_id')) {
            $site = (new SiteCreateRepository())->createSite();
            $plan = optional($site)->plan;
            $demo = optional($site)->demo;
            $lastPaymentHistory = optional($site)->lastPaymentHistory;
            $data = [
                'success'          => 'Order has been created successfully. It will take 15 minutes to complete setup',
                'site'        => $site,
                'live_demo_name'   => $demo,
                'specification'    => $plan,
                'transaction_data' => $lastPaymentHistory
            ];

            session()->forget('site_create_array');
            session()->forget('plan_name');
            session()->forget('plan_price');

            return view('sites.thank-you', $data);
        } else {
            return abort(404);
        }

    }
}
