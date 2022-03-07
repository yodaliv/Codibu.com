<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Site;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalService
{
    protected $client;

    public function __construct()
    {
        $this->client = new PayPalClient;
        $this->client->setApiCredentials(config('paypal'));
        $this->client->getAccessToken();
    }

    public function planDetails($plan_id)
    {
        return $this->client->showPlanDetails($plan_id);
    }
    public function createPlan($planRequest)
    {
        $planRequest = (object) $planRequest;
        $plan = Plan::find($planRequest->plan_id);
        config('services.paypal.product_id');
        if (!$plan->paypal_plan_id) {
            $data = [
              "product_id" => config('services.paypal.product_id'),
              "name" => $planRequest->name,
              "description" => $planRequest->statement_description,
              "status" => "ACTIVE",
              "billing_cycles" => [
                0 => [
                  "frequency" => [
                    "interval_unit" => $planRequest->interval,
                    "interval_count" => $planRequest->interval_count,
                  ],
                  "tenure_type" => "REGULAR",
                  "sequence" => 1,
                  "total_cycles" => 0,
                  "pricing_scheme" =>  [
                    "fixed_price" =>  [
                      "value" => number_format($planRequest->amount),
                      "currency_code" => $planRequest->currency,
                    ]
                  ]
                ]
              ],
              "payment_preferences" =>  [
                "auto_bill_outstanding" => true,
                "setup_fee_failure_action" => "CONTINUE",
                "payment_failure_threshold" => 1
              ]
            ];
            $paypal = $this->client->createPlan($data);
            $paypal = (object) $paypal;
            $plan->update(['paypal_plan_id' => $paypal->id]);
        }
    }

    public function handleSubscription(Request $request)
    {

        $plan = Plan::find($request->plan);
        $array = [
            'plan_id' => $plan->paypal_plan_id,
            'subscriber' => [
                'name' => [
                    'given_name' =>$request->user()->name,
                ],
                'email_address' => $request->user()->email
            ],
            'application_context' => [
                'brand_name' => config('app.name'),
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'SUBSCRIBE_NOW',
                'return_url' => config('services.paypal.return_url'),
                'cancel_url' => config('services.paypal.cancel_url'),
            ]
        ];
        try {
            $subscription = $this->client->createSubscription($array);
            $array = session()->get('site_create_array');
            $array['subscription_id'] = $subscription['id'];
            session()->put('site_create_array', $array);
            $subscriptionLinks = collect($subscription["links"]);
            $approve = $subscriptionLinks->where('rel', 'approve')->first();
            Subscription::create([
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'platform' => 'Paypal',
                'subscription' => json_encode($subscription)
            ]);
            return $approve["href"];
        } catch (\Exception $e){
            dd($e);
        }

    }

    public function createRefund($coupon = false, $amount = 0, $site_id)
    {
        $bearer = $this->client->getAccessToken()["access_token"];
        $transaction = $this->getTransactions(Site::find($site_id)->subscription_id);
        $msg = $coupon == false ? 'Refund your Amount' : 'Cashback for your applied coupon';
        $api =config('paypal.mode') == 'sandbox' ? 'https://api-m.sandbox.paypal.com/v1/payments/payouts' : 'https://api-m.paypal.com/v1/payments/payouts';
        return Http::withToken($bearer)
            ->post($api, [
                    "sender_batch_header" => [
                        "sender_batch_id" => "PO-" . date('my') . substr(uniqid(), 0, 20),
                        "email_subject" => "You have a payout!",
                        "email_message" => "You have received a payout! Thanks for using our service!"
                    ],
                    "items" => [
                        [
                            "recipient_type" => "EMAIL",
                            "amount" => [
                                "value" => $amount,
                                "currency" => "USD"
                            ],
                            "note" => $msg,
                            "sender_item_id" => "PO-" . date('my') . substr(uniqid(), 0, 20),
                            "receiver" => $transaction['payer_email'],
                            "alternate_notification_method" => [
                                "phone" => [
                                    "country_code" => "91",
                                    "national_number" => "9999988888"
                                ]
                            ],
                            "notification_language" => "en-US"
                        ],
                    ],
                ]
            );
    }

    public function getTransactions($subscription_id)
    {
        try {
            $transactions = $this->client->listSubscriptionTransactions(
                $subscription_id,
                now()->subDays(10),
                now()
            );
            return collect($transactions['transactions'])->last();
        } catch (\Exception $e){
            dd($e);
        }
    }

    public function cancelSubscription($subscription_id, $reason){
        return $this->client->cancelSubscription($subscription_id, $reason);
    }

    /*public function createProduct()
    {
        $array = [
            "name" => "Codibu",
            "description" => "Website management service",
            "type" => "SERVICE",
            "category" => "SOFTWARE",
            "image_url" => "https://codibu.com/images/logo.svg",
            "home_url" => "https://codibu.com/"
        ];
        return $this->client->createProduct($array, 'CODIBU-18062020-001');
    }*/
}
