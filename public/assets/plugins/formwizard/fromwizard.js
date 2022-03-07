/*(function ($) {
    "use strict";

    const hasPayment = $("#has-payment").val();

    // Toolbar extra buttons
    const btnFinish = $('<button></button>').text('Place your order')
        .addClass('btn btn-primary pull-right btn-finish')
        .attr('type', 'submit');

    if (hasPayment === 'no') {
        btnFinish.attr('disabled', 'disabled');
    }
    $('<button></button>').text('Cancel')
        .addClass('btn btn-danger')
        .on('click', function () {
            $('#smartwizard').smartWizard("reset");
        });

    // Smart Wizard
    $('#smartwizard').smartWizard({
        selected        : 0,
        theme           : 'default',
        transitionEffect: 'fade',
        showStepURLhash : false,
        enableURLhash   : false,
        anchorSettings  : {
            anchorClickable: false
        },
        toolbarSettings : {
            toolbarPosition      : 'bottom', // none, top, bottom, both
            toolbarButtonPosition: 'left', // left, right, center
            showNextButton       : true, // show/hide a Next button
            showPreviousButton   : true, // show/hide a Previous button
            toolbarExtraButtons  : [btnFinish] // Extra buttons to show on toolbar, array of jQuery input/buttons elements
        },
    });
    $(".sw-btn-next").addClass('btn-primary');
    $(".sw-btn-prev").addClass('btn-info');

})(jQuery);*/
