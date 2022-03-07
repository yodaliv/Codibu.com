(function ($) {
    "use strict";
    var loadingText    = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';


    $("body").on('click', ".select-theme", function (e) {
        e.preventDefault();
        var theme_id   = $(this).data('id');
        var theme_name = $("#theme-" + theme_id).find('.card-title').text();
        // $(this).attr('disabled', 'disabled');
        if ($(this).hasClass('is_demo')) {
            $("input[name='theme_id']").val("");
            $("input[name='demo_id']").val(theme_id);
        } else{
            $("input[name='theme_id']").val(theme_id);
        }
        $(".theme-name").text(theme_name);
        $('#smartwizard').smartWizard("next");
    });

    $('#siteForm').on('keyup keypress', function (event) {
        var code;

        if (event.key !== undefined) {
            code = event.key;
        } else if (event.keyIdentifier !== undefined) {
            code = event.keyIdentifier;
        } else if (event.keyCode !== undefined) {
            code = event.keyCode;
        }
        if (code === 13 || code == 'Enter') {
            event.preventDefault();
            return false;
        }

    });

    $("#domain-name").on('keyup', function (event) {
        var code;
        event.preventDefault();

        if (event.key !== undefined) {
            code = event.key;
        } else if (event.keyIdentifier !== undefined) {
            code = event.keyIdentifier;
        } else if (event.keyCode !== undefined) {
            code = event.keyCode;
        }
        if (code === 13 || code == 'Enter') {
            $(".do-domain-search").click();
        }
        return false;
    });

    $("body").on('click', '.do-domain-search', function (e) {
        e.preventDefault();
        var CSRF_TOKEN    = $('meta[name="csrf-token"]').attr('content');
        var query         = $('#domain-name').val();
        var $button       = $(this);
        var buttonOldtext = $button.html();
        $button.html(loadingText);
        $button.attr('disabled', 'disabled');

        $.ajax({
            /* the route pointing to the post function */
            url : base_url + '/sites/domain',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data    : {_token: CSRF_TOKEN, query},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (response) {
                $(".domain-box:not(#cloneable_domain)").remove();
                $('#domainNameError').text('');
                Object.entries(response.result).map(([k, v]) => {
                    var $domaindiv = $("#cloneable_domain").clone();
                    $domaindiv.removeAttr('id');
                    $domaindiv.removeAttr('style');
                    $domaindiv.find('h5').text(k);
                    if (!v) {
                        $domaindiv.find('.d-not-available').removeAttr('style');
                        $domaindiv.find('.i-own-domain').removeAttr('style');
                    } else {
                        $domaindiv.find('.d-available').removeAttr('style');
                        $domaindiv.find('.purchase-domain').removeAttr('style');
                    }
                    $domaindiv.appendTo('.domains');
                    $domaindiv.find('.select-domain').data('domain', k);
                    $.ajax({
                        url: base_url + '/sites/domain/price',
                        type: 'POST',
                        data: {_token:CSRF_TOKEN, domain:k},
                        dataType: 'JSON',
                        success: function(res) {
                            $domaindiv.find('h6').text('$' + res + " per year");
                            $domaindiv.find('.select-domain').data('domain_price', res);
                        },
                        error : (res) => {
                            console.log(res);
                        }
                    })
                });
                $("#tab-steps").css("height", "auto");
                $button.html(buttonOldtext);
                $button.removeAttr('disabled');

                setTimeout(() => {
                    StickyFooter();
                }, 1000);
            },
            error  : (response) => {
                $('#domainNameError').text(response.responseJSON.errors.query);
                $("#tab-steps").css("height", "auto");
                $button.html(buttonOldtext);
                $button.removeAttr('disabled');

                setTimeout(() => {
                    StickyFooter();
                }, 1000);
            }
        });

    });

    $("input[name=title], input[name=password]").on('keyup', function (event) {

        $(this).removeClass('is-invalid');
        $(this).parent().find('.invalid-feedback').addClass('hidden');

    });

    if($("input[name=session_domain]").val()) {
        $(".domain-name").text($("input[name=session_domain]").val());
    }
    if($("input[name=session_domain_price]").val()) {
        $(".domain-price").text($("input[name=session_domain_price]").val());
    }
    if($("input[name=session_plan_name]").val()) {
        $(".plan-name").text($("input[name=session_plan_name]").val());
    }
    if($("input[name=session_plan_price]").val()) {
        $(".plan-price").text("$" + $("input[name=session_plan_price]").val());
    }
    if($("input[name=session_plan_discount]").val()) {
        $(".plan-discount").text($("input[name=session_plan_discount]").val()+"%");
    }
    if($("input[name=session_coupon_discount]").val()) {
        $(".coupon-discount").text($("input[name=session_coupon_discount]").val()+"%");
    }
    $('body').on('click', '.select-domain', function (e) {
        $("input[name=domain]").val($(this).data('domain'));
        $("input[name=domain_price]").val($(this).data('domain_price'));
        $(".domain-name").text($(this).data('domain'));
        $(".domain-price").text("$" + $(this).data('domain_price') + " per year");
        if ($(this).hasClass('purchase-domain')) {
            $("input[name=domain_type]").val("purchase_request");
        } else {
            $("input[name=domain_type]").val("already_owned");
        }
        $('#smartwizard').smartWizard("next");
    });
    function coupon_code() {
        var coupon_code = $("#coupon_code").val();
        var CSRF_TOKEN  = $('meta[name="csrf-token"]').attr('content');
        var plan_id = $("input[name=plan]").val();
        var price   = $(".plan-" + plan_id).find('.card-title .pull-right').text();
        console.log("price :: ", price);
        console.log("coupon_code :: ", coupon_code);
        console.log("plan_id :: ", plan_id);

        $.ajax({
            /* the route pointing to the post function */
            url : base_url + '/sites/coupon',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data    : {_token: CSRF_TOKEN, coupon_code: coupon_code, plan_id: plan_id},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: (response) => {
                if (response.errors) {
                    $(".coupon-discount").text(response.discount);
                    $(".final-price").text(response.price);
                    $("#coupon_errors").fadeIn("slow");
                    $("#coupon_errors").html(response.errors);
                    $("#coupon_errors").delay(2000).fadeOut();
                } else {
                    $(".coupon-discount").text(response.discount);
                    $(".final-price").text(response.price);
                    $("#coupon_success").fadeIn("slow");
                    $("#coupon_success").html(response.success);
                    $("#coupon_success").delay(2000).fadeOut();
                }
            },
            error  : (XMLHttpRequest, textStatus, errorThrown) => {
                $("#tab-steps").css("height", "auto");
                let errors = XMLHttpRequest.responseText.errors;
                if (!XMLHttpRequest.responseText.errors) {
                    errors = JSON.parse(XMLHttpRequest.responseText);
                }
                displayErrors(errors, $button);
            }

        });
    }
    if($("#coupon_code").val()){
        $(window).on("load", function () {
            coupon_code();
        });
    }
    $('#coupon_code').on('input', debounce(function(){
        coupon_code()
    },500));
    function debounce(func, wait, immediate) {
        var timeout;
        return function() {
            var context = this, args = arguments;
            var later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };

    $(".PlanButton").on('click', function (e) {
        var plan_id = $(this).data('id');
        $("input[name=plan]").val(plan_id);
        $(".plan-name").text($(".plan-" + plan_id).find('#planName').text());
        var $planPrice = "$" + $(".plan-" + plan_id).find('#planPrice').text().match(/\d+(\.\d\d+)?/g).map(function(v) { return parseFloat(v); });
        var $planDiscount = $(".plan-" + plan_id).find('#planDiscount').text().match(/\d+(\.\d\d+)?/g).map(function(v) { return parseFloat(v); })+"%";
        var $planCurrentPrice = "$" + $(".plan-" + plan_id).find('#planCurrentPrice').text().match(/\d+(\.\d\d+)?/g).map(function(v) { return parseFloat(v); });
        $(".plan-price").text($planPrice);
        $(".plan-discount").text($planDiscount);
        $(".final-price").text($planCurrentPrice);

        $('#smartwizard').smartWizard("next");
    });
    $("#smartwizard").on("showStep", function(e, anchorObject, stepIndex, stepDirection) {
        if(stepIndex == null){
            $("#smartwizard .toolbar").hide();
        }
    });
    $("#smartwizard").on("leaveStep", function (e, anchorObject, currentStepIndex, nextStepIndex, stepDirection) {
        if (nextStepIndex == 0) {
            $("#smartwizard .toolbar").hide();
        } else {
            $("#smartwizard .toolbar").show();
        }

        if (nextStepIndex == 2) {


            var title    = $("input[name=title]").val();
            var password = $("input[name=password]").val();
            if (!title) {
                $("input[name=title]").focus();
                $("input[name=title]").addClass('is-invalid');
                $("input[name=title]").parent().find('.invalid-feedback').removeClass('hidden');
                return false;
            }

            if (!password) {
                $("input[name=password]").focus();
                $("input[name=password]").addClass('is-invalid');
                $("input[name=password]").parent().find('.invalid-feedback').removeClass('hidden');
                return false;
            }

        }


        if (nextStepIndex == 1) {
            $(".sw-btn-next").show();
        } else {
            $(".sw-btn-next").hide();
        }


        if (nextStepIndex == 4) {
            $(".btn-finish").show();
        } else {
            $(".btn-finish").hide();
        }
        setTimeout(() => {
            StickyFooter();
        }, 1000);
    });

    function displayErrors(res, $button) {
        $.each(res.errors, function (i, error) {
            let errorLine = error.constructor === Array ? error[0] : error;
            $(".errors").append("<li>" + errorLine + "</li>");
        });
        $('#loader').addClass('hidden');
        $(".errors").removeClass("hidden");
        $button.removeAttr('disabled');
        $button.removeClass('disabled');
        $button.html("Finish");
    }

    $("#siteForm").on('submit', function (e) {
        e.preventDefault();
        var $form = $(this);
        var $button = $(".btn-finished");
        $button.attr('disabled', 'disabled');
        $button.addClass('disabled');
        $button.html(loadingText);
        $(".errors li").remove();

        $.ajax({
            /* the route pointing to the post function */
            url: base_url + '/sites',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: $form.serialize(),
            dataType: 'JSON',
            beforeSend: function () { // Before we send the request, remove the .hidden class from the spinner and default to inline-block.
                $('#loader').removeClass('hidden')
            },
            /* remind that 'data' is the response of the AjaxController */
            success:  (response) => {
                if(response.errors) {
                    displayErrors(response, $button);
                } else {
                    $("#tab-steps").css("height", "auto");
                    $(".create-success").removeAttr('hidden');
                    if (response.payment_platform =='PayPal') {
                        window.location.href = response.paypal_url;
                    }else if (response.payment_platform =='Stripe'){
                        top.location.href = base_url + "/thank-you";
                    } else {
                        $("#AmazonPayButton").click();
                        $('#loader').addClass('hidden');

                    }
                }
            },
            error: (XMLHttpRequest, textStatus, errorThrown)  => {
                $("#tab-steps").css("height", "auto");
                let errors = XMLHttpRequest.responseText.errors;
                if(!XMLHttpRequest.responseText.errors) {
                    errors = JSON.parse(XMLHttpRequest.responseText);
                }
                displayErrors(errors, $button);
            }
        });

    });


    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })


    function StickyFooter() {
        let difference = $(document).height() - $(window).height();
        if (difference == 0) {
            $(".footer").addClass('sticky-footer')
        } else {
            $(".footer").removeClass('sticky-footer')
        }
    }

    $(window).on('load', function () {
        let footerInterval = setInterval(() => {
            if (!$("#global-loader").is(':visible')) {
                StickyFooter();
                clearInterval(footerInterval);
            }
        }, 3000);
    });


})(jQuery);
