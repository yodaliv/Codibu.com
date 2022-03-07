<?php

namespace App\Admin\Actions\Site\Payment;

use App\Services\AmazonPayService;
use App\Services\PaypalService;
use App\Services\StripeService;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PartialRefund extends RowAction
{
    public $name = 'Partial Refund';

    public function handle(Model $model, Request $request)
    {
        if ($model->payment_platform == 'Stripe') {
            (new StripeService())->createRefund($model->charge, $request->get('amount'));
        } elseif ($model->payment_platform == 'Amazon') {
            (new AmazonPayService())->createRefund($model->charge, $request->get('amount'));
        } else {
            (new PaypalService())->createRefund(false, $request->get('amount'), $model->site_id);
        }

        $model->refund_amount += $request->get('amount');
        $model->save();
        return $this->response()->success('Success message.')->refresh();
    }

    public function form()
    {
        $this->text('amount', 'Amount')->rules('required');
    }
}
