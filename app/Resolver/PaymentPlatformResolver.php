<?php

namespace App\Resolver;

use App\Models\PaymentPlatform;

class PaymentPlatformResolver
{
    protected $paymentPlatforms;

    public function __construct()
    {
        $this->paymentPlatforms = PaymentPlatform::all();
    }

    public function resolveServices($paymentPlatFormName)
    {
        $name    = strtolower($paymentPlatFormName);
        $service = config("services.{$name}.class");
        if ($service) {
            return resolve($service);
        }
        throw new \Exception('The selected platform is not in the configuration');
    }
}
