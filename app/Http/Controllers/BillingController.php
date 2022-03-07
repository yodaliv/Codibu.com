<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BillingController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Http\Response|View
     */
    public function index()
    {
        $histories = auth()->user()->payment_history()->orderBy('id', 'desc')->paginate(15);
        return view('billing.index',compact('histories'));
    }
}
