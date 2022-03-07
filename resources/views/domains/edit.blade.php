@extends('layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
@endsection
@section('page-header')
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{__('My Domains')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Domain Maintenance</li>
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
            <div class="table-responsive">
                <div class="card-header d-block">
                    <h1 class="page-title">SUMMARY</h1>
                    <P class="card-text">The summary displays information about your domain</P>
                </div>
                <div class="card-body">
                    @if(session()->has('status'))
                    <div class="alert alert-success">
                        <strong>Success!</strong> {{session()->get('status')}}
                    </div>
                    @endif
                    <div class="row">

                        <div class="col-6">
                            <div class="card">
                                <h5 class="card-header">DOMAIN SETTINGS</h5>
                                <div class="card-body">
                                    <form id="siteForm" action="{{  route('domains.update',request('domain')) }}" method="post">
                                        @csrf
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Auto Renew') }}</label>
                                            <input type="checkbox" class="form-control" @if($domain['renew_auto']) checked @endif data-toggle="toggle" data-size="mini" name="renew_auto">
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Domain Lock') }}</label>
                                            <input type="checkbox" class="form-control" @if($domain['locked']) checked @endif data-toggle="toggle" data-size="mini" name="locked">
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Primary Domain') }}</label>
                                            <input type="checkbox" class="form-control" disabled data-toggle="toggle" data-size="mini" name="">
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('LetsEncrypt Free SSL') }}</label>
                                            <input type="checkbox" class="form-control" disabled data-toggle="toggle" data-size="mini" name="">
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Enforce SSL') }}</label>
                                            <input type="checkbox" class="form-control" disabled data-toggle="toggle" data-size="mini" name="">
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer  d-flex justify-content-end">
                                    <button form="siteForm" type="submit" class="btn btn-primary">{{ __('RENEW DOMAIN')}}</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <h5 class="card-header">DOMAIN INFORMATION</h5>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Domain : ') }}</label>
                                            <label>{{$domain['domain']}}</label>
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Registered On : ') }}</label>
                                            <label>{{$domain['created_at']}}</label>
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Expires On : ') }}</label>
                                            <label>{{$domain['expires']}}</label>
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                            <label>{{ __('Renews On : ') }}</label>
                                            <label>{{$domain['renew_deadline']}}</label>
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                        </div>
                                        <div class="form-group d-flex justify-content-between">
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-muted">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
@endsection