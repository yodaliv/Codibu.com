@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
<!-- PAGE-HEADER -->
<div class="page-header">
    <div>
        <h1 class="page-title">{{__('My Domains')}}</h1>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">Domains</li>
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
                <table class="table card-table">
                    <tr class="border-bottom">
                        <th class="col-10">Domain</th>
                        <th class="col-2">Actions</th>
                    </tr>
                    @foreach($domains as $domain)
                    <tr class="border-bottom">
                        <td class="col-8">{{ $domain->domain }}</td>
                        <td class="col-2">
                            <div class="btn-list">
                                <a href="{{ route('domains.edit',$domain->domain) }}" class="btn btn-sm btn-primary">Details</a>
                            </div>
                        </td>
                        <td class="col-2">
                            <div class="btn-list">
                                <a href="{{ route('domains.dns',$domain->domain) }}" class="btn btn-sm btn-primary">DNS & Nameserver</a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
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
@endsection