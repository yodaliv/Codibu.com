<div class="slider  bg-img-bubble choose-template">
    <div class="container">
        <div class="row d-flex justify-content-center pt-5 pb-5">
            <div class="col-12">
                    <div class="slider_header text-center">
                    <h3>Choose your template</h3>
                    <p>Choose your favorite website template form 700+ customizable templates. Create any kind of website. No code, no manuals, no limits.</p>
                   </div>
            </div>
        </div>
        <hr class="hr">
        <div class="row pt-5 pb-5 text-center" id="template">
            <div class="col-12">
                <div class="slider_btn d-flex justify-content-center">
                    @forelse($themes as $theme)
                        <a href="#" class="slider-btn {{ $loop->first ? 'active' : '' }}"
                           data-slider="{{ strtolower($theme['name']) ?? '' }}">{{ $theme['name'] ?? '' }}</a>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid-xxl">
        <div class="preloader-slider-section"><img src="{{ asset('images/preloader.gif') }}" alt=""></div>
        @forelse($themes as $site_type)
            <div id="{{ strtolower($site_type['name']) ?? '' }}" class="slider_item">
                <div class="owl-carousel">
                    @forelse($site_type['demos'] as $demo_theme)
                        <div class="owl-items">
                            <div onclick="takeAction(event)" data-url="{{ $demo_theme['url'] ?? '#' }}" class="img-box">
                                <img class="scrollable-image img-fluid"
                                     src="{{ $demo_theme['theme_image'] ?? '' }}"
                                     alt="">
                            </div>
                            <p class="text-center">{{ $demo_theme['name'] ?? '' }}</p>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        @empty
        @endforelse
        <div id="owl-number">1 / 10</div>
    </div>