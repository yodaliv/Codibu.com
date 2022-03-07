@extends('layouts.master')
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">Add new payment methods</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/' . $page='stripe-card') }}">Payment methods</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add new</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                {{ __('1 USD Will be deducted from your credit card and added to your balance for verification.')}}
            </div>
            <div class="card">
                <div class="card-body">
                    <div id="stripe_div">
                        <input type="hidden" value="yes" id="do-redirect">
                        <small class="form-text text-muted" id="cardErrors" role="alert"></small>
                        <input type="hidden" value="" name="payment_method">
                        <div class="form-group">
                            <label>Card Holder Name *</label>
                            <input id="cardHolderName" type="text" class="form-control mb-2">
                        </div>
                        <div class="form-group">
                            <label class="mb-10">Card Information *</label>
                            <div id="card-element"></div>
                            <div id="card-errors" role="alert"></div>
                        </div>
                        <div class="form-group text-right mt-5">
                            <button id="submit" type="button" class="btn btn-primary">Add Card</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    @include('user.stripe-card.script')
@endsection
