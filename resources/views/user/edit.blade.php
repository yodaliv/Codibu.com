@extends('layouts.master')
@section('css')
<link href="{{ asset('assets/plugins/formwizard/smart_wizard.css')}}" rel="stylesheet">
<link href="{{ asset('assets/plugins/formwizard/smart_wizard_theme_arrows.css')}}" rel="stylesheet">
<link href="{{ asset('assets/plugins/formwizard/smart_wizard_theme_circles.css')}}" rel="stylesheet">
<link href="{{ asset('assets/plugins/formwizard/smart_wizard_theme_dots.css')}}" rel="stylesheet">
<link href="{{ asset('assets/plugins/forn-wizard/css/demo.css')}}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<style>
    .slow  .toggle-group { transition: left 0.7s; -webkit-transition: left 0.7s; }
    .fast  .toggle-group { transition: left 0.1s; -webkit-transition: left 0.1s; }
    .quick .toggle-group { transition: none;      -webkit-transition: none; }
    .popover-body { color: #212529; }
</style>
@endsection
@section('page-header')
						<!-- PAGE-HEADER -->
						<div class="page-header">
							<div>
								<h1 class="page-title">Edit Profile</h1>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="{{ url('/' . $page='home') }}">Dashboard</a></li>
									<li class="breadcrumb-item active" aria-current="page">Edit Profile</li>
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
                                        <form id="siteForm" action="{{  route('update.profile') }}" method="post">
                                            @method('post')
                                            @csrf
                                            <div class="form-group">
                                                <label>{{ __('Name') }}</label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" value="{{ $user->name }}" name="name">
                                                 @error('name')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Email address') }}</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" value="{{ $user->email }}" name="email">
                                                 @error('email')<div class="text-danger">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="form-group">
                                                <label>Phone</label>
                                                <input type="text" class="form-control" id="phone" name="phone" value="{{ optional($user->info)->phone }}">
                                            </div>
                                            <div class="form-group">
                                                <label>Street Address</label>
                                                <input type="text" class="form-control" name="street_address" value="{{ optional($user->info)->street_address }}">
                                            </div>
                                            <div class="form-row">
                                                <div class="form-group col-md-6">
                                                    <label>City</label>
                                                    <input type="text" class="form-control" name="city" value="{{ optional($user->info)->city }}">
                                                </div>
                                                <div class="form-group col-md-4">
                                                    <label>State</label>
                                                    <input type="text" class="form-control" name="state" value="{{ optional($user->info)->state }}">
                                                </div>
                                                <div class="form-group col-md-2">
                                                    <label>Zip</label>
                                                    <input type="text" class="form-control" name="zip" value="{{ optional($user->info)->zip }}">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="checkbox"  class="form-control"  @if($user->{'2fa'}) checked @endif data-toggle="toggle" data-size="mini" name="2fa">
                                                <span
                                                    class="badge badge-pill badge-dark"
                                                    data-container="body"
                                                    data-toggle="popover"
                                                    data-trigger="hover"
                                                    data-placement="top"
                                                    data-content="You may active this to make more secure your account. Every time, when you submit your login, you will get an email with 6 digits PIN number. After submitting this PIN number you will have successfully logged in "
                                                    style="cursor: pointer; margin: 10px"
                                                >?</span>
                                                <label>{{ __('Two Factor Authentication') }}</label>
                                            </div>
                                            <div class="form-group">
                                            <div class="form-check">
                                                <input
                                                    hidden
                                                    class="form-check-input position-static"
                                                    type="checkbox"
                                                    @if(old('change_password')) checked @endif
                                                    checked
                                                    name="change_password"
                                                    id="passwordCheck"
                                                    aria-expanded="true"
                                                    data-toggle="collapse"
                                                    data-target="#changePassword"
                                                    aria-controls="changePassword"
                                                >
                                                <label hidden="" class="form-check-label" for="passwordCheck">
                                                    {{ __('Change Password') }}
                                                </label>
                                            </div>
                                            <div id="changePassword"
                                                 class="collapse show @if(old('change_password')) show @endif"
                                                 aria-labelledby="changePassword"
                                                 data-parent="#passwordCheck">
                                                <div class="form-group">
                                                    <label class="col-form-label text-md-right">{{ __('Current Password')}}</label>
                                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" onfocus="this.removeAttribute('readonly');" readonly>
                                                    @error('current_password')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="password" class="col-form-label text-md-right">{{ __('New Password')}}</label>
                                                    <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" onfocus="this.removeAttribute('readonly');" readonly>
                                                    @error('new_password')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="password" class="col-form-label text-md-right">{{ __('New Confirm Password')}}</label>
                                                    <input type="password" class="form-control @error('new_confirm_password') is-invalid @enderror" name="new_confirm_password" onfocus="this.removeAttribute('readonly');" readonly>
                                                    @error('new_confirm_password')<div class="text-danger">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            </div>
                                            <div class="form-group" align='right'>
                                                <button type="submit" class="btn btn-primary">{{ __('Update Profile')}}</button>
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
@section('js')
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="{{asset('assets/js/jquery.mask.js')}}"></script>
    <script>
        $(document).ready(function() {
            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, '').length >= 10 ? '+0 (000) 000-0000' : '(000) 000-0000';
                },
                spOptions      = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(SPMaskBehavior.apply({}, arguments), options);
                    }
                };

            $('#phone').mask(SPMaskBehavior, spOptions);
        })
    </script>
@endsection
