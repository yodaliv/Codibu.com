@extends('frontend.layouts.app')
@section('page-title')
    Login
@endsection
@section('content')
    <div class="login-section user-account d-flex flex-wrap">
        <div class="left-section">
            <a href="{{ url('') }}" class="position-absolute">
                <img src="{{ asset('images/new-logo-white.svg')}}" alt="">
            </a>
            <div class="d-flex flex-column h-100 justify-content-center">
                <h2>One-Stop <br>WordPress <br>Solution<span class="text-blue">.</span></h2>
                <p>Enjoy outstanding packages and services as well as explore codibu for free.</p>
            </div>
        </div>
        <div class="align-items-sm-center d-flex right-section">
            <div class="login-content">
                <a href="" class="logo d-block d-sm-none">
                    <img href="{{ url('') }}" src="{{ asset('images/new-logo.svg')}}" alt="">
                </a>
                <h2>Login to your account</h2>
                <div class="social-login d-flex flex-wrap">
                    <a href="{{ route('provider', 'google') }}" class="social-item border-gradient">
                        <img src="{{ asset('/images/icon/google.svg') }}" alt=""> Login with Google
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
                    </a>*/ ?>
                </div>
                <div class="divider" data-text="Login with Email"></div>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="group-input">
                        <label for="email" class="form-label">Email address*</label>
                        <div class="border-gradient @error('email') is-invalid-error @enderror">
                            <input type="email" class="control-form" name="email" id="email"
                                   placeholder="{{ __('yourmail@mail.com') }}" value="{{ old('email') }}" required>
                            <img class="position-icon cursor-pointer"
                                 src="{{ asset('images/icon/check-gradient.svg') }}" alt="">
                        </div>
                        @error('email')
                        <div class="invalid-feedback-error">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="group-input">
                        <label for="password" class="form-label">Password*</label>
                        <div class="border-gradient position-relative @error('password') is-invalid-error @enderror">
                            <input type="password" name="password" required
                                   class="control-form" id="password"
                                   placeholder="********">
                            <img class="eye-off pass-eye position-icon active cursor-pointer"
                                 src="{{ asset('images/icon/eye-off.svg') }}" alt="">
                            <img class="eye-on pass-eye position-icon cursor-pointer"
                                 src="{{ asset('images/icon/eye-on.svg') }}" alt="">
                            @error('password')
                            <div class="invalid-feedback-error">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="group-input">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember"
                                   name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Remember Me') }}
                            </label>
                            <a href="{{ route('password.request') }}" class="float-end">Forgot Password</a>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap align-items-center group-input free-trail-search icon-insert">
                        <button class="bg-sky hover-animation" type="submit">Login
                            <img class="ms-3" src="{{ asset('images/icon/arrow-right-white.svg') }}" alt="">
                        </button>
                        <h4 class="signup-text">Don’t have an account?
                            <a href="{{url('/register')}}">Sign up</a>
                        </h4>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
