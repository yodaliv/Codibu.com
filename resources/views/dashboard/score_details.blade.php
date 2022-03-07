<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>
    <title>Codibu.com</title>
    <style>
        .holds-the-iframe {
            background: url({{asset('loader.gif')}}) no-repeat;
            background-position: center 300px;
        }

        ::-webkit-scrollbar {
            display: none;
        }

        body {
            -ms-overflow-style: none; /* IE and Edge */
            scrollbar-width: none; /* Firefox */
        }
    </style>
</head>

<body>

<div id="iframe-loader">
</div>

<script src="{{ URL::asset('assets/landing/js/jquery.js') }}"></script>
<script>
    $("#global-loader").fadeOut("slow");
    let iframeURL = '{{ request("url") }}'
    if (iframeURL) {
        let decodedUrl = atob(iframeURL);
        let dataForIframe = {
            url: decodedUrl,
            height: 1400
        }
        loadIframe(dataForIframe);
    }

    function loadIframe(iframeData) {
        $("#iframe-loader").empty();
        $("#iframe-loader").append('<div class="holds-the-iframe text-center"></div>');

        var ifr = $('<iframe/>', {
            id: 'MainPopupIframe',
            style: 'width: 100%; border: 0;',
            height: iframeData.height,
            src: iframeData.url,
        });
        $("#iframe-loader div").append(ifr);
    }
</script>
</body>
</html>
