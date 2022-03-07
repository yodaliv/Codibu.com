(function($) {
"use strict";

function getThemes($button = false, page = 1) {
    $(".theme-box:not(#cloneable_theme)").remove();
    $(".loader").fadeIn("fast");
    $.ajax({
        /* the route pointing to the post function */
        url: base_url + '/themes/filter',
        type: 'POST',
        /* send the csrf-token and the input to the controller */
        data: $('.filters').serialize() + "&page=" +page,
        //{_token: CSRF_TOKEN, keyword, site_type: theme_type_id, page, theme_type},
        dataType: 'JSON',
        /* remind that 'data' is the response of the AjaxController */
        success: function (response) {
            $(".theme-pagination").html(response.pagination);
            $(".loader").fadeOut("fast");;
            appendPlain(response.themes.data);
        }
    });

}

function appendPlain(themes) {
    for (let index = 0; index < themes.length; index++) {
        var theme = themes[index];
        var collapseId = 'collapse-' + theme.id;
        var $themediv = $("#cloneable_theme").clone();
        $themediv.removeAttr('style');
        $themediv.attr('id', 'theme-' + theme.id);
        $themediv.find('input[name=item_id]').val(theme.id);
        $themediv.find('input[name=item_version]').val(theme.versions[0].id);
        $themediv.find('img').attr('src', "http://demo.codibu.com/wp-content/themes/"+theme.folder_uri+"/screenshot.png").attr("style", "height: 261px;");
        // $themediv.find('img').attr('onerror', 'this.src =  ; this.onerror = null;');
        $themediv.find('img').on("error", function () {
            let image = this;
            $.get("http://demo.codibu.com/wp-content/themes/"+theme.folder_uri+"/screenshot.jpg").done(function() {
                image.src = "http://demo.codibu.com/wp-content/themes/"+theme.folder_uri+"/screenshot.jpg";
            }).fail(function() {
                image.src = not_found_image;
            })
        });
        $themediv.find('img').attr('alt', theme.name);
        let name = theme.name;
        if(theme.name.toLowerCase().includes('theme') == false) {
            name += " Theme";
        }
        $themediv.find('.card-title').text(name);
        $themediv.find('.card-text').html(theme.description).attr('id', collapseId);
        $themediv.find('.developer').attr('href', theme.developer_link).text(theme.developer);
        $themediv.find('.info').attr('href', theme.info);
        $themediv.find(".download-theme").attr('data-id', theme.id);
        $themediv.appendTo('.themes');

        // Show more text option
        var showChar = 130;  // How many characters are shown by default
        var ellipsestext = "...";
        var moretext = "Read more";
        var lesstext = "Read less";

        //Cut content based on showChar length
        if ($(`#${collapseId}`).length) {
            $(`#${collapseId}`).each(function() {
                var content = $(this).html();
                var regex = /(<([^>]+)>)/ig;
                content = content.replace(regex, "");
                if(content.length > showChar) {
                    var contentExcert = content.substr(0, showChar);
                    var contentRest = content.substr(showChar, content.length - showChar);
                    var html = contentExcert + '<span class="toggle-text-ellipses">' + ellipsestext + ' </span> <span class="toggle-text-content"><span>' + contentRest + '</span><a id="collapse_id_'+theme.id+'" href="javascript:;" class="toggle-text-link">' + moretext + '</a></span>';
                    $(this).html(html);
                }
            });
        }

        //Toggle content when click on read more link
        $("#collapse_id_"+theme.id).click(function(){
            if($(this).hasClass("less")) {
                $(this).removeClass("less");
                $(this).html(moretext);
            } else {
                $(this).addClass("less");
                $(this).html(lesstext);
            }
            $(this).parent().prev().toggle();
            $(this).prev().toggle();
            return false;
        });
    }

    setTimeout(() => {
        StickyFooter();
    }, 1000);

}

$("body").on('click',".select-theme", function(e) {
    e.preventDefault();
    var theme_id = $(this).data('id');
    var theme_name = $("#theme-"+theme_id).find('.card-title').text();
    // $(this).attr('disabled', 'disabled');
    if($(this).hasClass('is_demo')) {
        $("input[name='theme_id']").val("");
        $("input[name='demo_id']").val(theme_id);

    } else{
        $("input[name='theme_id']").val(theme_id);
    }
    $(".theme-name").text(theme_name);
    $('#smartwizard').smartWizard("next");
});



$(".do-theme-filter").on('click', function(e) {
    getThemes(false, 1);
});

$('#inlineFormInputKeyword').keypress(function(event){
  var keycode = (event.keyCode ? event.keyCode : event.which);
  if(keycode == '13'){
    getThemes(false, 1);
  }
});

$("body").on('click', '.theme-pagination .page-link', function(e) {
    e.preventDefault();
    getThemes(false, $(this).data('page'));
});



function StickyFooter() {
    let difference = $(document).height() - $(window).height();
    if(difference == 0) {
        $(".footer").addClass('sticky-footer')
    } else {
        $(".footer").removeClass('sticky-footer')
    }
}

getThemes(false, 1);

})(jQuery);
