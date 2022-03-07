@extends('frontend.layouts.app')
@section('page-title')
    Home
@endsection
@section('content')
    <!-- hero section -->
    <div id="home" class="hero-section d-flex flex-column justify-content-center align-items-center">
        <h1 class="text-capitalize">One-stop wordPress solution</h1>
        <div class="small-title d-flex flex-column flex-sm-row flex-wrap">
            @forelse($sections['hero']['content'] as $section)
                @if(!empty($section['content']))
                    <div class="d-flex flex-item mb-4 me-3">
                        <div>
                            <img class="me-2 me-sm-3" src="{{ asset('images/icon/check-arrow.svg') }}" alt="">
                        </div>
                        <div>
                            {{$section['content']}}
                        </div>
                    </div>
                @endif
            @empty
            @endforelse
        </div>
        <form action="{{ url('register') }}">
            <div class="d-flex free-trail-search flex-wrap flex-column flex-sm-row">
                <input type="text" class="form-control" name="email" placeholder="Enter your email address">
                <button type="submit" class="bg-sky hover-animation">Start a free trial</button>
            </div>
        </form>
    </div>

    <!-- main work image section -->
    <div class="main-work-image-section d-flex justify-content-center border-bottom">
        <img src="{{ asset('images/work-img.png') }}" class="d-none d-lg-block img-fluid" alt="">
        <img src="{{ asset('images/work-img-md.png') }}" class="d-none d-md-block d-lg-none img-fluid" alt="">
        <img src="{{ asset('images/work-img-sm.png') }}" class="d-none d-sm-block d-md-none img-fluid" alt="">
        <img src="{{ asset('images/work-img-xs.png') }}" class="d-block d-sm-none img-fluid" alt="">
    </div>

    <!-- How it works section -->
    <div class="how-it-work-section border-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title-section">
                        <h3>How it works</h3>
                        <p>It is never been so easy as now. You are only three-step away from your dream website.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center ">
                <div class="work-content work-content-icon text-center">
                    <img src="{{ asset('images/updated-work-1.svg') }}" alt="">
                    <div class="title">Choose a Template</div>
                    <p class="text-break">Browse and select which template is best fit for you, we have 700+ temples
                        to choose from.</p><br><p class="text-break">We also offer 2k+ premium plugins for your website.                                     
                    </p>
                <!--                    <a href="#" class="arrow-icon">
                        <img src="{{ asset('images/icon/bg-arrow.svg') }}" alt="">
                        <img src="{{ asset('images/icon/bg-arrow-hover.svg') }}" alt="">
                    </a>-->
                </div>
                <div class="work-content work-content-icon text-center active">
                    <img src="{{ asset('images/work-2.svg') }}" alt="">
                    <div class="title">Customize Content</div>
                    <p class="text-break">
                        WordPress is one of the most popular content management system solutions.</p><br>
                        <p class="text-break">Customize your content the way you like it using wordPress.
                    </p>
                <!--                    <a href="#" class="arrow-icon">
                        <img src="{{ asset('images/icon/bg-arrow.svg') }}" alt="">
                        <img src="{{ asset('images/icon/bg-arrow-hover.svg') }}" alt="">
                    </a>-->
                </div>
                <div class="work-content work-content-icon text-center">
                    <img src="{{ asset('images/work-3.svg') }}" alt="">
                    <div class="title">Publish Website</div>
                    <p class="text-break">
                        After customizing your content, publish your website using our service and resources.</p><br>
                        <p class="text-break">We use AWS hosting service that ensure your fastest and uninterrupted service.
                    </p>
                <!--                    <a href="#" class="arrow-icon">
                        <img src="{{ asset('images/icon/bg-arrow.svg') }}" alt="">
                        <img src="{{ asset('images/icon/bg-arrow-hover.svg') }}" alt="">
                    </a>-->
                </div>
            </div>
        </div>
    </div>

    <!-- Slider section -->

    @include('frontend.component.slider')
    <!-- our key services section -->
    <div class="key-it-services-section border-bottom" id="services">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title-section">
                        <h3>Our key services</h3>
                        <p>Build a site, sell your stuff, start a blog and so much more. We provide everything you need
                            to publish your website. check out our key services.</p>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                @forelse($services as $service)
                    <div class="work-content key-service {{ $loop->index == 0 ? 'active' : '' }}">
                        <img class="services-logo" src="{{ asset($service->image) }}" alt="Image">
                        <div class="title">{{ $service->title ?? asset('images/no-image.jpg') }}</div>
                        <p class="text-break">
                            {{ $service->description ?? '' }}
                        </p>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
    @include('frontend.pages.pricing')
    <!-- quote section -->
    @if($testimonials->isNotEmpty())
        <div class="quote-section border-bottom bg-img-bubble">
            <div class="bg-position-img"></div>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-3 d-none d-lg-block">
                        <div class="quote-image">
                            <img class="h-100 w-100" src="{{ asset('images/faq-avatar-group.png') }}" alt="">
                        </div>
                    </div>
                    <div class="col-lg-9 col-12">
                        <div class="quote-content d-flex flex-column justify-content-center">
                            <h4 class="text-uppercase">What people say about codibu</h4>
                            <!-- Slider main container -->
                            <div id="quote-carousel-Indicators" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    @forelse($testimonials as $testimonial)
                                        <button type="button" data-bs-target="#quote-carousel-Indicators"
                                                data-bs-slide-to="{{ $loop->index }}"
                                                class="{{ $loop->index ? 'active' : '' }}"
                                                aria-current="true" aria-label="Slide{{ $loop->index }}"></button>
                                    @empty
                                    @endforelse
                                </div>
                                <div class="carousel-inner">
                                    @forelse($testimonials as $testimonial)
                                        <div class="carousel-item {{ $loop->index == 0 ? 'active' : '' }}">
                                            <div class="quote-slider">
                                                <p>{{ $testimonial->message ?? '' }}</p>
                                                <div class="name">{{ $testimonial->name ?? '' }}</div>
                                            </div>
                                        </div>
                                    @empty
                                    @endforelse
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- faq section -->
    <div class="faq-section border-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="title-section">
                        <h3>FAQ</h3>
                        <p>
                            Do you have any question in mind? we have already answered some questions for you.
                        </p>
                    </div>
                </div>
                <div class="col-md-10 offset-md-1">
                    <div class="accordion" id="accordionFaq">
                        @forelse($faqs as $faq)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{$loop->index}}">
                                    <button class="accordion-button {{ $loop->index == 0 ? '' : 'collapsed' }}"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{$loop->index}}" aria-expanded="false"
                                            aria-controls="collapse{{$loop->index}}">
                                        {{ $faq->section_title ?? '' }}
                                    </button>
                                </h2>
                                <div id="collapse{{$loop->index}}"
                                     class="accordion-collapse collapse {{ $loop->index == 0 ? 'show' : '' }}"
                                     aria-labelledby="heading{{$loop->index}}" data-bs-parent="#accordionFaq">
                                    <div class="accordion-body">Placeholder content for this accordion, which is
                                        {!! $faq->section_content ?? '' !!}
                                    </div>
                                </div>
                            </div>
                        @empty
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- start a free trial section -->
    <div class="align-items-center bg-img-bubble d-flex flex-column free-trial-section">
        <div class="bg-position-img"></div>
        <h1>Start your free trial</h1>
        <p> Create your dream website in few clicks. Sign up to our free trial session to find out if it is good fit
            your dream website.
        </p>
        <form action="{{ url('register') }}">
            <div class="d-flex free-trail-search flex-wrap flex-column flex-sm-row">
                <input type="text" class="form-control shadow-sm" name="email" placeholder="Enter your email address">
                <button type="submit" class="bg-sky hover-animation shadow">Start a free trial</button>
            </div>
        </form>
    </div>
    @include('frontend.pages.contact')
@endsection

@push('js')
    <script defer>

        // owl-slider
        let activeClassName = "center";

        function takeAction(event) {
            event.preventDefault();
            let dataUrl = event.target.closest("[data-url]");
            dataUrl.offsetParent.classList.contains(activeClassName) ? window.open(`https://${dataUrl.dataset.url}`, '_blank') : false;
        }

        document.addEventListener("DOMContentLoaded", function () {
            // menu active class added
            let navBar = document.querySelectorAll('.navbar-nav .nav-link');
            navBar.forEach(ele => {
                ele.addEventListener('click', function () {
                    navBar.forEach(e => {
                        e.classList.remove('active')
                    });
                    ele.classList.add('active')
                })
            })

            // mouse active class list
            const keyService = document.querySelectorAll('.key-service');
            keyService.forEach((ele) => {
                ele.addEventListener('mouseover', function (e) {
                    keyService.forEach(item => {
                        item.classList.remove('active')
                    })
                    ele.classList.add('active');
                })
            })
            const workContentIcon = document.querySelectorAll('.work-content-icon');
            workContentIcon.forEach((ele) => {
                ele.addEventListener('mouseover', function (e) {
                    workContentIcon.forEach(item => {
                        item.classList.remove('active')
                    })
                    ele.classList.add('active');
                })
            })


            // scrolling image
            $(".scrollable-image").parent().scrollImage();

            // active slider style
            let sliderBtn     = document.querySelectorAll('.slider-btn');
            let sliderSection = document.querySelectorAll('.slider_item');
            sliderBtn.forEach((value, index) => {
                sliderBtn[index].addEventListener('click', showClass);
            })

            function showClass(e) {
                $(".preloader-slider-section").show();
                e.preventDefault();
                sliderBtn.forEach((value, index) => {
                    sliderBtn[index].classList.remove('active');
                })
                e.target.classList.add('active');
                setTimeout(function () {
                    $(".preloader-slider-section").hide();
                }, 500);
                sliderSection.forEach((e, i) => {
                    sliderSection[i].style.display = 'none';
                })
                let getAttr                                    = e.target.getAttribute('data-slider');
                document.getElementById(getAttr).style.display = 'block';
            }

            // line break style
            let textBreak = document.querySelectorAll('.text-break');
            textBreak.forEach((e, i) => {
                e.innerHTML = e.innerText.split('.').join(`.</br>`);
            })
        });
        // Menu links to sections with auto-scrolling
        $(".nav-link").click(function () {
            let id = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(id).offset().top - 80
            }, 100);

        });

        // owl-carousel
        let owl = $('.owl-carousel').owlCarousel({
            center : true,
            loop   : true,
            margin : 0,
            nav    : true,
            navText: [`<img src="{{ asset('images/icon/arrow-blue-right.svg') }}" alt="">`, `<img src="{{ asset('images/icon/arrow-blue-left.svg') }}" alt="">`],
            // dots   : true,
            // dotsData: true,
            responsiveClass: true,
            responsive     : {
                0  : {
                    items: 2,
                    // margin : 30,
                },
                576: {
                    items: 2,
                },
                768: {
                    items: 2,
                },
                992: {
                    items: 3,
                }

            }
        });

        owl.on('changed.owl.carousel', function (event) {
            // TODO: Update positions in DOM
            $('#owl-number').text(`${event.page.index + 1} /${event.page.count}`)
        });

        $("body").on('click', '.owl-item', function (ele) {
            let activeClass = $(this).hasClass('center')
            if (!activeClass) {
                let x = window.outerWidth / 2;
                if (x > ele.pageX) {
                    $('.owl-prev').click()
                } else {
                    $('.owl-next').click()
                }
            }
        });

    </script>

<!--    <script defer>
        "use strict";

        // owl-slider
        var activeClassName = "center";

        function takeAction(event) {
            event.preventDefault();
            var dataUrl = event.target.closest("[data-url]");
            dataUrl.offsetParent.classList.contains(activeClassName) ? window.open("https://" + dataUrl.dataset.url, '_blank') : false;
        }

        document.addEventListener("DOMContentLoaded", function () {
            // menu active class added
            var navBar = document.querySelectorAll('.navbar-nav .nav-link');
            navBar.forEach(function (ele) {
                ele.addEventListener('click', function () {
                    navBar.forEach(function (e) {
                        e.classList.remove('active');
                    });
                    ele.classList.add('active');
                });
            });

            // mouse active class list
            var keyService = document.querySelectorAll('.key-service');
            keyService.forEach(function (ele) {
                ele.addEventListener('mouseover', function (e) {
                    keyService.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    ele.classList.add('active');
                });
            });
            var workContentIcon = document.querySelectorAll('.work-content-icon');
            workContentIcon.forEach(function (ele) {
                ele.addEventListener('mouseover', function (e) {
                    workContentIcon.forEach(function (item) {
                        item.classList.remove('active');
                    });
                    ele.classList.add('active');
                });
            });

            // scrolling image
            $(".scrollable-image").parent().scrollImage();

            // active slider style
            var sliderBtn = document.querySelectorAll('.slider-btn');
            var sliderSection = document.querySelectorAll('.slider_item');
            sliderBtn.forEach(function (value, index) {
                sliderBtn[index].addEventListener('click', showClass);
            });

            function showClass(e) {
                $(".preloader-slider-section").show();
                e.preventDefault();
                sliderBtn.forEach(function (value, index) {
                    sliderBtn[index].classList.remove('active');
                });
                e.target.classList.add('active');
                setTimeout(function () {
                    $(".preloader-slider-section").hide();
                }, 500);
                sliderSection.forEach(function (e, i) {
                    sliderSection[i].style.display = 'none';
                });
                var getAttr = e.target.getAttribute('data-slider');
                document.getElementById(getAttr).style.display = 'block';
            }

            // line break style
            var textBreak = document.querySelectorAll('.text-break');
            textBreak.forEach(function (e, i) {
                e.innerHTML = e.innerText.split('.').join(".</br>");
            });
        });
        // Menu links to sections with auto-scrolling
        $(".nav-link").click(function () {
            var id = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(id).offset().top - 80
            }, 100);
        });

        // owl-carousel
        var owl = $('.owl-carousel').owlCarousel({
            center: true,
            loop: true,
            margin: 0,
            nav: true,
            navText: ["<img src=\"{{ asset('images/icon/arrow-blue-right.svg') }}\" alt=\"\">", "<img src=\"{{ asset('images/icon/arrow-blue-left.svg') }}\" alt=\"\">"],
            // dots   : true,
            // dotsData: true,
            responsiveClass: true,
            responsive: {
                0: {
                    items: 2
                },
                576: {
                    items: 2
                },
                768: {
                    items: 2
                },
                992: {
                    items: 3
                }

            }
        });

        owl.on('changed.owl.carousel', function (event) {
            // TODO: Update positions in DOM
            $('#owl-number').text(event.page.index + 1 + " /" + event.page.count);
        });

        $("body").on('click', '.owl-item', function (ele) {
            var activeClass = $(this).hasClass('center');
            if (!activeClass) {
                var x = window.outerWidth / 2;
                if (x > ele.pageX) {
                    $('.owl-prev').click();
                } else {
                    $('.owl-next').click();
                }
            }
        });
    </script>-->

@endpush
