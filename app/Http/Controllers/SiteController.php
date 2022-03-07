<?php

namespace App\Http\Controllers;

use App\Services\LightsailService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Coupon;
use App\Models\PaymentPlatform;
use App\Models\SiteType;
use App\Models\Theme;
use App\Models\Plan;
use App\Models\Site;
use App\Models\Demo;
use App\Models\PageEditor;
use App\Services\GoDaddyService;
use App\Services\ThemeFilter;
use App\Resolver\PaymentPlatformResolver;
use Illuminate\View\View;

class SiteController extends Controller
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


    public function index()
    {
        $sites = auth()->user()->sites()->orderBy('id', 'desc')->paginate(15);
        return view('sites.index', compact('sites'));
    }


    public function show($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('id', (int)$id)->first();
        if (!$site) {
            abort(404);
        }

        return view('sites.single', compact('user', 'site'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|Response|View
     */
    public function create(Request $request)
    {
        $data = [
            'site_types'       => SiteType::pluck('name', 'id'),
            'plans'            => Plan::with('specs')->get(),
            'payment_methods'  => false,
            'paymentPlatforms' => PaymentPlatform::all(),
            'intent'           => false,
            'demo_names'       => Demo::pluck('name', 'id'),
            'editor_types'     => PageEditor::pluck('name', 'id')
        ];
        $user = Auth::user();
        //Amazon pay start
        $paymentPlatformAmazon = $this->paymentPlatformResolver->resolveServices('Amazon');
        $createCheckoutSession = $paymentPlatformAmazon->createCheckoutSession();
        $data['payload']       = $createCheckoutSession['payload'];
        $data['signature']     = $createCheckoutSession['signature'];
        //Amazon pay end
        if ($user)
            $data['payment_methods'] = $user->payment_methods;

        //theme filtering from services class
        $data['themes'] = (new ThemeFilter())->filteringThemes($request);
        return view('sites.create', $data);
    }


    /**
     * Store the purchase request for a website
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required',
            'password'    => 'required',
            'domain'      => 'required|unique:sites,domain',
            //'email'       => 'unique:users,email',
            'domain_type' => ['required', function ($attribute, $value, $fail) {
                if (!isset(\auth()->user()->aws_account_id)) {
                    return $fail('Your account is still processing please try again later');
                }

                if ($value === 'purchase_request' && (new GoDaddyService())->domainPriceCheck(\request('domain')) > 10) {
                    return $fail('Please choose the domain which price is less then US$10');
                }
            }],
            //'theme_type'  => 'required',
            'plan'        => 'required|exists:plans,id',
        ]);

        $user = Auth::user();

        $plan   = Plan::find($request->input('plan'));
        $coupon = false;
        if ($request->coupon_code) {
            $coupon = Coupon::firstWhere('coupon_code', $request->coupon_code);
            if ($coupon) {
                $data     = checkCoupon([
                    "coupon_code" => $request->coupon_code,
                    "plan_id"     => $request->plan
                ]);
                $data     = json_decode(json_encode($data), true);
                $discount = $data["discount"];
            }
        }
        $price  = $plan->discount_percentage > 0
                ? $plan->price_after_discount
                : $plan->price;

        $site                 = new Site;
        $site->title          = $request->input('title');
        $site->slug           = Theme::slugify($site->title, '_');
        $site->admin_password = $request->input('password');
        $site->db_pass        = Str::random(20);
        $site->domain         = $request->input('domain');
        $site->domain_type    = $request->input('domain_type');
        $site->domain_price    = $request->input('domain_price');
        $site->user_id        = $user->id;
        if ($coupon) {
            $site->coupon_code     = $coupon->coupon_code;
            $site->coupon_discount = $discount;
        } else {
            $site->coupon_code     = null;
            $site->coupon_discount = 0;
        }
        $site->total_price = $price;
        $site->demo_id     = $request->input('demo_id');
        $site->plan_id     = $request->input('plan');
        $site->status      = 'building';
        $site->theme_type  = 'Demo'; //$request->input('theme_type');
        $site->platform = $request->payment_platform;

        session()->forget('site_create_array');
        session()->forget('payment_amount');
        session()->forget('paymentmethod');
        session()->forget('site_renew_id');
        session()->forget('plan_name');
        session()->forget('plan_price');

        session()->put('site_create_array', $site->toArray());
        session()->put('plan_name', $plan->name);
        session()->put('plan_price', $plan->price);
        session()->put('plan_price', $plan->price);
        session()->put('payment_amount', $price);
        session()->put('paymentmethod', $request->payment_method);
        // payment start
        if ($request->payment_platform) {
            $paymentPlatform = $request->payment_platform != 'Amazon'
                ? $this->paymentPlatformResolver->resolveServices($request->payment_platform)
                : '';
            $paypal_url      = '';
            if ($request->payment_platform == "PayPal"){
                $paypal_url =  $paymentPlatform->handleSubscription($request);
            } elseif($request->payment_platform == "Stripe"){
                $paymentPlatform->handleSubscription($request);
            } else {
                session()->put('plan_id', $request->input('plan'));
            }
        } else {
            return ['errors' => ['payment' => 'Payment method not provided.']];
        }
        //payment end
        $data = [
            'status'           => true,
            'paypal_url'       => $paypal_url,
            'payment_platform' => $request->payment_platform
        ];
        return json_encode($data);
    }

    public function resetForm()
    {
        session()->forget('site_create_array');
        session()->forget('payment_amount');
        session()->forget('paymentmethod');
        session()->forget('site_renew_id');
        session()->forget('plan_name');
        session()->forget('plan_price');
        return 0;
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $site = $user->sites()->where('id', (int)$id)->first();
        if (!$site || empty($site->instance_name)) {
            return redirect()->back()->with('error', 'You don\'t have access over this stack.');
        }
        (new LightsailService($user->aws_account_id))->stopInstance($site);
        //2022.03.03 add
        $body = [
            "renewAuto" => false
        ];
        $domain = $site->domain;
        (new GoDaddyService())->domainUpdate($domain, $body);
        //end
        return redirect()->back()->with('success', 'Website termination request has been sent.');

    }

    public function restartSite(Request $request)
    {
        $user = Auth::user();
        $site = $user->sites()->where('id', (int)$request->site_id)->first();
        if (!$site || empty($site->instance_name)) {
            return redirect()->back()->with('error', 'You don\'t have access over this stack.');
        }

        (new LightsailService($user->aws_account_id))->startInstance($site);
        //2022.03.03 add
        $body = [
            "renewAuto" => true
        ];
        $domain = $site->domain;
        //end
        (new GoDaddyService())->domainUpdate($domain, $body);
        return redirect()->back()->with('success', 'Website restart request has been sent.');

    }

    public function analysisDetails(Site $site)
    {
        return view('sites.analysis.index', compact('site'));
    }
}
