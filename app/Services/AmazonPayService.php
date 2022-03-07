<?php

namespace App\Services;

use Amazon\Pay\API\Client;
use App\Models\Plan;
use App\Models\Site;
use Carbon\Carbon;

class AmazonPayService
{
    protected $config;
    protected $client;

    public function __construct()
    {
        $this->config = array(
            'public_key_id' => config('services.amazon.public_key_id'),
            'private_key'   => storage_path(config('services.amazon.private_key')),
            'region'        => config('services.amazon.region'),
            'sandbox'       => config('services.amazon.sandbox')
        );
        $this->client = new Client($this->config);
    }

    public function getCheckoutSession($checkoutSessionId)
    {
        return $this->client->getCheckoutSession($checkoutSessionId);
    }

    public function completeCheckoutSession(Site $site)
    {
        $checkoutSession = json_decode($this->client->getCheckoutSession($site->subscription_id)['response']);
        $permissionId = $checkoutSession->chargePermissionId;
        $purchaseDate = Carbon::parse($checkoutSession->creationTimestamp);

        $paymentCount = count($site->paymentHistories);
        $duration_count = $paymentCount > 0 ? $site->plan->duration_count * $paymentCount : $site->plan->duration_count;
        $expireDate = calculatePaymentExpireDate($purchaseDate, $site->plan->duration, $duration_count);

        if(count($site->paymentHistories) < 1 || (Carbon::parse($expireDate)->isCurrentDay() && $site->lastPaymentHistory->created_at != $expireDate )){
            $payload = [
                "chargeAmount" => [
                    "amount"       => $site->plan->price,
                    "currencyCode" => "USD"
                ],
            ];
            $headers = array('x-amz-pay-Idempotency-Key' => uniqid());
            if(!empty($permissionId)){
                $payload["chargePermissionId"] = $permissionId;
                $payload["captureNow"] = true;
                return $this->client->createCharge($payload,$headers);
            } else {
                $checkoutNewSession = $this->client->completeCheckoutSession($site->subscription_id, $payload, $headers);
                $charge = json_decode($checkoutNewSession['response'])->chargeId;
                if(count($site->paymentHistories) < 1  && $site->coupon_discount){
                    $this->createRefund($charge, $site->coupon_discount);
                }
                return $checkoutNewSession;
            }
        }

    }


    public function createCheckoutSession()
    {
        $sessionRequestPayload = [
            'webCheckoutDetails' => [
                'checkoutReviewReturnUrl' =>  config('services.amazon.checkoutReviewReturnUrl'),
            ],
            'storeId' => config('services.amazon.storeId'),
            'chargePermissionType' => 'Recurring'
        ];
        $sessionRequestPayload = json_encode($sessionRequestPayload);
        $signature = $this->client->generateButtonSignature($sessionRequestPayload);

        $payload = json_decode($sessionRequestPayload, true);

        return ['payload' =>$payload, 'signature' => $signature];
    }

    public function updateCheckoutSession($checkoutSessionId, Plan $plan)
    {
        $payload = array(
            'webCheckoutDetails' => [
                'checkoutResultReturnUrl' =>  config('services.amazon.checkoutResultReturnUrl')
            ],
            'chargePermissionType' => 'Recurring',
            'recurringMetadata' => [
                'frequency' => [
                    'unit' => ucfirst($plan->duration),
                    'value' => $plan->duration_count,
                ],
                'amount' => [
                    'amount' => $plan->price,
                    'currencyCode' => 'USD',
                ],
            ],
            'paymentDetails' => array(
                'paymentIntent' => 'AuthorizeWithCapture',
                'canHandlePendingAuthorization' => false,
                'chargeAmount' => array(
                    'amount' => $plan->price,
                    'currencyCode' => 'USD'
                ),
            )
        );
        try {

            $result = $this->client->updateCheckoutSession($checkoutSessionId, $payload);
            if ($result['status'] === 200) {
                $response = json_decode($result['response'], true);
                return $amazonPayRedirectUrl = $response['webCheckoutDetails']['amazonPayRedirectUrl'];
            } else {
                echo 'status=' . $result['status'] . '; response=' . $result['response'] . "\n";
            }
        } catch (\Exception $e) {
            echo $e . "\n";
        }
    }

    public function createRefund($charge_id, $amount = 0)
    {
        $payload = array(
            "chargeId"     =>$charge_id,
            "refundAmount" => array(
                "amount" => $amount,
                "currencyCode" =>"USD"
            )
        );
        $headers = array('x-amz-pay-Idempotency-Key' => uniqid());
        return $this->client->createRefund($payload, $headers);
    }

    public function cancelSubscription($charge_id, $reason)
    {
        $payload = array(
            "chargeId"     =>$charge_id,
            "refundAmount" => array(
                "closureReason" => $reason,
                "cancelPendingCharges" => false
            )
        );
        $headers = array('x-amz-pay-Idempotency-Key' => uniqid());
        return $this->client->closeChargePermission($charge_id, $payload, $headers);
    }
}
