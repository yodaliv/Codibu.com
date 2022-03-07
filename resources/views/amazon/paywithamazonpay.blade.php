@extends('layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/forn-wizard/css/demo.css')}}" rel="stylesheet">
@endsection
@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <!-- form card login -->
            <div class="card card-outline-secondary mt-4">
                <div class="card-header d-block">
                    <h3 class="mb-0">Amazon Pay</h3>
                </div>
                <div class="card-body" style="font-size: 15px">
                    <div class="form-group">
                        <strong>Site name: </strong> {{$site['title']}}
                    </div>
                    <div class="form-group">
                        <strong>Domain name: </strong> {{$site['domain']}}
                    </div>
                    <div class="form-group">
                        <strong>Plan name: </strong> {{$plan->name}}
                    </div>
                    <div class="form-group">
                        <strong>Total price:</strong> {{ "$". $site['total_price']}}
                    </div>
                    <a class="btn btn-primary btn-block" href="{{route('merchant.confirm.payment')}}" role="button">
                        Confirm Payment
                    </a>
                </div><!--/card-block-->
            </div><!-- /form card login -->
        </div>
    </div>
@endsection
