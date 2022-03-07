@extends('layouts.master')
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{__('My websites')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Sites</li>
            </ol>
        </div>
        <div class="ml-auto pageheader-btn">
            <a href="{{ URL::route('sites.create') }}" class="btn btn-success btn-icon text-white mr-2">
                <span><i class="fe fe-plus"></i></span> {{ __('New Website')}}
            </a>
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
                            <th>Name</th>
                            <th>Theme/Demo</th>
                            <th>Plan</th>
                            <th>Domain</th>
                            <th>Server IP</th>
                            <th>Start Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                        @forelse($sites as $site)
                            <tr class="border-bottom">
                                <td>{{ $site->title }}</td>
                                <td>{{ $site->theme_type == 'demo' ? optional($site->demo)->name : optional($site->theme)->name }}</td>
                                <td>{{ optional($site->plan)->name }}</td>
                                <td>{{ $site->domain }}</td>
                                <td>{{ $site->server_ip }}</td>
                                <td>{{ $site->created_at->format('d F Y') }}</td>
                                <td><span class="text-capitalize px-3 py-2 badge badge-pill {{ $site->status == 'stopped' ? 'badge-danger' : 'badge-info' }}">{{  $site->status == 'completed' ? 'active' : $site->status  }}</span></td>
                                <td>
                                    <div class="btn-list">
                                        <a href="{{ url('sites/')  . '/' . $site->id }}" class="btn btn-sm btn-primary">
                                            <i class="fe fe-search mr-2"></i>Site detail
                                        </a>
                                        <!-- <a href="{{ url('sites/')  . '/' . $site->id }}" class="btn btn-sm btn-primary">
                                            <i class="fe fe-search mr-2"></i>Inspect
                                        </a> -->
                                        <!-- <button type="button" class="btn btn-sm btn-info">
                                            <i class="fe fe-message-circle mr-2"></i>Help
                                        </button> -->
                                        @if($site->status == 'stopped')
                                            <form class="terminate-restart" id="terminate-restart-{{ $site->id }}"
                                                  action="{{ route('sites.restart') }}" method="post">
                                                @method('POST')
                                                @csrf
                                                <input type="text" value="{{ $site->id }}" hidden name="site_id">
                                                <button type="button"
                                                        class="btn btn-sm btn-success btn-terminate-restart">
                                                    <i class="fe fe-play mr-2"></i>Restart
                                                </button>
                                            </form>
                                        @else
                                            <form class="terminate-delete" id="terminate-{{ $site->id }}"
                                                  action="{{ url('/sites', ['id' => $site->id]) }}"
                                                  method="post">
                                                @method('delete')
                                                @csrf
                                                <button data-id="{{ $site->id }}" type="submit" {{ $site->status != 'completed' ? 'disabled' : ''}}
                                                        class="btn btn-sm btn-danger btn-terminate">
                                                    <i class="fe fe-trash mr-2"></i>Terminate
                                                </button>
                                            </form>
                                        @endif

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="border-bottom">
                                <td colspan="100%" class="text-center"><h5>No site found!</h5></td>
                            </tr>
                        @endforelse
                    </table>
                    <div class="pagination-center my-4 text-center">
                        {{$sites->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
@endsection
@section('js')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        //site paginate button click
        $("body").on('click', '.pagination-center .page-link', function (e) {
            getSites($(this).data('page'));
        });

        function getSites(page = 1) {
            let params           = `page=${page}`
            window.location.href = `/sites?${params}`;
        }

        $("body").on('click', '.terminate-delete button', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            Swal.fire({
                title             : 'Are you sure?',
                text              : "Are you sure you want to temporary terminate this site?",
                icon              : 'warning',
                showCancelButton  : true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor : '#d33',
                confirmButtonText : 'Yes, terminate it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loader').show();
                    $("#terminate-" + id).submit();
                }
            })
        });

        $("body").on('click', '.btn-terminate-restart', function (e) {
            e.preventDefault();
            Swal.fire({
                title             : 'Are you sure?',
                text              : "Are you sure you want to restart this site?",
                icon              : 'info',
                showCancelButton  : true,
                confirmButtonColor: '#13bfa6',
                cancelButtonColor : '#d33',
                confirmButtonText : 'Yes, restart it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#loader').show();
                    $(this).closest('.terminate-restart').submit();
                }
            })
        });
    </script>
@endsection
