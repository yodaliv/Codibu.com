<?php

namespace App\Http\Controllers;

use Amazon\Pay\API\Client;
use App\Models\Plan;
use App\Models\Site;
use App\Models\Subscription;
use App\Resolver\PaymentPlatformResolver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AmazonPayController extends Controller
{
    /**
     * @var PaymentPlatformResolver
     */
    protected $paymentPlatformResolver;

    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed
     */
    protected $paymentPlatformAmazon;

    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
        $this->paymentPlatformAmazon   = $this->paymentPlatformResolver->resolveServices('Amazon');
    }

    public function merchantReviewPage()
    {
        $checkoutSessionId = request('amazonCheckoutSessionId');
        session()->put('amazonCheckoutSessionId', $checkoutSessionId);
        if (session()->exists('site_create_array')) {
            $site = session()->get('site_create_array');
            $plan = Plan::findOrFail($site['plan_id']);
            return view('amazon.paywithamazonpay', compact('site', 'plan'));
        } else {
            return redirect()->back()->with('error', 'Site data not found! please try again');
        }
    }

    public function paymentUpdate()
    {
        $site = session()->get('site_create_array');
        $plan = Plan::findOrFail($site['plan_id']);
        if (session()->exists('amazonCheckoutSessionId')) {
            $checkoutSessionId = session()->get('amazonCheckoutSessionId');
            try {
                $result = $this->paymentPlatformAmazon->getCheckoutSession($checkoutSessionId);
                if ($result['status'] === 200) {
                    return redirect($this->paymentPlatformAmazon->updateCheckoutSession($checkoutSessionId, $plan));
                } else {
                    // check the error
                    echo 'status=' . $result['status'] . '; response=' . $result['response'] . "\n";
                }

                return view('merchant-review-page');
            } catch (\Exception $e) {
                // handle the exception
                echo $e . "\n";
            }
        }
        return redirect($this->paymentPlatformAmazon->updateCheckoutSession($checkoutSessionId, $plan ));
    }

    public function merchantConfirmPage()
    {
        $array = session()->get('site_create_array');
        $array['subscription_id'] = request('amazonCheckoutSessionId');
        session()->put('site_create_array', $array);

        return redirect()->route('thankYou');
    }
}
