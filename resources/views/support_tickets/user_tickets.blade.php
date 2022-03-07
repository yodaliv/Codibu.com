@extends('layouts.master')
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">My Tickets</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">My Tickets</li>
            </ol>
        </div>
        <div class="ml-auto pageheader-btn">
            <a href="{{ URL::route('support-ticket.create') }}" class="btn btn-info btn-icon text-white mr-2">
                <span>
                    <i class="fe fe-plus"></i>
                </span> Create Support Ticket
            </a>
        </div>

    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
	<div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="loader lds-ring"></div>
                    <table class="table user_tickets">
                        <thead>
                            <tr>
                                <th>Category</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Last Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="user_tickets-pagination mt-2 mb-4"></div>
	        	</div>
	        </div>
	    </div>
	</div>
@endsection
@section('js')
<script src="{{ URL::asset('vendor\laravel-admin\moment\min\moment-with-locales.min.js') }}"></script>
<script src="{{ URL::asset('assets/js/user_tickets.js') }}"></script>
@endsection
