@extends('layouts.master')
@section('css')
<link href="{{ URL::asset('assets/plugins/morris/morris.css')}}" rel="stylesheet">
@endsection
@section('page-header')
						<!-- PAGE-HEADER -->
						<div class="page-header">
							<div>
								<h1 class="page-title">FAQ</h1>
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="{{  URL::route('home') }}">Home</a></li>
									<li class="breadcrumb-item active" aria-current="page">FAQ</li>
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
                                    @foreach($faqs as $faq)
                                        <div class="card-body">
                                            <h4 class="font-weight-semibold">{{ $faq['section_title']}}</h4>
                                            {!! $faq['section_content'] !!}
                                        </div>
                                    @endforeach
								</div>
							</div>
						</div>
                        <!-- ROW-1 CLOSED -->
                        
					</div>
				</div>
				<!-- CONTAINER CLOSED -->
			</div>
@endsection