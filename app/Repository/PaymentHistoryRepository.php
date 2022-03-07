<?php

namespace App\Repository;

use App\Models\PaymentHistory;
use App\Models\Site;
use App\Services\AmazonPayService;
use App\Services\PaypalService;
use App\Services\StripeService;
use Carbon\Carbon;

class PaymentHistoryRepository
{
    /**
     * Payment history creating
     *
     * @param Site $site
     * @param $paymentIntent
     * @param $payment_platform
     * @return \App\Models\PaymentHistory
     */
    public function createPaymentHistory(Site $site): PaymentHistory
    {
        if ($site->platform == "Stripe"){
            $charge = (new StripeService())->getInvoice((new StripeService())->getSubscription($site)['latest_invoice'])['charge'];
        } elseif ($site->platform == "Amazon"){
            $makePayment = (new AmazonPayService())->completeCheckoutSession($site);
            $charge = !empty($makePayment) ? json_decode($makePayment['response'], true)["chargeId"] : $site->paymentHistories[0]->charge;
            if(count($site->paymentHistories) < 1 && $site->coupon_discount) {
                $site->total_price = $site->total_price - $site->coupon_discount;
            }
        } else {
            $charge = (new PaypalService())->getTransactions($site->subscription_id)['id'];
            if(count($site->paymentHistories) < 1 && $site->coupon_discount){
                (new PaypalService())->createRefund(true, $site->coupon_discount, $site->id);
                $site->total_price = $site->total_price - $site->coupon_discount;
            }
        }
        $payment_history = PaymentHistory::firstOrCreate(
            [
                'payment_platform' => $site->platform,
                'charge' => $charge
            ],
            [
                'user_id' => $site->user_id,
                'plan_id' => $site->plan_id,
                'site_id' => $site->id,
                'payment_amount' => $site->total_price,
                'refund_amount' => $site->coupon_discount
            ]
        );
        session()->forget('payment_amount');
        session()->forget('paymentmethod');
        session()->forget('site_renew_id');
        return $payment_history;

    }

}
