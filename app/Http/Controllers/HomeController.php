<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateSiteScore;
use App\Models\Demo;
use App\Models\Faq;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Plugin;
use App\Models\RecentHistory;
use App\Models\Section;
use App\Models\Service;
use App\Models\Site;
use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function dashboard()
    {
        $user = auth()->user();
        $themes = Theme::count();
        $plugins = Plugin::count();
        $demos = Demo::count();
        $directory = $themes + $plugins + $demos;
        $tutorials = Faq::tutorials();
        $plan = Plan::where('discount_percentage', '>', 0)->first();

        $data = [
            'sites' => $this->userSites($user),
            'user_balance' => $user->balance,
            'themes_and_pugins' => ($themes + $plugins),
            'directory' => $directory,
            'tutorials'         => $tutorials,
            'plan' => $plan,
        ];

        return view('dashboard.dashboard', $data);
    }

    /**
     * get auth user sites
     *
     * @param $user
     */
    private function userSites($user)
    {
        $sites = $user->sites->where('server_ip', '!=', null);
        $ipAddresses = $sites->where('server_ip', '!=', null)->pluck('server_ip');
        $check_site_info = RecentHistory::on('mysql2')->whereIn('domain_name', $ipAddresses)
            ->orderByDesc('id')->get();
        $allJobs = DB::table('jobs')->select('payload')->pluck('payload');

        if (count($sites) > 0) {
            foreach ($sites as $site) {
                $is_process = $allJobs->filter(function ($value, $key) use ($site) {
                    return strpos($value, $site->server_ip) !== false;
                })->count();

                $site->demoDetails = $site->demo;
                $site->score = 0;
                $site->global_rank = 0;
                $site->page_speed = 0;
                $site->score_process = !empty($is_process);

                foreach ($check_site_info as $item) {
                    if ($site->server_ip == $item->domain_name) {
                        $response = unserialize(base64_decode($item->other));
                        $site->score = $response[0];
                        $site->global_rank = number_format($response[1]);
                        $site->page_speed = $response[2];
                    }
                }
            }
        }
        return $sites;
    }

    public function faq()
    {

        $faqs = Faq::all();

        return view('faq', compact('faqs'));
    }

    public function notificationMarkAsRead()
    {
        Notification::where('user_id', Auth::id())->where('seen', 0)->update(['seen' => 1]);
        return response()->json('Successfully Notification Mark As Read');
    }

    /*
    Uses - ClearNotification() used for clear all notifications
    Author - RBH NArola
     */
    public function ClearNotification()
    {
        $result = Notification::where('user_id', Auth::id())->where('seen', 1)->update(['is_clear' => 1]);
        if ($result) {
            return response()->json(array('status' => true, 'message' => 'Successfully Notification clear All'));
        } else {
            return response()->json(array('status' => false, 'message' => 'Successfully Notification clear All'));
        }

    }

    /**
     * dispatch job
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function generateScore(Request $request): \Illuminate\Http\JsonResponse
    {
        $this->dispatch(new GenerateSiteScore($request->server_ip));
        return response()->json('job dispatched');
    }

    /**
     * @return mixed
     */
    public function checkScoreProcess()
    {
        $authUser = auth()->user();
        return $this->userSites($authUser);
    }

    /**
     * return score details page
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function scoreDetails()
    {
        return view('dashboard.score_details');
    }

}
