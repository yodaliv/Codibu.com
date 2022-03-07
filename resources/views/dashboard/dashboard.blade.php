@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/plugins/morris/morris.css')}}" rel="stylesheet">
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Dashboard</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </div>
        <div class="ml-auto pageheader-btn">
            <a href="{{ route('sites.create') }}" class="btn btn-success btn-icon text-white mr-2">
                <span> <i class="fe fe-plus"></i></span> Build a website
            </a>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-value-text text-success">{{$user_balance}}</div>
                <div class="card-icon-symbol bg-success">
                    <i class="fa fa-dollar bg-white text-success box-success-shadow"></i>
                </div>
                <h2 class="bg-success mb-0 text-white text-center pb-5 card-title-text">Account Balance</h2>
                <a role="button" href="{{ url('payment') }}" class="btn btn-link text-success btn-icon">
                    <span><i class="fe fe-eye"></i></span> View Detail
                </a>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-value-text text-primary">{{ count($sites) }}</div>
                <div class="card-icon-symbol bg-primary">
                    <i class="fa fa-chrome bg-white text-primary box-primary-shadow"></i>
                </div>
                <h2 class="bg-primary mb-0 text-white text-center pb-5 card-title-text">Active Website</h2>
                <a role="button" href="{{ url('sites') }}" class="btn btn-link text-primary btn-icon">
                    <span><i class="fe fe-eye"></i></span> View Detail
                </a>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="card">
                <div class="card-value-text text-secondary">{{ $themes_and_pugins }}</div>
                <div class="card-icon-symbol bg-secondary">
                    <i class="fa fa-files-o bg-white text-secondary box-secondary-shadow"></i>
                </div>
                <h2 class="bg-secondary mb-0 text-white text-center pb-5 card-title-text">Themes & Plugins</h2>
                <div class="d-flex">
                    <a role="button" href="{{ url('/directory/themes/list') }}"
                       class="btn btn-link text-secondary btn-icon w-50">
                        <span><i class="fe fe-eye"></i></span> View Themes
                    </a>
                    <a role="button" href="{{ url('/directory/plugins/list') }}"
                       class="btn btn-link text-secondary btn-icon w-50">
                        <span><i class="fe fe-eye"></i></span> View Plugins
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="dashboard">
        <div class="col-lg-12" id="recentlylisted">
            <div class="card">
                <div class="card-header justify-content-between">
                    <h2 class="page-title text-primary">My Sites</h2>
                </div>
                <div class="card-body">
                    <recently-listed-component sites_data="{{$sites}}" ></recently-listed-component>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{asset('js/dashboard.js')}}"></script>
    <script>
        //site paginate button click
        $("body").on('click', '.pagination-center .page-link', function (e) {
            getSites($(this).data('page'));
        });

        function getSites(page = 1) {
            let params           = `page=${page}`
            window.location.href = `/home?${params}`;
        }

        //
        var counted = 0;
        $(window).scroll(function () {
            var oTop = $('#recentlylisted').offset().top - window.innerHeight;
            if (counted == 0 && $(window).scrollTop() > oTop) {
                $('.count').each(function () {
                    var $this   = $(this),
                        countTo = $this.attr('data-count');
                    $({countNum: $this.text()}).animate({countNum: countTo}, {
                        duration: 2000,
                        easing  : 'swing',
                        step    : function () {
                            $this.text(Math.floor(this.countNum));
                        },
                        complete: function () {
                            $this.text(this.countNum);
                        }
                    });
                });
                counted = 1;
            }
        });
    </script>
@endsection
