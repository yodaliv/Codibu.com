<?php

namespace App\Http\Controllers;

use App\Services\StripeService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Stripe;

class StripeController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|\Illuminate\Http\Response|View
     */
    public function index()
    {
        $methods = Auth::user()->payment_methods;
        return view('user.stripe-card.list', compact('methods'));
    }

    /**
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('user.stripe-card.create');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $paymentMethods = [];
        if (auth()->user()->stripe_id) {
            $paymentMethods = Stripe::paymentMethods()->all([
                'type'     => 'card',
                'customer' => auth()->user()->stripe_id,
            ]);
            $paymentMethods = array_map(function ($val) {
                return $val['id'];
            }, $paymentMethods['data']);
        }
        if (!in_array($request->paymentMethodId, $paymentMethods)) {
            (new StripeService)->attachPaymentMethodToCustomar($request->paymentMethodId);
            \Session::flash('success', 'Payment method added successfully.');
            return response()->json(['response' => 'Payment method added.', 'status' => true], 200);
        } else {
            \Session::flash('error', 'Payment method already exists in your account.');
            return response()->json(['response' => 'Payment method already exists in your account.', 'status' => true], 200);
        }
    }

    public function destroy($paymentMethodID)
    {
        Stripe::paymentMethods()->detach($paymentMethodID);
        return redirect()->back()->with('success', 'Payment method deleted successfully.');
    }
}
