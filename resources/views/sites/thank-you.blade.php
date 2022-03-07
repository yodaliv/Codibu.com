@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/forn-wizard/css/demo.css')}}" rel="stylesheet">
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row thanks-wrap">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show mt-5" role="alert">
                {{$success}}
            </div>
            <div class="text-center bg-white shadow mt-5">
                <h1 class="display-3 pt-5">Thank You!</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 mb-3">
            <div class="card">
                <h4 class="card-header font-weight-bold text-uppercase" style="justify-content: center;">Domain Info</h4>
                <div class="card-body">
                    <span class="font-weight-bolder text-body text-uppercase">Domain name: </span><span class="font-weight-bolder">{{ $site->domain }}</span><br>
                    <span class="font-weight-bolder text-body text-uppercase">Site name: </span><span class="font-weight-bolder">{{ $site->title }}</span><br>
                    <span class="font-weight-bolder text-body text-uppercase">Live demo name: </span><span class="font-weight-bolder">{{ $live_demo_name->name }}</span><br>
                </div>
            </div>
        </div>

        <div class="col-6 mb-3">
            <div class="card">
            <h4 class="card-header font-weight-bold text-uppercase" style="justify-content: center;">Site Login Info</h4>
            <div class="card-body">
                <span class="font-weight-bolder text-body text-uppercase">Login URL: </span><span class="font-weight-bolder">{{ $site->domain }}/wp-admin</span><br>
                <span class="font-weight-bolder text-body text-uppercase">Login User Name: </span><span class="font-weight-bolder">user</span><br>
                <span class="font-weight-bolder text-body text-uppercase">Login Password: </span><span class="font-weight-bolder">{{ $site->admin_password }}</span><br>
            </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6 mb-3">
            <div class="card">
            <h4 class="card-header font-weight-bold text-uppercase" style="justify-content: center;">Server hosting info and specification</h4>
            <div class="card-body">
                <span class="font-weight-bolder text-body text-uppercase">Plan Name: </span><span class="font-weight-bolder">{{ $specification->name }}</span><br>
                <span class="font-weight-bolder text-body text-uppercase">Description: </span><span class="font-weight-bolder">{{ $specification->description }}</span><br>
                <span class="font-weight-bolder text-body text-uppercase">Plan Price: </span><span class="font-weight-bolder">{{ "$".$specification->price }} per {{$specification->duration_count}} {{$specification->duration}}</span><br>
            </div>
            </div>
        </div>

        <div class="col-6 mb-3">
            <div class="card">
                <h4 class="card-header font-weight-bold text-uppercase" style="justify-content: center;">Payment info</h4>
                <div class="card-body">
                    <span class="font-weight-bolder text-body text-uppercase">Payment Type: </span><span class="font-weight-bolder">{{$site->platform}}</span><br>
                    <span class="font-weight-bolder text-body text-uppercase">Duration: </span><span class="font-weight-bolder">{{$specification->duration_count}} {{$specification->duration}}</span><br>
                    <span class="font-weight-bolder text-body text-uppercase">Discount: </span><span class="font-weight-bolder">{{ ($site->coupon_discount != 0) ? $site->coupon_discount."$" : '0' }}</span><br>
                    <span class="font-weight-bolder text-body text-uppercase">Final Price: </span><span class="font-weight-bolder">{{ "$".$site->total_price}} per {{$specification->duration_count}} {{$specification->duration}}</span><br>
                </div>
            </div>
        </div>
    </div>

    <div class="row thanks-wrap">

        <div class="col-12">
            <div class="page-header justify-content-center">
                <a class="btn btn-primary" href="{{route('sites.index')}}" role="button">Back to site
                    list
                </a>
            </div>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
@endsection
