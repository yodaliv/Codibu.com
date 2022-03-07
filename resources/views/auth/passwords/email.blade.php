@extends('frontend.layouts.app')
@section('page-title')
    Reset Password
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
                <h2>Reset Password</h2>
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="group-input">
                        <label for="email" class="form-label">{{ __('E-Mail Address') }}</label>
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
                    <div class="d-flex flex-wrap justify-content-between align-items-center group-input free-trail-search icon-insert">
                        <button class="bg-sky hover-animation send-password" type="submit">Send Password
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
