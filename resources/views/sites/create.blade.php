@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard_theme_arrows.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard_theme_circles.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/formwizard/smart_wizard_theme_dots.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/forn-wizard/css/demo.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/sweet-alert/sweetalert.css')}}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/select2.min.css')}}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/css/loader.css')}}" rel="stylesheet"/>
    <link href="{{ URL::asset('assets/css/theme-filter.css')}}" rel="stylesheet"/>
    <style>
        .theme-box:hover .image-holder {
            transition: 1s;
            bottom: 0;
        }
        .sw-theme-default .toolbar , .btn-finish{
            display: block;
        }
        .hidden {
            display: none!important;
        }
    </style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Create a new website</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Sites</a></li>
                <li class="breadcrumb-item active" aria-current="page">New Website</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-12">
            <form id="siteForm" action="{{  url()->current() }}" method="post">
                @csrf
                <div id="loader" class="lds-dual-ring hidden overlay"></div>
                <div id="smartwizard" class="mb-5 pb-5">
                    <ul class="nav">
                        <li><a class="nav-link" href="#step-1">Design</a></li>
                        <li><a class="nav-link" href="#step-2">Website</a></li>
                        <li><a class="nav-link" href="#step-3">Domain</a></li>
                        <li><a class="nav-link" href="#step-4">Hosting Plan</a></li>
                        <li><a class="nav-link" href="#step-5">Order Summary</a></li>
                    </ul>
                    <div class="alert errors hidden alert-danger" role="alert">
                        <ul></ul>
                    </div>
                    <div id="tab-steps" class="tab-content">
                        <div id="step-1" class="tab-pane" role="tabpanel">
                            <input type="hidden" name="theme_id">
                            <input type="hidden" name="demo_id" value="{{session('site_create_array')? session('site_create_array')["demo_id"] : ''}}">
                            <input type="hidden" name="theme_type" value="demo">
                            <div class="card mb-3">
                                <fieldset>
                                    <legend>Theme Filter</legend>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2"><label class="mb-0" for=""><b>Website Type</b></label>
                                            </div>
                                            @foreach($site_types as $site_key=> $type)
                                                <div class="form-check form-check-inline mb-2">
                                                    {{ Form::checkbox('theme_type_ids', $site_key, request()->site_type && in_array($site_key, json_decode(request()->site_type)) == true ? true : false, ['class' => 'form-check-input theme_type_ids', 'id'=>"theme_type_$site_key"]) }}
                                                    &nbsp;
                                                    {{ Form::label("theme_type_$site_key", $type, ['class' => 'form-check-label']) }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="mb-2"><label class="mb-0" for=""><b>Editor Type</b></label>
                                            </div>
                                            @foreach($editor_types as $editor_key=> $etype)
                                                <div class="form-check form-check-inline mb-2">
                                                    {{ Form::checkbox('editor_type', $editor_key, request()->etype && in_array($editor_key, json_decode(request()->etype)) == true ? true : false, ['class' => 'form-check-input editor_type', 'id'=>"editor_type_$editor_key"]) }}
                                                    &nbsp;
                                                    {{ Form::label("editor_type_$editor_key", $etype, ['class' => 'form-check-label']) }}
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            {{ Form::select('demo_name_ids', $demo_names, json_decode(request()->demo_name), ['class' => 'select2 custom-select', 'id' => 'demo_name_ids', 'multiple' =>true, 'data-placeholder' => 'Template Type']) }}
                                        </div>
                                        <div class="col-md-4">
                                            {{ Form::text('search', request()->keyword, ['readonly' => true, 'onfocus' => "this.removeAttribute('readonly')",'class' => 'form-control w-100', 'id'=>'inlineFormInputKeyword', 'placeholder' => 'Search Template']) }}
                                        </div>
                                        <div class="col-md-2">
                                            {{Form::button('Filter', ['class'=>'btn btn-primary do-theme-filter btn-block'])}}
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="themes row">
                                @forelse($themes as $row)
                                    @php
                                        $theme    = $row->network->theme;
                                    @endphp
                                    <div id="theme_{{$row->id}}"
                                         onmouseover="themeScrollImage(`theme_{{$row->id}}`)"
                                         class="theme-box col-md-4">
                                        <div class="card">
                                            <div class="image-holder">
                                                <img src="{{$row->theme_image}}" class="card-img-top scrollable-image"
                                                     alt="{{$theme->name}}">
                                            </div>
                                            <div class="card-body">
                                                <h5 class="card-title">{{$row->name}}</h5>
                                                <small class="text-muted cat">
                                                    <i class="icon icon-user text-info"></i> &nbsp;
                                                    <a href="{{$theme->developer_link}}"
                                                       class="developer">{{$theme->developer}}</a>
                                                    &nbsp;
                                                    <i class="icon icon-question text-info"></i> &nbsp;
                                                    <a href="{{$theme->info}}" class="info">
                                                        Theme Info
                                                    </a>
                                                </small>
                                                <div class="card-tags mb-2"></div>
                                                <p class="card-text">{{$row->description ?? '' }}</p>
                                                <button type="button" data-id="{{$row->id}}"
                                                        class="btn btn-primary select-theme mr-1 is_demo">
                                                    Select Demo
                                                </button>
                                                <a target="popup" href="http://{{$row->url}}"
                                                   onclick="openPopUp('http://{{$row->url}}')"
                                                    class="btn live-demo btn-dark">
                                                    {{ __('Live Demo') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="theme-box col-md-12">
                                        <h4 class="text-center card p-3 font-weight-bold text-danger">
                                            No theme found!
                                        </h4>
                                    </div>
                                @endforelse
                            </div>
                            <div class="theme-pagination mt-2">
                                {{$themes->links()}}
                            </div>
                        </div>
                        <div id="step-2" class="tab-pane" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('General info')}}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>{{ __('Website title') }} <small
                                                    class="text-danger">*</small></label>
                                            <input type="text" name="title" value="{{session('site_create_array')? session('site_create_array')["title"] : ''}}" class="form-control required" required>
                                            <span class="invalid-feedback hidden" role="alert">
                                                <strong>{{ __('Website title is required.') }}</strong>
                                            </span>
                                        </div>
                                        @guest
                                            <div class="alert alert-info" role="alert">
                                                <button type="button" class="close" data-dismiss="alert"
                                                        aria-hidden="true">×
                                                </button>
                                                <i class="fa fa-bell-o mr-2" aria-hidden="true"></i>Heads up! A
                                                TopRankOn account will be created with same credentials
                                            </div>
                                            <div class="form-group">
                                                <label>{{ __('Admin Email') }} <small
                                                        class="text-danger">*</small></label>
                                                <input type="email" name="email" class="form-control required" required>
                                                <span class="invalid-feedback hidden" role="alert">
                                                    <strong>{{ __('Admin email is required.') }}</strong>
                                                </span>
                                            </div>
                                        @endguest
                                        <div class="form-group">
                                            <label>{{ __('Admin Password') }} <small
                                                    class="text-danger">*</small></label>
                                            <input type="password" name="password"  value="{{session('site_create_array')? session('site_create_array')["admin_password"] : ''}}" class="form-control required"
                                                   required>
                                            <span class="invalid-feedback hidden" role="alert">
                                                <strong>{{ __('Admin password is required.') }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="step-3" class="tab-pane" role="tabpanel">

                            <input type="hidden" name="domain" value="{{session('site_create_array')? session('site_create_array')["domain"] : ''}}">
                            <input type="hidden" name="domain_type" value="{{session('site_create_array')? session('site_create_array')["domain_type"] : ''}}">
                            <input type="hidden" name="domain_price" value="{{session('site_create_array')? session('site_create_array')["domain_price"] : ''}}">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">{{ __('Domain info')}}</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <input type="text" class="form-control width-100" id="domain-name"
                                                   placeholder="Write the domain you own or want to purchase..">
                                            <span class="text-danger" id="domainNameError"></span>
                                            <p></p>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-primary do-domain-search btn-block">
                                                Search
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="domains row">
                                <div id="cloneable_domain" class="domain-box col-md-12 mt-3" style="display:none;">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="button-controls pull-right">
                                                <button type="button" style="display:none;"
                                                        class="btn btn-info i-own-domain select-domain mr-1">{{ __('I own the domain') }}</button>
                                                <button type="button" style="display:none;"
                                                        class="btn btn-primary purchase-domain select-domain mr-1">{{ __('Purchase domain') }}</button>
                                            </div>
                                            <h5 class="card-title  mb-1"></h5>
                                            <h6 class="card-title  mb-1"></h6>
                                            <p class="d-available text-primary"
                                               style="display:none;">{{ __('Domain available') }}</p>
                                            <p class="d-not-available text-danger"
                                               style="display:none;">{{ __('Domain not available') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div id="step-4" class="tab-pane" role="tabpanel">
                            <input type="hidden" name="plan" value="{{session('site_create_array')? session('site_create_array')["plan_id"] : ''}}">
                            <div class="row">
                                @foreach($plans as $plan)
                                    <div class="col-md-4">
                                        <div class="card text-white plan-{{$plan->id}} {{$plan->color}}">
                                            <div class="card-body">
                                                <h5 class="card-title mb-3">
                                                    <span id="planName" class="pull-left">{{ $plan->name}}</span>
                                                    <span id="planPrice"
                                                          class="pull-right">Price : {{ $plan->price }}$</span>
                                                    <div class="clear"></div>
                                                </h5>
                                                <h5 class="card-title mb-3">
                                                    <span class="pull-left"></span>
                                                    <span id="planDiscount" class="pull-right"> Discount : {{ $plan->discount_percentage }}%</span>
                                                    <div class="clear"></div>
                                                </h5>
                                                <h5 class="card-title mb-3">
                                                    <span class="pull-left"></span>
                                                    <span id="planCurrentPrice" class="pull-right"> Current Frice : {{ $plan->discount_percentage>0 ?$plan->price_after_discount: $plan->price}}$</span>
                                                    <div class="clear"></div>
                                                </h5>
                                                <ul>
                                                    <li><strong>Processor</strong>
                                                        : {{optional($plan->bundle)->cpuCount}} Vcpu
                                                    </li>
                                                    <li><strong>Memory (GiB)</strong>
                                                        : {{optional($plan->bundle)->ramSizeInGb}}
                                                    </li>
                                                    <li><strong>Storage</strong>
                                                        :  {{optional($plan->bundle)->diskSizeInGb}}
                                                    </li>
                                                    <li><strong>Transfer</strong>
                                                        :  {{optional($plan->bundle)->transferPerMonthInGb}}
                                                    </li>
                                                    @foreach($plan->specs as $spec)
                                                        <li><b>{{ $spec->spec->name }}</b> : {{ $spec->value }}</li>
                                                    @endforeach
                                                </ul>
                                                <p class="card-text mt-3">{{ __($plan->description) }}</p>
                                                <button data-id="{{ $plan->id }}" type="button"
                                                        class="btn btn-default btn-block mt-3 PlanButton">{{ __('Start now') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div id="step-5" class="tab-pane" role="tabpanel">
                            <div class="card mb-3 cart">
                                <div class="card-header">
                                    <h3 class="card-title">{{__('TopRankOn order summary')}}</h3>
                                </div>
                                <div class="card-body">
                                    <p>{{ __("Here's a breakdown for your order") }}</p>
                                    <ul class="list-group">
                                        <li class="list-group-item">
                                            Theme &nbsp; <b class="theme-name"></b>
                                            <span class="badgetext h4 mb-0">Free</span>
                                        </li>
                                        <li class="list-group-item">
                                            <input type="hidden" name="session_domain" value="{{session('site_create_array')? session('site_create_array')["domain"] : ""}}">
                                            <input type="hidden" name="session_domain_price" value="{{session('site_create_array')? session('site_create_array')["domain_price"] : ""}}">
                                            Domain &nbsp; <b class="domain-name"></b>
                                            <span class="badgetext domain-price h4 mb-0"></span>
                                        </li>
                                        <li class="list-group-item">
                                            <input type="hidden" name="session_plan_name" value="{{session('plan_name')? session('plan_name') : ""}}">
                                            Plan <span class="badgetext h4 mb-0 plan-name"></span>

                                        </li>
                                        <li class="list-group-item">
                                            <input type="hidden" name="session_plan_price" value="{{session('plan_price')? session('plan_price') : ""}}">
                                            Plan Price <span class="badgetext h4 mb-0 plan-price"></span>
                                        </li>
                                        <li class="list-group-item">
                                            <input type="hidden" name="session_plan_discount" value="{{session('plan_discount')? session('plan_discount') : ""}}">
                                            Plan Discount <span class="badgetext h4 mb-0 plan-discount"></span>
                                        </li>
                                        <li class="list-group-item d-flex align-items-center justify-content-between flex-wrap">
                                            Coupon Code &nbsp; <b class="copon_code"></b>
                                            <span class="alert-danger p-2 font-weight-bold" id="coupon_errors" style="color:red; display: none"></span>
                                            <span class="alert-success p-2 font-weight-bold" id="coupon_success" style="color:green; display: none"></span>
                                            <div class="coupon-div">
                                                <input type="text" class="form-control" name="coupon_code" value="{{session('site_create_array')? (isset(session('site_create_array')["coupon_code"])? session('site_create_array')["coupon_code"] : '') : ''}}"
                                                       id="coupon_code" placeholder="apply coupon here..">
                                            </div>
                                            <!-- <input type="hidden" name="val_price" id="val_price" value=""> -->
                                        </li>
                                        <li class="list-group-item">
                                            <input type="hidden" name="session_coupon_discount" value="{{session('site_create_array')? session('site_create_array')["coupon_discount"] : ""}}">
                                            Coupon Discount <span class="badgetext h4 mb-0 coupon-discount"></span>
                                        </li>

                                        <li class="list-group-item">
                                            Final Price
                                            <span class="badgetext h4 mb-0 final-price"></span>
                                        </li>
                                    </ul>
                                </div>
                                <div class="card-footer add-payment">
                                    <div class="card-title">Payment Method</div>
                                    <div class="form-group" id="toggler">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            @foreach ($paymentPlatforms as $paymentPlatform)
                                                <label
                                                    class="btn btn-outline-secondary rounded m-2 p-1"
                                                    data-target="#{{ $paymentPlatform->name }}Collapse"
                                                    data-toggle="collapse"
                                                >
                                                    <input
                                                        type="radio"
                                                        id="{{strtolower($paymentPlatform->name)}}_payment"
                                                        name="payment_platform"
                                                        value="{{ $paymentPlatform->name }}"
                                                        required
                                                    >

                                                    <img id="{{ strtolower($paymentPlatform->name) }}_image"
                                                         class="img-thumbnail h-60"
                                                         src="{{ asset($paymentPlatform->image) }}">
                                                </label>
                                            @endforeach
                                        </div>
                                        @foreach ($paymentPlatforms as $paymentPlatform)
                                            <div
                                                id="{{ $paymentPlatform->name }}Collapse"
                                                class="collapse"
                                                data-parent="#toggler"
                                            >
                                                @includeIf ('components.' . strtolower($paymentPlatform->name) . '-collapse')
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="alert create-success hidden alert-success" role="alert">
                                {{ __('Your order has been sent. Site creation takes up to 15 minutes and you will be notified through email once it\'s completed..')}}
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
@endsection
@section('js')
    <script type="text/javascript">
        //iframe modal view the demo website
        const showDemoWebsiteInIframe = url => {
            $(".iframe-modal").addClass("show-iframe");
            document.getElementById('live-demo-iframe').src = `https://${url}`;
        }

        //removeIframe
        const removeIframe = () => $(".iframe-modal").removeClass("show-iframe");

        //theme filter Image Scrolling
        const themeScrollImage = selector => $(`#${selector} .image-holder`).scrollImage();

        $(document).ready(function (e) {
            var plan_id = $("input[name=plan]").val();
            var price   = $(".plan-" + plan_id).find('.card-title .pull-right').text();
            console.log(plan_id);
            $(".final-price").text(price);
            $('#siteForm').prop('autocomplete', 'off');
            $('input').prop('autocomplete', 'off');
            $('select').prop('autocomplete', 'off');
            $('#toggler img').click(function () {
                if ($(this).attr('id') == 'stripe_image') {
                    if (!$('#payment_method').is(':checked')) {
                        $(".btn-finish").attr('disabled', 'disabled');
                    } else {
                        $(".btn-finish").removeAttr('disabled');
                    }
                } else if ($(this).attr('id') == 'paypal_image') {
                    $(".btn-finish").removeAttr('disabled');
                } else if ($(this).attr('id') == 'amazon_image') {
                    $(".btn-finish").removeAttr('disabled');
                    $("#AmazonPayButton").hide();
                }
            });

        });
        // Demo popup
            function openPopUp(url) {
            var windowWidth = 1280; //screen.width - screen.width/98;
            var windowHeight = 600; //screen.height - 100;
            var left = (screen.width - windowWidth) / 2;
            var top = (screen.height - windowHeight) / 4;
            var myWindow = window.open(url,'_blank','width=' + windowWidth + ', height=' + windowHeight + ', top=' + top + ', left=' + left);
        }
    </script>
    <script src="{{ URL::asset('assets/plugins/accordion-Wizard-Form/jquery.accordion-wizard.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/chart/utils.js') }}"></script>
    <script src="https://unpkg.com/smartwizard@5/dist/js/jquery.smartWizard.min.js" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/plugins/select2/select2.full.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/formwizard/fromwizard.js') }}"></script>
    <script src="{{ URL::asset('assets/js/image-scroller.js') }}"></script>
    <script src="{{ URL::asset('assets/js/site.create.js') }}"></script>
    <script src="{{ URL::asset('assets/js/theme-filter.js') }}?v=0.2"></script>
    <script>
        const hasPayment = $("#has-payment").val();
        // Toolbar extra buttons
        const btnFinish = $('<button></button>').text('Place your order')
            .addClass('btn btn-primary pull-right btn-finish')
            .attr('type', 'submit');

        if (hasPayment === 'no') {
            btnFinish.attr('disabled', 'disabled');
        }

        var selected = {{session()->has('site_create_array')? 4 : 0 }}
        const btnReset = $('<a></a>').text('Reset')
            .addClass('btn btn-danger text-white')
            .on('click', function () {

                $.ajax({
                    url     : base_url + '/form-reset',
                    type    : 'GET',
                    success : function (res) {

                        $('#smartwizard').smartWizard("reset")
                        //selected = 0
                        location.reload()
                    }
                });
            });
        // Smart Wizard
        $('#smartwizard')
            .smartWizard({
            selected        : selected,
            theme           : 'default',
            transitionEffect: 'fade',
            showStepURLhash : false,
            enableURLhash   : true,
            backButtonSupport: true, // Enable the back button support

            anchorSettings: {
                anchorClickable: true, // Enable/Disable anchor navigation
                enableAllAnchors: false, // Activates all anchors clickable all times
                markDoneStep: true, // Add done state on navigation
                markAllPreviousStepsAsDone: true, // When a step selected by url hash, all previous steps are marked done
                removeDoneStepOnNavigateBack: false, // While navigate back done step after active step will be cleared
                enableAnchorOnDoneStep: true // Enable/Disable the done steps navigation
            },
            toolbarSettings : {
                toolbarPosition      : 'bottom', // none, top, bottom, both
                toolbarButtonPosition: 'left', // left, right, center
                showNextButton       : true, // show/hide a Next button
                showPreviousButton   : true, // show/hide a Previous button
                toolbarExtraButtons  : [btnFinish {{session()->has('site_create_array')? ', btnReset' : ''  }}] // Extra buttons to show on toolbar, array of jQuery input/buttons elements
            },
            lang: { // Language variables for button
              previous: 'Previous-step'
            },
            disabledSteps: [], // Array Steps disabled
        });
        $(".sw-btn-next").addClass('btn-primary');
        $(".sw-btn-prev").addClass('btn-info');
    </script>
@endsection
