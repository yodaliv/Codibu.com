<?php

namespace App\Http\Controllers;

use App\Services\PaypalService;
use Illuminate\Http\Request;

class PaypalController extends Controller
{
    public function completeSubscription()
    {
        return redirect()->route('thankYou');
   }
}
