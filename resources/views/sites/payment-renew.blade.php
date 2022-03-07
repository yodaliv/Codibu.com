@extends('layouts.master')
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
            <form id="renew-form" action="{{  url()->current() }}" method="post">
                @csrf
                <input type="hidden" name="site_id" value="{{request('site_id')}}">
                <div class="card mb-3 cart">
                    <div class="card-header">
                        <h3 class="card-title">{{__('TopRankOn Payment Renew for ') }}{{$site->title}}</h3>
                    </div>
                    <div class="card-body">
                        <p>{{ __("Here's a breakdown for your order") }}</p>
                        <ul class="list-group">
                            <li class="list-group-item">
                                Price
                                <span class="badgetext h4 mb-0 plan-price">{{$site->total_price}}$</span>
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
                                            class="payment_platform"
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
                        <div class="form-group" align='right'>
                            <button id="submitButton" type="button" class="btn btn-primary">{{ __('Renew')}}</button>
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
        let payment_platform = 'PayPal';
        $(document).ready(function (e) {
            $('#toggler img').click(function () {
                if ($(this).attr('id') == 'stripe_image') {
                    if (!$('#payment_method').is(':checked')) {
                        $(".btn-renew").attr('disabled', 'disabled');
                    } else {
                        $(".btn-renew").removeAttr('disabled');
                    }
                    payment_platform = 'Stripe';
                } else if ($(this).attr('id') == 'paypal_image') {
                    $(".btn-renew").removeAttr('disabled');
                    payment_platform = 'PayPal';
                } else if ($(this).attr('id') == 'amazon_image') {
                    $(".btn-renew").removeAttr('disabled');
                    payment_platform = 'Amazon';
                    $("#AmazonPayButton").hide();
                }
            });
        });
        $('button#submitButton').click(function () {
            if (payment_platform === 'Amazon') {
                $("#AmazonPayButton").click();
            } else {
                $('form#renew-form').submit();
            }
        });
    </script>
@endsection

