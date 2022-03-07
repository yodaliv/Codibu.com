<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Site;
use App\Models\Subscription;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe;

class StripeService
{
    public function createCustomer()
    {
        $stripe_id = auth()->user()->stripe_id;
        if (!auth()->user()->stripe_id) {
            $customer = Stripe::customers()->create([
                'email' => auth()->user()->email,
                'name'  => auth()->user()->name
            ]);
            User::find(auth()->id())->update(['stripe_id' => $customer["id"]]);
            $stripe_id = $customer["id"];
        }
        return $stripe_id;
    }

    public function createIntent()
    {
        if (session()->exists('payment_amount')) {
            $intent = Stripe::paymentIntents()->create([
                'amount'               => session()->get('payment_amount'),
                'currency'             => 'usd',
                'customer'             => auth()->user()->stripe_id,
                'capture_method'       => 'manual',
                'description'          => 'You create a website from codibu',
                'payment_method_types' => ['card'],
            ]);
            return $intent["id"];
        }
    }

    public function attachPaymentMethodToCustomar($paymentMethodId)
    {
        $customerId = $this->createCustomer();
        Stripe::paymentMethods()->attach($paymentMethodId, $customerId);
    }


    public function createPlan($planRequest)
    {
        $planRequest = (object) $planRequest;
        $plan = Plan::find($planRequest->plan_id);
        if (!$plan->stripe_plan_id) {
            $stripe = Stripe::prices()->create([
                'unit_amount' => $planRequest->amount*100,
                'currency' => $planRequest->currency,
                'recurring' => [
                    'interval' => $planRequest->interval,
                    'interval_count' => $planRequest->interval_count
                ],
                'product' => config('services.stripe.product_id'),
            ]);
            $stripe = (object) $stripe;
            $plan->update(['stripe_plan_id' => $stripe->id]);
        }
    }

    public function handleSubscription(Request $request)
    {
        $plan = Plan::find($request->plan);
        $subscription = Stripe::subscriptions()->create( Auth::user()->stripe_id, [
            'plan' => $plan->stripe_plan_id,
            'coupon' => $request->coupon_code,
            'default_payment_method' => $request->payment_method
        ]);
        $array = session()->get('site_create_array');
        $array['subscription_id'] = $subscription['id'];
        session()->put('site_create_array', $array);
        return $subscription;
    }

    public function getSubscription(Site $site)
    {
        return Stripe::subscriptions()->reactivate(User::find($site->user_id)->stripe_id, $site->subscription_id);
    }

    public function getInvoice($invoice)
    {
        return Stripe::invoices()->find($invoice);
    }

    public function createRefund($charge_id, $amount = false)
    {
        if ($amount){
            return Stripe::refunds()->create($charge_id,$amount);
        } else {
            return Stripe::refunds()->create($charge_id);
        }
    }

    public function createCoupon($coupon)
    {
        $off = $coupon->condition_discount == 1 ? 'percent_off' : 'amount_off';
        $discount = $coupon->condition_discount == 1 ? $coupon->discount : $coupon->discount*100;

        return Stripe::coupons()->create([
            'id'          => $coupon->coupon_code,
            'duration'    => 'once',
            $off => $discount,
            'currency'    => 'USD'
        ]);
    }

    public function cancelSubscription($customerId, $subscriptionId)
    {
        return Stripe::subscriptions()->cancel($customerId, $subscriptionId);
    }

    /*public function createProduct($customerId, $subscriptionId)
    {
        return Stripe::subscriptions()->cancel($customerId, $subscriptionId);
    }*/



}
