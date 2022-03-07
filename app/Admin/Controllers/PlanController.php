<?php

namespace App\Admin\Controllers;

use App\Models\Bundel;
use App\Models\Bundle;
use App\Models\PaymentPlatform;
use App\Models\Plan;
use App\Models\PlanSpec;
use App\Resolver\PaymentPlatformResolver;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use Illuminate\Support\Facades\Storage;
use Stripe;

class PlanController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Plan';


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

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Plan());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('description', __('Description'));
        $grid->column('duration', __('Duration'));
        $grid->column('price', __('Price'));
        // $grid->column('discount_percentage', __('Discount percentage'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Plan::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('description', __('Description'));
        $show->field('duration', __('Duration'));
        $show->field('price', __('Price'));
        // $show->field('discount_percentage', __('Discount percentage'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $bundles = Bundle::all()->map(function ($bundel) {
            return [
                "Price : " . $bundel['price']
                . ", cpuCount : " . $bundel['cpuCount']
                . ", diskSizeInGb : " . $bundel['diskSizeInGb']
                . ", bundleId : " . $bundel['bundleId']
                . ", instanceType : " . $bundel['instanceType']
                . ", isActive : " . $bundel['isActive']
                . ", name : "  . $bundel['name']
                . ", power : "  . $bundel['power']
                . ", ramSizeInGb : "  . $bundel['ramSizeInGb']
                . ", transferPerMonthInGb : ". $bundel['transferPerMonthInGb'], $bundel["id"]];
        });
        $bundles = collect($bundles)->pluck(0, 1);

        $form = new Form(new Plan());
        $form->hidden('id');
        $form->select('bundle_id', 'EC2 Instance Type')->options($bundles);

        $form->text('name', __('Name'))->required();
        $form->text('color', __('Color class'));
        $form->textarea('description', __('Description'));
        if (substr(trim(request()->path(), '/'), -5) === '/edit') {
            $form->select('duration', __('Duration'))->options(['day' => 'Day', 'week' => 'Week', 'month' => 'Month', 'year' => 'Yearly'])->disable();
            $form->hidden('duration');
            $form->number('duration_count', __('Duration Count'))->disable();
            $form->hidden('duration_count');
            $form->currency('price', __('Price'))->disable();
            $form->hidden('price');
            $form->number('discount_percentage', __('Discount percentage'))->disable();
            $form->hidden('discount_percentage');
        } else {
            $form->select('duration', __('Duration'))->required()->options(['day' => 'Day', 'week' => 'Week', 'month' => 'Month', 'year' => 'Yearly']);
            $form->number('duration_count', __('Duration Count'));
            $form->currency('price', __('Price'));
            $form->number('discount_percentage', __('Discount percentage'))->default(0);
        }
        $form->hidden('paypal_plan_id');
        $form->hidden('stripe_plan_id');
        $form->hidden('amazon_plan_id');
        $form->switch('Status', __('Active'))->default(1);
        $form->number('download_limit', __('Download limit'))->default(0);
        $form->divider();
        $form->hasMany('specs', function (Form\NestedForm $form) {
            $form->select('plan_spec_id', __('Feature'))->options(PlanSpec::all()->pluck('name', 'id'));
            $form->text('value', __('Value'));
        });
        $form->saved(function (Form $form) {
            $price = $form->model()->toArray()["price"];
            if ($form->model()->toArray()["discount_percentage"]) {
                $discount = ($price * $form->discount_percentage) / 100;
                $price    -= $discount;
                //$price    = round($price, 2);
                $form->price_after_discount = round($price, 2);
                Plan::find($form->model()->toArray()["id"])->update(['price_after_discount' => round($price, 2)]);
            }

            foreach (PaymentPlatform::all() as $paymentPlatform) {
                if ($paymentPlatform->name != 'Amazon') {
                    $Platform = $this->paymentPlatformResolver->resolveServices($paymentPlatform->name);
                    $plan     = [
                        'plan_id'               => $form->model()->toArray()["id"],
                        'name'                  => $form->model()->toArray()["name"],
                        'amount'                => $price,
                        'currency'              => 'USD',
                        'interval'              => $form->model()->toArray()["duration"],
                        'interval_count'        => $form->model()->toArray()["duration_count"],
                        'statement_description' => $form->model()->toArray()["description"] . ' to services.',
                    ];
                    $Platform->createPlan($plan);
                }
            }
        });
        return $form;
    }
}
