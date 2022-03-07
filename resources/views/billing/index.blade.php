@extends('layouts.master')
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{__('Billing & History')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/' . $page='home') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Billing & History</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <div id="loader" class="lds-dual-ring hidden overlay"></div>
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table card-table">
                        <tr class="border-bottom">
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Payment Method</th>
                            <th>Refund</th>
                        </tr>
                        @forelse($histories as $history)
                            <tr class="border-bottom">
                                <td>{{ $history->created_at->format('d F Y') }}</td>
                                <td>{{ '$'.$history->payment_amount }}</td>
                                <td>{{ $history->payment_platform }}</td>
                                <td>{{ '$'.$history->refund_amount }}</td>
                            </tr>
                        @empty
                            <tr class="border-bottom">
                                <td colspan="100%" class="text-center"><h5>No history found!</h5></td>
                            </tr>
                        @endforelse
                    </table>
                    <div class="pagination-center my-4 text-center">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
@endsection
@section('js')
@endsection
