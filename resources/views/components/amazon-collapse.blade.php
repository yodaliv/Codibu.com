<small>You will be redirected to the Amazon checkout page.</small>
<div id="AmazonPayButton"></div>
<script src="https://static-na.payments-amazon.com/checkout.js"></script>
<script type="text/javascript" charset="utf-8">
    amazon.Pay.renderButton('#AmazonPayButton', {
        type: 'expressPaymentButton',
        // set checkout environment
        merchantId: '{{config("services.amazon.merchant_id")}}',
        publicKeyId: '{{config("services.amazon.public_key_id")}}',
        ledgerCurrency: 'USD',
        // customize the buyer experience
        checkoutLanguage: 'en_US',
        productType: 'PayAndShip',
        placement: 'Cart',
        buttonColor: 'Gold',
        // configure Create Checkout Session request
        createCheckoutSessionConfig: {
            payloadJSON: @json($payload),
            signature:  "{{ $signature }}"
        }
    });
</script>
