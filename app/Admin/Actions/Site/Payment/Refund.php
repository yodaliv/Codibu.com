<?php

namespace App\Admin\Actions\Site\Payment;

use App\Services\AmazonPayService;
use App\Services\PaypalService;
use App\Services\StripeService;
use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;
use Cartalyst\Stripe\Laravel\Facades\Stripe;
class Refund extends RowAction
{
    public $name = 'Refund';

    public function handle(Model $model)
    {
        if ($model->payment_platform == 'Stripe'){
            (new StripeService())->createRefund($model->charge);
        } elseif ($model->payment_platform == 'Amazon') {
            $sdf = (new AmazonPayService())->createRefund($model->charge, $model->payment_amount - $model->refund_amount);
            return $this->response()->success(json_encode($sdf))->refresh();
        } else {
            (new PaypalService())->createRefund(false, $model->payment_amount - $model->refund_amount, $model->site_id);
        }

        $model->refund_amount += $model->payment_amount;
        $model->save();
        return $this->response()->success('Success message.')->refresh();
    }

    public function dialog()
    {
        $this->confirm('Are you sure to copy this row?');
    }

}
