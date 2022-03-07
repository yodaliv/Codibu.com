(function($) {
    "use strict";

    function getPlugins($button = false, page = 1) {
        $(".theme-box:not(#cloneable_plugin)").remove();
        $(".loader").fadeIn("fast");
        $.ajax({
            /* the route pointing to the post function */
            url: base_url + '/plugins/filter',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: $('.filters').serialize() + "&page=" +page,
            //{_token: CSRF_TOKEN, keyword, site_type: theme_type_id, page, theme_type},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (response) {
                $(".plugin-pagination").html(response.pagination);
                $(".loader").fadeOut("fast");;
                appendPlain(response.plugins.data);
            }
        });

    }

    function appendPlain(plugins) {
        for (let index = 0; index < plugins.length; index++) {
            var plugin = plugins[index];
            var collapseId = 'collapse-' + plugin.id;

            var $plugin = $("#cloneable_plugin").clone();
            $plugin.removeAttr('style');
            $plugin.attr('id', 'plugin-' + plugin.id);
            $plugin.find('input[name=item_id]').val(plugin.id);
            $plugin.find('input[name=item_version]').val(plugin.versions[0].id);
            $plugin.find('.card-title').text(plugin.name);
            $plugin.find('.card-text').html(plugin.description).attr('id', collapseId);
            $plugin.find('.info').attr('href', plugin.info);
            $plugin.find(".download-plugin").attr('data-id', plugin.id);
            $plugin.appendTo('.themes');

            // Show more text option
            var showChar = 130;  // How many characters are shown by default
            var ellipsestext = "...";
            var moretext = "Read more";
            var lesstext = "Read less";

            //Cut content based on showChar length
            if ($(`#${collapseId}`).length) {
                $(`#${collapseId}`).each(function() {
                    var content = $(this).html();
                    if(content.length > showChar) {
                        var contentExcert = content.substr(0, showChar);
                        var contentRest = content.substr(showChar, content.length - showChar);
                        var html = contentExcert + '<span class="toggle-text-ellipses">' + ellipsestext + ' </span> <span class="toggle-text-content"><span>' + contentRest + '</span><a id="collapse_id_'+plugin.id+'" href="javascript:;" class="toggle-text-link">' + moretext + '</a></span>';
                        $(this).html(html);
                    }
                });
            }

            //Toggle content when click on read more link
            $("#collapse_id_"+plugin.id).click(function(){
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

    $(".do-plugin-filter").on('click', function(e) {
        getPlugins(false, 1);
    });

    $('#inlineFormInputKeyword').keypress(function(event){
      var keycode = (event.keyCode ? event.keyCode : event.which);
      if(keycode == '13'){
        getPlugins(false, 1);
      }
    });

    $("body").on('click', '.plugin-pagination .page-link', function(e) {
        e.preventDefault();
        getPlugins(false, $(this).data('page'));
    });



    function StickyFooter() {
        let difference = $(document).height() - $(window).height();
        if(difference == 0) {
            $(".footer").addClass('sticky-footer')
        } else {
            $(".footer").removeClass('sticky-footer')
        }
    }

    getPlugins(false, 1);

    })(jQuery);
