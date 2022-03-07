<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Plan;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PricingController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return Application|Factory|Response|View
     */
    public function __invoke(Request $request)
    {
        $plans = Plan::with('specs')->get();
        $faqs = Faq::get();
        return view('frontend.pages.pricing')->with(['plans' => $plans, 'faqs' => $faqs]);
    }
}
