<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Plan;
use App\Models\Section;
use App\Models\Service;
use App\Models\SiteType;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $services = Service::all();
        $faqs     = Faq::query()->orderBy('order')->get();
        $testimonials = Testimonial::all();
        $themes   = SiteType::query()
            ->with(['demos'=>function($q){ $q->orderByRaw('RAND()'); }])
            ->get()
            ->map(function ($siteType) {
                return array_merge($siteType->toArray(), ['demos' => $siteType->demos->take(10)->toArray()]);
            });
        //2022.03.02 added
        $plans = Plan::with('specs')->get();    
        $sections = Section::getByNames(['hero'])->toArray();
        return view('frontend.pages.index', compact('faqs', 'themes', 'sections', 'services','testimonials','plans'));
    }
}
