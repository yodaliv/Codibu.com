@extends('frontend.layouts.app')
@section('page-title')
    Register
@endsection
@section('content')
    <div class="register-section user-account d-flex flex-wrap">
        <div class="left-section">
            <a href="{{ url('') }}" class="position-absolute">
                <img src="{{ asset('images/new-logo-white.svg')}}" alt="">
            </a>
            <div class="d-flex flex-column h-100 justify-content-center">
                <div class="key-services">
                    <h3>Key Services.</h3>
                    <div class="d-flex ">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>Pre-installed WordPress Environment</p>
                    </div>
                    <div class="d-flex">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>AWS hosting and Domain Purchase</p>
                    </div>
                    <div class="d-flex">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>Free 700+ pre-built demos (Auto Installer)</p>
                    </div>
                    <div class="d-flex">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>Free 5000+ plugins & themes & Free update</p>
                    </div>
                    <div class="d-flex">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>Free SEO analysis report</p>
                    </div>
                    <div class="d-flex">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>Best pricing packages</p>
                    </div>
                    <div class="d-flex">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>24/7 Customer Support</p>
                    </div>
                    <div class="d-flex">
                        <img class="align-self-start" src="{{ asset('images/icon/arrow-crate.svg') }}" alt="">
                        <p>Hire Professionals (Upcomming)</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="align-items-sm-center d-flex right-section">
            <div class="login-content">
                <a href="" class="logo d-block d-sm-none">
                    <img href="{{ url('') }}" src="{{ asset('images/new-logo.svg')}}" alt="">
                </a>
                <h2>Create your free account</h2>
                <div class="social-login d-flex flex-wrap">
                    <a href="{{ route('provider', 'google') }}" class="social-item border-gradient">
                        <img src="{{ asset('/images/icon/google.svg') }}" alt=""> Login with Googles
                    </a>
                    <a href="{{ route('provider', 'facebook') }}" class="social-item border-gradient me-0">
                        <img src="{{ asset('/images/icon/facebook.svg') }}" alt=""> Login with Facebook
                    </a>
                    <a href="{{ route('provider', 'amazon') }}" class="social-item border-gradient">
                        <img src="{{ asset('/images/icon/amazon.svg') }}" alt=""> Login with Amazon
                    </a>
                    <?php /* <a href="{{ route('instagram') }}" class="social-item border-gradient me-0">
                        <img src="{{ asset('/images/icon/instagram-color.svg') }}" alt=""> Login with
                        Instagram
                    </a> */ ?>
                </div>
                <div class="divider" data-text="Sign up with Email"></div>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="wrap-input100 group-input w-49 validate-input"
                             data-validate="First name is required">
                            <label for="first-name" class="form-label">First Name*</label>
                            <div class="border-gradient @error('first_name') is-invalid @enderror">
                                <input type="text" class="control-form " id="first-name" placeholder="First Name"
                                       value="{{ old('first_name') }}" name="first_name">
                                <img class="position-icon cursor-pointer"
                                     src="{{ asset('images/icon/check-gradient.svg') }}" alt="">
                            </div>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
										<i class="mdi mdi-account" aria-hidden="true"></i>
									</span>
                            @error('first_name')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                        <div class="wrap-input100 group-input w-49 validate-input"
                             data-validate="Last First name is required">
                            <label for="last-name" class="form-label">Last Name*</label>
                            <div class="border-gradient @error('last_name') is-invalid @enderror">
                                <input type="text" class="control-form " id="last-name" placeholder="Last Name"
                                       value="{{ old('last_name') }}" name="last_name">
                                <img class="position-icon cursor-pointer"
                                     src="{{ asset('images/icon/check-gradient.svg') }}" alt="">
                            </div>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
										<i class="mdi mdi-account" aria-hidden="true"></i>
									</span>
                            @error('last_name')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                    </div>

                    <div class="group-input wrap-input100 validate-input"
                         data-validate="Valid email is required: ex@abc.xyz">
                        <div class="group-input wrap-input100 validate-input"
                             data-validate="Valid email is required: ex@abc.xyz">
                            <label for="email" class="form-label">Email address*</label>
                            <div class="border-gradient @error('email') is-invalid @enderror">
                                <input type="text" class="control-form " id="email" placeholder="Email"
                                       value="{{ isset($_GET['email']) ? $_GET['email'] : old('email') }}" name="email" autofill="off" autocomplete="off">
                                <img class="position-icon cursor-pointer"
                                     src="{{ asset('images/icon/check-gradient.svg') }}" alt="">
                            </div>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
										<i class="mdi mdi-account" aria-hidden="true"></i>
									</span>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>

                        <div class="group-input wrap-input100 validate-input" data-validate="Password is required">
                            <label for="password" class="form-label">Password*</label>
                            <div class="border-gradient @error('password') is-invalid @enderror">
                                <input type="password" class="control-form " id="password" placeholder="Password"
                                       value="{{ old('password') }}" name="password">
                                <img class="eye-off pass-eye position-icon active cursor-pointer"
                                     src="{{ asset('images/icon/eye-off.svg') }}" alt="">
                                <img class="eye-on pass-eye position-icon cursor-pointer"
                                     src="{{ asset('images/icon/eye-on.svg') }}" alt="">
                            </div>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
										<i class="mdi mdi-account" aria-hidden="true"></i>
									</span>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>

                        <div class="group-input wrap-input100 validate-input"
                             data-validate="Password confirmation is required">
                            <label for="password_confirmation" class="form-label">Confirmation Password*</label>
                            <div class="border-gradient @error('password_confirmation') is-invalid @enderror">
                                <input type="password" class="control-form " id="password_confirmation"
                                       placeholder="Confirm Password" value="{{ old('password_confirmation') }}"
                                       name="password_confirmation">
                                <img class="eye-off pass-eye-2 position-icon active cursor-pointer"
                                     src="{{ asset('images/icon/eye-off.svg') }}" alt="">
                                <img class="eye-on pass-eye-2 position-icon cursor-pointer"
                                     src="{{ asset('images/icon/eye-on.svg') }}" alt="">
                            </div>
                            <span class="focus-input100"></span>
                            <span class="symbol-input100">
										<i class="mdi mdi-account" aria-hidden="true"></i>
									</span>
                            @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                            @enderror
                        </div>
                        <div class="group-input">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="remember" required>
                                <label class="form-check-label" for="remember">
                                    I agree to the
                                </label>
                                <a href="{{route('terms')}}" class="text-blue">Terms and Conditions</a> &
                                <a href="{{route('policy')}}"  class="text-blue">Privacy Policy</a>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap align-items-center group-input free-trail-search icon-insert">
                            <button class="bg-sky hover-animation" type="submit">Sign up
                                <img class="ms-3" src="{{ asset('images/icon/arrow-right-white.svg') }}" alt="">
                            </button>
                            <h4 class="signup-text">Already have an account?
                                <a href="{{ url('login') }}">Login</a>
                            </h4>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

