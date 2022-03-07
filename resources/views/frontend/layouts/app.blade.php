<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} - @yield('page-title', 'Welcome')</title>
    <link rel="shortcut icon" href="{{ asset('images/logo.svg')}}">
    @stack('css')
    <link rel="stylesheet" href="{{ asset('css/app.bootstrap.5.1.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

</head>

<body>
@if ( !in_array(Route::currentRouteName(), ['register', 'login','password.request']))
    @include('frontend.layouts.header')
@endif
@yield('content')

@if ( !in_array(Route::currentRouteName(), ['register', 'login','password.request']))
    @include('frontend.layouts.footer')
@endif
<script src="{{ asset('js/app.bootstrap.5.1.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/image-scroller.js') }}"></script>


<script>

    let base_url = "{{ config('app.url') }}";
    /*=========START LOG IN SCRIPT==========*/
    // input text validation
    document.querySelectorAll('input[type="text"]').forEach(item => {
        item.addEventListener('input', function () {
            let inputText = item.value
            if (inputText.length > 2) {
                item.nextElementSibling.classList.add('active')
            } else {
                item.nextElementSibling.classList.remove('active')
            }
        })
    })

    // password eye show hide
    $('.pass-eye').click(function () {
        $('.pass-eye').addClass('active')
        $(this).removeClass('active')
        if ($('.eye-on').hasClass('active')) {
            $(this).siblings('input').attr('type', 'text');
        } else {
            $(this).siblings('input').attr('type', 'password')
        }
    })
    $('.pass-eye-2').click(function () {
        $('.pass-eye-2').addClass('active')
        $(this).removeClass('active')
        if ($('.eye-on').hasClass('active')) {
            $(this).siblings('input').attr('type', 'text');
        } else {
            $(this).siblings('input').attr('type', 'password')
        }
    })

    // email validation
    let emailInput = document.querySelector('input[type="email"]');
    emailInput.addEventListener('input', function () {
        let email = emailInput.value;
        if (isEmailValid(email)) {
            emailInput.nextElementSibling.classList.add('active')
        } else {
            emailInput.nextElementSibling.classList.remove('active')
        }
    })

    function isEmailValid(email) {
        const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    document.querySelectorAll('.control-form').forEach(item => {
        item.addEventListener('focus', function () {
            item.parentNode.classList.add('active-gradient')
        })
        item.addEventListener('blur', function () {
            item.parentNode.classList.remove('active-gradient')
        })
    })

    /*=========END LOG IN SCRIPT=========*/
</script>
@stack('js')
</body>


</html>
