<?php

namespace Database\Seeders;

use App\Models\PaymentPlatform;
use Illuminate\Database\Seeder;

class PaymentPlatformSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $payment_platforms = array(
            array('id' => '1', 'name' => 'PayPal', 'image' => 'img/payment-platforms/paypal.jpg', 'subscriptions_enabled' => '1', 'created_at' => '2021-05-07 18:51:56', 'updated_at' => '2021-05-07 18:51:56'),
            array('id' => '2', 'name' => 'Stripe', 'image' => 'img/payment-platforms/stripe.jpg', 'subscriptions_enabled' => '1', 'created_at' => '2021-05-07 18:51:56', 'updated_at' => '2021-05-07 18:51:56'),
            array('id' => '3', 'name' => 'Amazon', 'image' => 'img/payment-platforms/amazon.jpg', 'subscriptions_enabled' => '1', 'created_at' => '2021-05-07 18:51:56', 'updated_at' => '2021-05-07 18:51:56')
        );
        PaymentPlatform::insert($payment_platforms);
    }
}
