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
            <div class="card-header">
                <h1>DNS & NAMESERVER</h1>
            </div>
            @isset($domain['name_servers'])
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table card-table">
                        @foreach($domain['name_servers'] as $name_servers)
                        <tr class="border-bottom">
                            <td class="col-12">{{ $name_servers }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            @endisset
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