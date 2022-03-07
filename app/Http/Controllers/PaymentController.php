<?php

namespace App\Http\Controllers;

use App\Models\PaymentPlatform;
use App\Models\Site;
use App\Resolver\PaymentPlatformResolver;
use App\Services\AmazonPayService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * @var $paymentPlatformResolver ;
     */
    protected $paymentPlatformResolver;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(PaymentPlatformResolver $paymentPlatformResolver)
    {
        $this->paymentPlatformResolver = $paymentPlatformResolver;
    }

    public function renewPayment()
    {
        if (request('site_id')) {
            $site         = Site::findOrFail(request('site_id'));
            $data['site'] = $site;
            session()->put('site_id', $site->id);
            session()->forget('site_renew_id');
            session()->put('site_renew_id', $site->id);

            //Amazon pay start
            $createCheckoutSession = (new AmazonPayService)->createCheckoutSession();
            $data['payload']       = $createCheckoutSession['payload'];
            $data['signature']     = $createCheckoutSession['signature'];
            //Amazon pay end

            $data['paymentPlatforms'] = PaymentPlatform::all();
            $data['payment_methods']  = !empty(auth()->user()->payment_methods)
                ? auth()->user()->payment_methods
                : false;
            return view('sites.payment-renew', $data);
        }
    }

    /**
     * Site payment renew submit
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     * @throws ValidationException
     */
    public function submitPayment(Request $request)
    {
        $this->validate($request, [
            'payment_platform' => 'required',
            'site_id'          => 'required'
        ]);
        $site                     = Site::findOrFail($request->site_id);
        $request_payment_platform = $request->payment_platform;
        session()->forget('payment_amount');
        session()->forget('site_renew_id');

        session()->put('payment_amount', $site->total_price);
        session()->put('site_renew_id', $site->id);

        $paymentPlatform = $request_payment_platform != 'Amazon'
            ? $this->paymentPlatformResolver->resolveServices($request_payment_platform)
            : '';
        if ($request_payment_platform == "PayPal") {
            return redirect($paymentPlatform->createOrder());
        } elseif ($request_payment_platform == "Stripe") {
            $paymentPlatform->handleSubscriptionRenew($site, $request->payment_method);
        }
        return redirect()->route('sites.index')->with('success', 'Site Payment renew successfully.');
    }
}
