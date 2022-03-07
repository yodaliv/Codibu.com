@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard_theme_arrows.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard_theme_circles.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard_theme_dots.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/forn-wizard/css/demo.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Change password </h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/' . $page='home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Change password</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('change.password') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="password" class="col-form-label text-md-right">{{ __('Current Password')}}</label>
                            <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password">
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-form-label text-md-right">{{ __('New Password')}}</label>
                            <input id="new_password" type="password" class="form-control" name="new_password" autocomplete="current-password">
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-form-label text-md-right">{{ __('New Confirm Password')}}</label>
                            <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password">
                        </div>
                        <div class="form-group" align='right'>
                            <button type="submit" class="btn btn-primary">{{ __('Update Password')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
    </div>
    </div>
    <!-- CONTAINER CLOSED -->
    </div>
@endsection
