(function ($) {
    "use strict";
    var loadingText    = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
    var buttonText     = "";
    var selectedDomain = "";

    //----------------themes filtering ---------------
    $(document).ready(function (e) {
        $(".select2").select2({minimumResultsForSearch: ""});
    });
    //paginate button click
    $("body").on('click', '.theme-pagination .page-link', function (e) {
        getThemes($(this).data('page'));
    });
    $('#inlineFormInputKeyword').keypress(function(event){
      var keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == '13'){
        getThemes(1);
      }
    });
    //filter button click
    /* $(".do-theme-filter").on('click', function (e) {
         getThemes(1);
     });
     //demo_name_ids on select
     $("#demo_name_ids, .editor_type, .theme_type_ids").on('change', function () {
         getThemes(1);
     });*/
    //filter button click
    $(".do-theme-filter").on('click', function (e) {
        getThemes(1);
    });

    function getThemes(page = 1) {
        var keyword    = $('#inlineFormInputKeyword').val();
        var theme_type = $("input[name=theme_type]").val();
        var demo_name  = $("#demo_name_ids").val();
        let etype      = [];
        $.each($("input[name='editor_type']:checked"), function () {
            etype.push($(this).val());
        });
        let site_type = [];
        $.each($("input[name='theme_type_ids']:checked"), function () {
            site_type.push($(this).val());
        });
        site_type            = site_type.length > 0 ? encodeURIComponent(JSON.stringify(site_type)) : '';
        etype                = etype.length > 0 ? encodeURIComponent(JSON.stringify(etype)) : '';
        demo_name            = demo_name.length > 0 ? encodeURIComponent(JSON.stringify(demo_name)) : '';
        let params           = `keyword=${keyword}&site_type=${site_type}&page=${page}&theme_type=${theme_type}&demo_name=${demo_name}&etype=${etype}`
        window.location.href = `/sites/create?${params}`;
    }

    // ------------end themes filtering-------------
})(jQuery);
