<header class="header-wrapper position-sticky top-0 shadow-sm">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <nav class="navbar navbar-expand-lg p-0">
                    <div class="container-fluid p-0">
                        <a class="navbar-brand" href="{{ route('landing') }}">
                            <img class="d-none d-lg-block" src="{{ asset('images/new-logo.svg')}}"
                                 alt="">
                            <img class="d-block d-lg-none"
                                 src="{{ asset('images/new-logo-white.svg')}}" alt="">
                        </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                                data-bs-target="#navbarContent" aria-controls="navbarContent"
                                aria-expanded="false"
                                aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarContent">
                            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <a class="nav-link text-capitalize {{Route::currentRouteName() === 'landing' ? 'active' : ''}}"
                                       href="{{ route('landing') }}">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-capitalize"
                                       href="#template">Template</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link text-capitalize"
                                       href="#services">services</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link text-capitalize {{Route::currentRouteName() === 'pricing' ? 'active' : ''}}"
                                       href="{{ route('pricing') }}">pricing</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link text-capitalize"
                                       href="#pricing">pricing</a>
                                </li>
                                <!-- <li class="nav-item">
                                    <a class="nav-link text-capitalize {{Route::currentRouteName() === 'contact' ? 'active' : ''}}"
                                       href="{{ route('contact') }}" >contact</a>
                                </li> -->
                                <li class="nav-item">
                                    <a class="nav-link text-capitalize"
                                       href="#contact" >contact</a>
                                </li>
                            </ul>
                            <div class="align-items-baseline border-top d-flex flex-column flex-lg-row me-5 me-lg-0 ms-auto pt-3 pt-lg-0 border-top-lg-0">
                                @auth
                                    <a href="{{ route('sites.create') }}" class="nav-link text-capitalize mb-3 mb-lg-0">New
                                        site</a>
                                    <a href="{{ route('home') }}"
                                       class="nav-link text-capitalize mb-3 mb-lg-0">Dashboard</a>
                                @endauth
                                @guest
                                    <a href="{{ url('register') }}" class="nav-link text-capitalize mb-3 mb-lg-0">Register</a>
                                    <a href="{{ url('login') }}" class="nav-link text-capitalize mb-3 mb-lg-0">Login</a>
                                    <a class="d-none d-lg-block nav-link bg-sky me-0 hover-animation"
                                       href="#">Start a free trial</a>
                                @endguest
                            </div>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
@push('js')
    <script>
        let div           = document.createElement('div');
        let navbarToggler = document.querySelector(".navbar-toggler");
        navbarToggler.addEventListener("click", function () {
            navbarToggler.classList.toggle('nav-collapse');
            document.body.appendChild(div).classList.toggle('overley');
        });
        $('.nav-link').on('click', function(){
            $('.navbar-collapse').collapse('hide');
            navbarToggler.classList.remove('nav-collapse');
            document.body.appendChild(div).classList.remove('overley');
        });
    </script>
@endpush
