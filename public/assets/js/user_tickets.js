(function ($) {
    "use strict";

    function getThemes($button = false, page = 1) {
        $(".theme-box:not(#cloneable_theme)").remove();
        $(".loader").fadeIn("fast");
        $.ajax({
            /* the route pointing to the post function */
            url: base_url + '/user_tickets',
            type: 'POST',
            /* send the csrf-token and the input to the controller */
            data: { _token: CSRF_TOKEN, page: page },
            //{_token: CSRF_TOKEN, keyword, site_type: theme_type_id, page, theme_type},
            dataType: 'JSON',
            /* remind that 'data' is the response of the AjaxController */
            success: function (response) {
                $(".user_tickets .user_tickets-pagination").html(response.pagination);
                $(".loader").fadeOut("fast");;
                appendPlain(response.tickets.data);
            }
        });

    }

    function appendPlain(tickets) {
        var html = '';
        for (let index = 0; index < tickets.length; index++) {
            var ticket = tickets[index];
            html += '<tr><td>' + ticket.category + '</td>' +
                '<td> <a href="' + base_url + '/support-ticket/' + ticket.id + '">' + '#' + ticket.ticket_id + ' - ' + ticket.title + '</a></td>' +
                '<td>';

            if (ticket.status == 'Open') {
                html += '<span class="label label-success">' + ticket.status + '</span>';
            } else {
                html += '<span class="label label-danger">' + ticket.status + '</span>';
            }
            html += '</td><td>' + moment(ticket.updated_at).format('DD-MM-YYYY') + '</td></tr>';
        }
        $('.user_tickets tbody').append(html);

        // setTimeout(() => {
        //     StickyFooter();
        // }, 1000);

    }

    $("body").on('click', '.theme-pagination .page-link', function (e) {
        e.preventDefault();
        getThemes(false, $(this).data('page'));
    });



    function StickyFooter() {
        let difference = $(document).height() - $(window).height();
        if (difference == 0) {
            $(".footer").addClass('sticky-footer')
        } else {
            $(".footer").removeClass('sticky-footer')
        }
    }

    getThemes(false, 1);

})(jQuery);
