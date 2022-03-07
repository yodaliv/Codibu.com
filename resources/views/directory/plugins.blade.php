@extends('layouts.master')
@section('css')
    <link href="{{ asset('assets/css/searching-filtering.css')}}" rel="stylesheet"/>
    <style>
        .toggle-text {
            max-width: 400px;
            margin: 50px auto;
            text-align: center;
        }

        .toggle-text-content span {
            display: none;
        }

        .toggle-text-link {
            display: block;
            margin: 20px 0;
        }
    </style>
@endsection
@section('page-header')
    <!-- PAGE-HEADER -->
    <div class="page-header">
        <div>
            <h1 class="page-title">{{__('Plugin directory')}}</h1>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">Directory</a></li>
                <li class="breadcrumb-item active" aria-current="page">Plugins</li>
            </ol>
        </div>
    </div>
    <!-- PAGE-HEADER END -->
@endsection
@section('content')
    <!-- ROW-1 OPEN -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-3">
                <fieldset>
                    <legend>Plugin Searching</legend>
                    <!--<form action="" method="get">-->
                    <div class="row">
                        <div class="col-md-10">
                            {{ Form::text('keyword', request()->keyword, ['readonly' => true, 'onfocus' => "this.removeAttribute('readonly')",'class' => 'form-control w-100', 'id'=>'inlineFormInputKeyword', 'placeholder' => 'Keyword for name, author, description..']) }}
                        </div>
                        <div class="col-md-2">
                            {{ Form::hidden('page', request()->page??1) }}
                            {{ Form::button('<i class="fa fa-search"></i> Search', ['type' => 'submit', 'class' => 'btn btn-primary do-plugin-filter btn-block'])}}
                        </div>
                    </div>
                    <!--</form>-->
                </fieldset>
            </div>
            <div class="themes row">
                @forelse($lists as $list)
                    <div id="plugin-{{$list->id}}" class="theme-box col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title" style="min-height: 50px">{{$list->name}}</h5>
                                <small class="text-muted cat">
                                    <i class="icon icon-user text-info"></i> &nbsp;
                                    <a class="developer"></a> &nbsp;
                                    <i class="icon icon-question text-info"></i> &nbsp;
                                    <a class="info" href="{{$list->info}}">Theme Info</a>
                                </small>
                                <p class="card-text truncate"
                                   style="min-height: 103px;" id="collapse-{{ $list->id}}">
                                    {!! $list->description !!}
                                    @if(strlen($list->description)>130)
                                        <span class="toggle-text-content">
                                        <a href="javascript:" class="toggle-text-link helpicon"
                                           onclick="readMoreLess(`plugin-{{$list->id}}`)">Read more</a>
                                    </span>
                                    @endif
                                </p>
{{--                                <a href="{{$list->s3_url}}" download class="btn btn-dark download-plugin mr-1 w-100">--}}
{{--                                    {{ __('Download Plugin') }}--}}
{{--                                </a>--}}
                                <form action="{{ url('/directory/download') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="item_type" value="plugin">
                                    <input type="hidden" name="item_id" value="{{$list->id}}">
                                    <input type="hidden" name="item_version" value="{{$list->versions[0]->id}}">
                                    <button type="submit"
                                            class="btn btn-dark btn-block download-theme mr-1"
                                            data-id="{{$list->id}}">{{ __('Download Theme') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="theme-box col-md-12">
                        <h4 class="text-center card p-3 font-weight-bold text-danger">No Plugin found!</h4>
                    </div>
                @endforelse
            </div>
            <div class="plugin-pagination mt-2 mb-5">
                {{$lists->links()}}
            </div>
        </div>
    </div>
    <!-- ROW-1 CLOSED -->
@endsection
@section('js')
    <script>
        $("body").on('click', '.plugin-pagination .page-link', function (e) {
            getLists($(this).data('page'));
        });
        $(".do-plugin-filter").on('click', function (e) {
            getLists(1);
        });
        $("#inlineFormInputKeyword").keyup(function (event) {
            if (event.keyCode === 13) {
                $(".do-plugin-filter").click();
            }
        })

        function getLists(page) {
            var keyword          = $('#inlineFormInputKeyword').val();
            let params           = `keyword=${keyword}&page=${page}`
            window.location.href = `/directory/plugins/list?${params}`;
        }

        function readMoreLess(id) {
            let btn_selector = $(`#${id} .helpicon`);
            const text       = btn_selector.text();
            $(`#${id} .truncate`).toggleClass('open');
            btn_selector.text(text === "Read less" ? "Read more" : "Read less");
        }
    </script>
@endsection
