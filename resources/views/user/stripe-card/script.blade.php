<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe   = Stripe("{{ config('services.stripe.key') }}");
    const elements = stripe.elements();
    const style    = {
        base   : {
            color: "#32325d",
        },
        invalid: {
            color    : "#fa755a",
            iconColor: "#fa755a"
        }
    };

    const card = elements.create("card", {style: style});
    card.mount("#card-element");
    card.on('change', function (event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    document.querySelector("#submit").addEventListener("click", async (event) => {
        event.preventDefault();
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        var $button     = $('#submit');
        $button.html(loadingText);
        $button.attr('disabled', 'disabled');
        if ($("#cardHolderName").val() == null || !$("#cardHolderName").val()) {
            $("#cardHolderName").addClass('is-invalid');
        } else {
            var cardholderName           = document.querySelector("#cardHolderName").value;
            const {paymentMethod, error} = await stripe.createPaymentMethod(
                'card', card, {
                    billing_details: {
                        "name": cardholderName
                    }
                }
            );
            if (error) {
                const displayError       = document.getElementById('cardErrors');
                displayError.textContent = error.message;
            } else {
                $.ajax({
                    url     : base_url + '/stripe-card',
                    type    : 'POST',
                    data    : {_token: CSRF_TOKEN, paymentMethodId: paymentMethod.id},
                    dataType: 'JSON',
                    success : function (res) {
                        if ($("#do-redirect").val() === 'yes') {
                            window.location.href = '/stripe-card'
                        } else {
                            $("input[name=payment_method]").val(paymentMethod.id);
                            $(".btn-finish").removeAttr('disabled');
                            $(".add-payment").hide();
                        }
                    }
                });
            }
        }
    });

    $("#cardHolderName").on('keyup', function (e) {
        if ($("#cardHolderName").val() == null || !$("#cardHolderName").val()) {
            $("#cardHolderName").addClass('is-invalid');
        } else {
            $("#cardHolderName").removeClass('is-invalid');
        }
    });
</script>
