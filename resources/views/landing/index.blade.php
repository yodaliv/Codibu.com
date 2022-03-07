<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- Stylesheets
	============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Heebo:300,400,500,700,900" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="{{ URL::asset('assets/landing/css/bootstrap.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ URL::asset('assets/landing/style.css') }}" type="text/css" />

	<link rel="stylesheet" href="{{ URL::asset('assets/landing/css/dark.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ URL::asset('assets/landing/css/font-icons.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ URL::asset('assets/landing/css/animate.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ URL::asset('assets/landing/css/magnific-popup.css') }}" type="text/css" />

	<link rel="stylesheet" href="{{ URL::asset('assets/landing/css/custom.css') }}" type="text/css" />

	<!-- Freelancer Demo Specific Stylesheet -->
	<link rel="stylesheet" href="{{ URL::asset('assets/landing/demos/freelancer/css/fonts.css') }}" type="text/css" />
	<link rel="stylesheet" href="{{ URL::asset('assets/landing/demos/freelancer/freelancer.css') }}" type="text/css" />

	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<link rel="stylesheet" href="{{ URL::asset('assets/landing/css/colors.css') }}" type="text/css" />

	<!-- Document Title
	============================================= -->
	<title>Codibu.com</title>

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Header
		============================================= -->
		<header id="header" class="border-bottom-0 no-sticky transparent-header">
			<div id="header-wrap">
				<div class="container">
					<div class="header-row">

						<!-- Logo
						============================================= -->
						<div id="logo">
							<a href="{{ url('') }}" class="standard-logo"><img src="{{ URL::asset('assets/landing/demos/freelancer/images/top_logo_white_300.png') }}" alt="codibu Logo"></a>
							<a href="{{ url('') }}" class="retina-logo"><img src="{{ URL::asset('assets/landing/demos/freelancer/images/top_logo_white_600.png') }}" alt="codibu Logo"></a>
						</div><!-- #logo end -->

						<div class="header-misc">
                            @auth
                                <a href="{{ url('sites/create') }}" class="button button-teal button-border rounded-pill">New site</a>
                                <a href="{{ url('home') }}" class="button button-border rounded-pill">Dashboard</a>
                            @endauth
                            @guest
                                <a href="{{ url('register') }}" class="button button-teal button-border rounded-pill">Register</a>
                                <a href="{{ url('login') }}" class="button button-primary button-border rounded-pill">Login</a>
                            @endguest
						</div>

						<div id="primary-menu-trigger">
							<svg class="svg-trigger" viewBox="0 0 100 100"><path d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"></path><path d="m 30,50 h 40"></path><path d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"></path></svg>
						</div>

						<!-- Primary Navigation
						============================================= -->
						<nav class="primary-menu">

							<ul class="menu-container">
								<li class="menu-item"><a class="menu-link" href="demo-freelancer-about.html"><div>About Us</div></a></li>
								<li class="menu-item"><a class="menu-link" href="#demos" data-scrollto="#demos" data-easing="easeInOutExpo" data-speed="1250" data-offset="70"><div>Demos</div></a></li>
								<li class="menu-item"><a class="menu-link" href="#services" data-scrollto="#services" data-easing="easeInOutExpo" data-speed="1250" data-offset="70"><div>Services</div></a></li>
								<li class="menu-item"><a class="menu-link" href="#faq" data-scrollto="#faq" data-easing="easeInOutExpo" data-speed="1250" data-offset="70"><div>FAQ</div></a></li>
								<li class="menu-item"><a class="menu-link" href="#" data-scrollto="#footer" data-easing="easeInOutExpo" data-speed="1250" data-offset="70"><div>Contact</div></a></li>
							</ul>

						</nav><!-- #primary-menu end -->

					</div>
				</div>
			</div>
		</header><!-- #header end -->

        @if(isset($sections['hero']))
		<!-- Slider
		============================================= -->
		<section id="slider" class="slider-element min-vh-md-100 py-4 include-header" style="background-image:url(https://knowledge-base.codibu.com/wp-content/uploads/2021/04/background3-scaled.jpg); background-size:cover;">
			<div class="slider-inner">
				<div class="vertical-middle slider-element-fade">
					<div class="container text-center py-5">
						<div class="emphasis-title mb-2">
									<h2>
								<span id="oc-images" class="owl-carousel image-carousel carousel-widget" data-items="1" data-margin="0" data-loop="true" data-nav="false" data-pagi="false" data-animate-in="fadeInUp">
									<h3 class="font-weight-bolder h1 mb-4">{{ $sections['hero']['title2']['content'] }}</h3>

								</span>
							</h2>
							<h4 class="font-weight-bolder mb-0">{{ $sections['hero']['title']['content'] }}</h4>
							<div class="mx-auto"  style="max-width: 1400px">
							<h4 class="font-weight-bolder mb-0">{{ $sections['hero']['description']['content'] }}</h4>
				<br>
						</div>
														{{--<!--<img style=" max-height: 500px;" src="{{  Storage::disk('s3')->url($sections['hero']['image']['photo']) }}"> -->--}}
                        </div>
									<a href="demo-freelancer-works.html" class="button button-dark button-hero h-translatey-3 tf-ts button-reveal overflow-visible bg-dark text-right"><span style="color:#f7c25e">Get Started</span><i class="icon-line-arrow-right"></i></a>
							<a href="#" data-scrollto="#container" data-easing="easeInOutExpo" data-speed="1250" data-offset="70" class="button button-large button-light text-dark bg-transparent m-0"><i class="icon-line2-arrow-down font-weight-bold"></i> <u>Read More</u></a>
						<!-- <div class="d-flex align-items-center justify-content-center mb-5">
							<img src="{{ URL::asset('assets/landing/demos/freelancer/images/face.svg') }}" alt="Face" width="60">
							<span class="text-uppercase font-weight-bold ml-3">SemiColonWeb</span>
						</div> -->

					</div>
				</div>
			</div>
		</section><!-- #slider end -->
        @endif

		<!-- Content
		============================================= -->
		<section id="content">

			<div id="demos" class="section m-0">
					<div class="container">
						<div class="row align-items-end justify-content-between mb-5">
							<div class="col-lg-5 offset-lg-1">
								<div>
									<h3 class="font-weight-bolder h1 mb-4">Creative Demos</h3>
																	</div>
							</div>
							<div class="col-auto">
								<a href="{{ url('directory/themes/list') }}" class="button button-dark button-border rounded-pill">View All Works <i class="icon-line-arrow-right"></i></a>
							</div>
						</div>

						<div class="row justify-content-center col-mb-50">
                            @php $colors = ['#e6f0ef', '#ffe1cf', '#dbe0f4', '#e7e4db']; @endphp
                            @foreach($themes as $key => $theme)
                            @php $types = $theme->site_types->pluck('name')->toArray(); @endphp
                                <div class="col-lg-3 h-translatey-3 tf-ts">
                                    <a href="http://{{ $theme->url }}" class="portfolio-item" target="_blank">
                                        <div class="portfolio-image" style="background-color: {{ $colors[$key] }}">
                                            <div class="portfolio-image-container">
                                                <img class="scrollable-image" src="{{ $theme->theme_image }}" alt="Portfoio Item">
                                            </div>
                                            <div class="bg-overlay">
                                                <div class="bg-overlay-content align-items-start justify-content-start flex-column px-5 py-4">
                                                    <h3 class="mb-0 mt-1">{{ $theme->name}}</h3>
                                                    <h5>{{ count($types) ? implode(', ', $types) : '' }}</h5>
                                                </div>
                                                <div class="bg-overlay-content align-items-start justify-content-end p-4">
                                                    <div class="overlay-trigger-icon bg-dark text-white" data-hover-animate="fadeInDownSmall" data-hover-animate-out="fadeOutUpSmall" data-hover-speed="350"><i class="icon-line-link"></i></div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
						</div>

					</div>
				</div>


			<div class="content-wrap p-0">

				<div class="section mb-0 pt-3 pb-0" style="background-color: #F4F4F4; margin-top: 150px; overflow: visible;">
					<div class="shape-divider" data-shape="wave" data-height="150" data-outside="true" data-flip-vertical="true" data-fill="#F4F4F4"></div>
					<div class="container">
						<div class="row justify-content-center text-center mt-5">
							<h3 class="font-weight-bolder h1 mb-4">Pick & Customize</h3>
									<p class="mb-5 lead text-black-50 font-weight-extralight">Choose from over 2000+ Free designer-made templates and customize using the easy website builder.</p>
								</div>
							</div>
						</div>
					</div>
					<div class="d-flex justify-content-center position-relative">
						<div class="parallax min-vh-75" style="background-image: url('{{ URL::asset('assets/landing/demos/freelancer/images/templates.jpg') }}'); background-size: cover; background-position: center center;" data-bottom-top="width: 40vw" data-center-top="width: 100vw;">
							<div class="row align-items-center justify-content-center h-100">
								<div class="col-auto text-center">
									<a href="#" class="display-4 font-weight-bolder text-white d-inline-block mx-4 h-op-08 op-ts"><u>Get Started</u></a>
															</div>
							</div>
						</div>
						<div class="shape-divider" data-shape="wave" data-position="bottom"></div>
					</div>
				</div>

									<div class="line line-sm mb-0"></div>
				</div>

				<div class="section bg-transparent py-5">
					<div class="container">
						<div class="row align-items-center justify-content-around">
					    <div class="row justify-content-center text-center mt-5">
							<div class="col-lg-8">
									<h3 class="font-weight-bolder h1 mb-4">Without Limits</h3>
									<p class="mb-5 lead text-black-50 font-weight-extralight">Thousands of easy‑to‑install free add‑ons, Collect leads, create contact forms, create subscriptions, automatically backup your site, and a whole lot more.</p>
								<h1>2000+ Free Premium Plugins are Ready</h1>
								<a href="demo-freelancer-works.html" class="button button-dark button-hero h-translatey-3 tf-ts button-reveal overflow-visible bg-dark text-right"><span>Get Started</span><i class="icon-line-arrow-right"></i></a>
								</div>
							</div>
					</div>
						</div>
					</div>


				<div class="clear"></div>

								<div class="clear"></div>

				<div id="services" class="section bg-transparent pt-5 mb-0 pb-0">

					<div class="container">
						<div class="row align-items-end mb-5 pb-0">
							<div class="col-lg-5 offset-lg-1">
								<h3 class="font-weight-bolder h1">Our Services</h3>
								<p>Codibu.com runs under GPL license</p>
							</div>
						</div>
						<div  class="row gutter-50 mb-5 align-items-stretch">
							@foreach($services as $service)
								<div class="col-md-4">
									<div class="card d-flex align-items-end flex-column p-4 border-0" style="background-color: {{ isset($service['color']) ? $service['color'] : '#EAEAEA' }}">
										<div class="mt-5"></div>
										<div class="mt-auto">
											<div class="card-body">
												<div class="d-flex align-items-center mb-4">
													<img src="{{ $service['image'] }}" height="50" alt="Image">
												</div>
												<h3 class="card-title font-weight-bolder">{{ $service['title'] }}</h3>
												<p class="card-text mb-0 mt-2 font-weight-light">{{ $service['description'] }}</p>
											</div>
										</div>
									</div>
								</div>
							@endforeach


							{{--<div class="col-md-4">
								<div class="card d-flex align-items-end flex-column p-4 border-0" style="background-color: #E2E8D8;">
									<div class="mt-5"></div>
									<div class="mt-auto">
										<div class="card-body">
											<div class="d-flex align-items-center mb-4">
												<img src="{{ URL::asset('assets/landing/demos/freelancer/images/icons/sketch.svg') }}" height="50" alt="Image">
												<img src="{{ URL::asset('assets/landing/demos/freelancer/images/icons/xd.png') }}" height="50" alt="Image" class="ml-3">
											</div>
											<h3 class="card-title font-weight-bolder">1500+ Free Premium Websites Templates</h3>
											<p class="card-text mb-0 mt-2 font-weight-light">No matter your site or your style, there’s a beautiful, pro layout waiting for you.</p>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="card d-flex align-items-end flex-column p-4 border-0" style="background-color: #C2DFEC;">
									<div class="mt-5"></div>
									<div class="mt-auto">
										<div class="card-body">
											<img src="{{ URL::asset('assets/landing/demos/seo/images/icons/social.svg') }}" alt="Image" class="mb-4" height="50">
											<h3 class="card-title font-weight-bolder">2000+ Free Premium Plugins</h3>
											<p class="card-text mb-0 mt-2 font-weight-light">Easy‑to‑install add‑ons, collect leads, create contact forms, create subscriptions, automatically backup your site, and a whole lot more.</p>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="card d-flex align-items-end flex-column p-4 border-0" style="background-color: #FADCE4">
									<div class="mt-5"></div>
									<div class="mt-auto">
										<div class="card-body">
											<img src="{{ URL::asset('assets/landing/demos/freelancer/images/icons/wp.png') }}" height="50" alt="Image" class="mb-4">
											<h3 class="card-title font-weight-bolder">Clean Wordpress Installation</h3>
											<p class="card-text mb-0 mt-2 font-weight-light">Your website is just the beginning. We’ve got the most powerful CMS and website tools you need to keep growing.</p>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="card d-flex align-items-end flex-column p-4 border-0" style="background-color: #E4E4E4">
									<div class="mt-5"></div>
									<div class="mt-auto">
										<div class="card-body">
											<img src="{{ URL::asset('assets/landing/demos/seo/images/icons/seo.svg') }}" height="50" alt="Image" class="mb-4">
											<h3 class="card-title font-weight-bolder">Easy Visual Editor</h3>
											<p class="card-text mb-0 mt-2 font-weight-light">Insert text, photos, forms, Yelp reviews, testimonials, maps, and more. Move them. Delete them. Play until it’s perfect. You already know how to do it!</p>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="card d-flex align-items-end flex-column p-4 border-0" style="background-color: #E5E3CE;">
									<div class="mt-5"></div>
									<div class="mt-auto">
										<div class="card-body">
											<img src="{{ URL::asset('assets/landing/demos/freelancer/images/icons/hosting.svg') }}" height="50" alt="Image" class="mb-4">
											<h3 class="card-title font-weight-bolder">Fast & Reliable Web Hosting</h3>
											<p class="card-text mb-0 mt-2 font-weight-light">We make sure your website is fast, secure & always up - so your visitors & search engines trust you.</p>
										</div>
									</div>
								</div>
							</div>

							<div class="col-md-4">
								<div class="card d-flex align-items-end flex-column p-4 border-0" style="background-color: #C9D6CF">
									<div class="mt-5"></div>
									<div class="mt-auto">
										<div class="card-body">
											<img src="{{ URL::asset('assets/landing/demos/freelancer/images/icons/plugins.png') }}" height="50" alt="Image" class="mb-4">
											<h3 class="card-title font-weight-bolder">24/7 Customer Serivce</h3>
											<p class="card-text mb-0 mt-2 font-weight-light">We consistently deliver a superior Customer Care experience to our customers.</p>
										</div>
									</div>
								</div>
							</div>--}}
						</div>
					</div>



				<div id="faq" class="section m-0" style="background: #f1efe5 url('{{ URL::asset('assets/landing/demos/freelancer/images/bg.svg') }}') no-repeat right center; padding-top: 240px">
					<div class="shape-divider" data-shape="wave-4" data-height="150" id="shape-divider-6017"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 140" preserveAspectRatio="none"><path class="shape-divider-fill" fill="#F8F7F2" d="M0 51.76c36.21-2.25 77.57-3.58 126.42-3.58 320 0 320 57 640 57 271.15 0 312.58-40.91 513.58-53.4V0H0z" opacity="0.3"></path><path class="shape-divider-fill" d="M0 24.31c43.46-5.69 94.56-9.25 158.42-9.25 320 0 320 89.24 640 89.24 256.13 0 307.28-57.16 481.58-80V0H0z" opacity="0.5"></path><path class="shape-divider-fill" d="M0 0v3.4C28.2 1.6 59.4.59 94.42.59c320 0 320 84.3 640 84.3 285 0 316.17-66.85 545.58-81.49V0z"></path></svg></div>
					<div class="container">
						<div class="row align-items-center">
							<div class="col-lg-5">
								<h3 class="font-weight-bolder h1 my-5">Frequently Asked<br>Questions</h3>
								<div class="accordion" data-collapsible="true">
                                    @foreach($faqs as $faq)
									<div class="accordion-header">
										<div class="accordion-icon">
											<i class="accordion-closed icon-line-plus color gradient-text gradient-red-yellow"></i>
											<i class="accordion-open icon-line-minus color gradient-text gradient-red-yellow"></i>
										</div>
										<div class="accordion-title">
                                         {{ $faq['section_title']}}
										</div>
									</div>
									<div class="accordion-content">{!! $faq['section_content'] !!}</div>
                                    @endforeach
								</div>
							</div>

							<div class="col-lg-7">
								<img src="{{ URL::asset('assets/landing/demos/freelancer/images/ask.svg') }}" alt="FAQs" class="px-5">
							</div>
						</div>
					</div>
				</div>

				<div class="clear"></div>

			</div>

		</section><!-- #content end -->

		<!-- Footer
		============================================= -->
		<footer id="footer" class="border-0" style="background-color: #C9D6CF;">

			<div class="container">
				<div class="footer-widgets-wrap  m-0">

					<div class="row justify-content-between">

						<div class="col-md-4">
							<div class="widget">

								<h3 class="h1 mb-5">Got a Project?<br>Let's Talk!</h3>
								<span class="text-black-50">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nobis quisquam aspernatur vero voluptas.</span>
								<a href="mailto:noreply@canvas.com" class="h4 text-dark mt-5 mb-4 d-block"><u>noreply@canvas.com</u> <i class="icon-line-arrow-right position-relative ml-2" style="top: 3px"></i></a>
								<div>
									<a href="http://facebook.com/semicolonweb" class="social-icon si-small si-colored si-facebook" target="_blank">
										<i class="icon-facebook"></i>
										<i class="icon-facebook"></i>
									</a>
									<a href="http://instagram.com/semicolonweb" class="social-icon si-small si-colored si-instagram" target="_blank">
										<i class="icon-instagram"></i>
										<i class="icon-instagram"></i>
									</a>
									<a href="http://youtube.com/semicolonweb" class="social-icon si-small si-colored si-youtube" target="_blank">
										<i class="icon-youtube"></i>
										<i class="icon-youtube"></i>
									</a>
									<a href="#" class="social-icon si-small si-colored si-flattr">
										<i class="icon-flattr"></i>
										<i class="icon-flattr"></i>
									</a>
								</div>

							</div>
						</div>

						<div class="col-md-5">
							<h3 class="h1 mb-5">Estimate your Project?</h3>
							<div class="form-widget" data-loader="button" data-alert-type="inline">

								<div class="form-result"></div>

								<form class="row mb-0" id="landing-enquiry" action="include/form.php" method="post" enctype="multipart/form-data">
									<div class="form-process"></div>
									<div class="col-12 form-group mb-4">
										<label>What is Your Name:</label>
										<input type="text" name="landing-enquiry-name" id="landing-enquiry-name" class="form-control border-form-control required" value="">
									</div>
									<div class="col-12 form-group mb-4">
										<label>Your Email Address Please:</label>
										<input type="email" name="landing-enquiry-email" id="landing-enquiry-email" class="form-control border-form-control required" value="">
									</div>
									<div class="col-12 form-group mb-4">
										<label>Tell more about your Project:</label>
										<textarea name="landing-enquiry-additional-requirements" id="landing-enquiry-additional-requirements" class="form-control border-form-control" cols="10" rows="3"></textarea>
									</div>
									<div class="col-12 d-none">
										<input type="text" id="landing-enquiry-botcheck" name="landing-enquiry-botcheck" value="" />
									</div>
									<div class="col-12">
										<button type="submit" name="landing-enquiry-submit" class="button h-translatey-3 bg-dark rounded-pill"><i class="icon-line-arrow-right m-0"></i></button>
									</div>

									<input type="hidden" name="prefix" value="landing-enquiry-">
								</form>
							</div>
						</div>

					</div>

				</div>
			</div>

		</footer><!-- #footer end -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-double-angle-up bg-white text-dark rounded-circle shadow"></div>

	<!-- External JavaScripts
	============================================= -->
	<script src="{{ URL::asset('assets/landing/js/jquery.js') }}"></script>
	<script src="{{ URL::asset('assets/landing/js/plugins.min.js') }}"></script>
    <script src="{{ URL::asset('assets/js/image-scroller.js') }}"></script>

	<!-- Footer Scripts
	============================================= -->
	<script src="{{ URL::asset('assets/landing/js/functions.js') }}"></script>

	<script>
		// Owl Carousel Scripts
		jQuery(window).on( 'pluginCarouselReady', function(){
			$('#oc-services').owlCarousel({
				items: 1,
				margin: 30,
				nav: false,
				dots: true,
				smartSpeed: 400,
				responsive:{
					576: { stagePadding: 30, items: 1 },
					768: { stagePadding: 30, items: 2 },
					991: { stagePadding: 150, items: 3 },
					1200: { stagePadding: 150, items: 3}
				},
			});
		});
	</script>

</body>
</html>
