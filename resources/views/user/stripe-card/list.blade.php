@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{__('Payment methods')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Cards</li>
            </ol>
        </div>
        <div class="ml-auto pageheader-btn">
            <a href="{{ route('stripe-card.create') }}" class="btn btn-success btn-icon text-white mr-2">
                <span><i class="fe fe-plus"></i></span> {{ __('Add method')}}
            </a>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">
        @if(!count($methods))
            <div class="col-md-12 alert alert-info">
                {{ __('You don\'t have any active payment methods attached to your account.')}}
            </div>
        @endif
        @foreach($methods as $method)
            <div class="col-xl-4 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div style="font-size:1.8rem;">
                            <i class="fa fa-cc-{{$method['brand']}}"></i><br> **** **** **** {{ $method['last_four'] }}
                        </div>
                        <form id="method-{{ $method['id'] }}" class="floating-delete"
                              action="{{ route('stripe-card.destroy', $method['id']) }}" method="post">
                            @method('delete')
                            @csrf
                            <button data-id="{{ $method['id'] }}" type="button" class="btn btn-sm btn-outline-danger">
                                <i class="fe fe-trash"></i>
                            </button>
                        </form>

                    </div>
                    <div class="card-footer" style="font-size:0.7rem;">
                        <span class="pull-left"><b>Expiry date:</b> {{ $method['exp_month'] . '/' . $method['exp_year'] }}</span>
                        <span class="pull-right"><b>Holder Name:</b> {{$method['holder'] }}</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section('js')
    <script>
        $("body").on('click', '.floating-delete button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            swal({
                title     : "Warning",
                text      : "Are you sure you want to delete this payment method?",
                type      : "warning",
                buttons   : true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $("#method-" + id).submit();
                }
            });

        });
    </script>
@endsection
