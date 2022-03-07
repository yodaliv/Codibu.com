<?php

namespace App\Admin\Controllers;

use App\Models\Coupon;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class CouponController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Coupon';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Coupon());

        $grid->column('id', __('Id'));
        $grid->column('coupon_code', __('Coupon Code'));
        $grid->column('coupon_availability', __('Coupon Availability'))->display(function () {
            return $this->getCouponAvailabilityName($this->coupon_availability);
        });
        $grid->column('start_date', __('Start Date'));
        $grid->column('expire_date', __('Expire Date Set'));
        $grid->column('discount', __('Discount'));
        $grid->column('condition_discount', __('Condition Discount'));
        $grid->column('status', __('Status'))->display(function () {
            if ($this->status == "1") {
                return "<span class='btn btn-sm btn-success'>Active</span>";
            } else {
                return "<span class='btn btn-sm btn-danger'>Inactive</span>";
            }
        });
        $grid->column('created_at', __('Created at'));

        $grid->export(function ($export) {
            $export->filename('coupons');
            $export->only(['id', 'coupon_code', 'coupon_availability', 'start_date', 'expire_date', 'discount', 'condition_discount', 'status']);
        });

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
        $show = new Show(Coupon::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('coupon_code', __('Coupon Code'));
        $show->field('coupon_availability', __('Coupon Availability'))->as(function () {
            return $this->getCouponAvailabilityName($this->coupon_availability);
        });
        $show->field('start_date', __('Start Date'));
        $show->field('expire_date', __('Expire Date'));
        $show->field('discount', __('Discount'));
        $show->field('condition_discount', __('Condition Discount'));
        $show->field('status', __('Status'))->as(function () {
            if ($this->status == "1") {
                return "Active";
            } else {
                return "Inactive";
            }
        });
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
        $form = new Form(new Coupon());
        $form->text('coupon_code')
            ->creationRules(['required', "unique:coupons"])
            ->updateRules(['required', "unique:coupons,coupon_code,{{id}}"]);
        $form->select('coupon_availability', __('Coupon Availability'))->options(['1' => '1-time use only', '2' => '1-time use per account', '3' => 'unlimited'])->rules('required');
        $form->date('start_date', __('Start Date'))->rules('required|after:yesterday');
        $form->date('expire_date', __('Expire Date Set'))->rules('required|after:start_date');
        $form->radio('condition_discount', __('Condition Discount'))->options(['1' => '% Discount', '2' => 'Fixed Amount Discount'])->default('1')->stacked()->rules('required');
        $form->number('discount', __('Discount'))->rules('required');
        $form->select('status', __('Status'))->options(['1' => 'Active', '2' => 'Inactive'])->rules('required');
        return $form;
    }

    public function generateRandomString($length = 20)
    {
        $characters       = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
