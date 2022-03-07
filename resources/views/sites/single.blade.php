@extends('layouts.master')
@section('css')
@endsection
@section('page-header')
						<!-- PAGE-HEADER -->
						<div class="page-header">
							<div>
								<h1 class="page-title">{{ $site->title }}</h1>
								<ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/sites') }}">Sites</a></li>
									<li class="breadcrumb-item active" aria-current="page">Site overview</li>

								</ol>
							</div>
							<div class="ml-auto pageheader-btn">
                                <span class="badge badge-{{ $site->status_class }}">
                                    <i class="fe fe-bell"></i>&nbsp;
                                    {{ ucfirst($site->status) }}
                                </span>
							</div>
						</div>
						<!-- PAGE-HEADER END -->
@endsection
@section('content')
						<!-- ROW-1 OPEN -->
						<div class="row">
							<div class="col-3">
							    <div class="card">
                                    <div class="card-header">
										<div class="card-title">{{ __('Site Information') }}</div>
									</div>
                                    <div class="card-body">
                                        <ul>
											<li>
                                                <b><i class="fa fa-server" aria-hidden="true"></i> Server IP</b>: {{ $site->server_ip }}
                                            </li>
                                            <li>
                                                <b><i class="fa fa-chrome" aria-hidden="true"></i> Domain</b>: {{ $site->domain }}
                                            </li>
											<li>
                                                <b><i class="fa fa-briefcase" aria-hidden="true"></i> Site Title</b>: {{ $site->title }}
                                            </li>
											<li>
                                                <b><i class="fa fa-chrome" aria-hidden="true"></i> {{ __('Admin URL') }}</b>: {{$site->domain.'/wp-admin'}} 
                                                <a target="_blank" href="http://{{ $site->domain.'/wp-admin'}}"><small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>
                                            </li>
											<li>
                                                <b><i class="fa fa-user" aria-hidden="true"></i> Admin ID</b>: {{ $site->user->name }}
                                            </li>
											<li>
                                                <b><i class="fa fa-lock" aria-hidden="true"></i> Admin Password</b>: {{ $site->admin_password }}
                                            </li>
                                            <li>
                                                <b><i class="fa fa-clone" aria-hidden="true"></i> {{ __('Active demo') }}</b>: {{$site->demo->name}} 
                                                <a target="_blank" href="http://{{ $site->demo->url }}"><small><i class="fa fa-external-link" aria-hidden="true"></i></small></a>
                                            </li>
                                        </ul>
                                    </div>
								</div>
							</div>
							<div class="col-3">
							    <div class="card">
                                    <div class="card-header">
										<div class="card-title">{{ __('Plan Information') }}</div>
									</div>
                                    <div class="card-body">
                                        <!-- <ul>
                                            @foreach($site->plan->specs as $spec)
                                            <li><b>{{$spec->spec->name}}</b>: {{$spec->value}}</li>
                                            @endforeach
                                        </ul> -->
										<ul>
											<li><strong>Plan</strong>
												: {{$site->plan->name}}
											</li>
											<li><strong>Payment</strong>
												: {{'$'.$site->plan->price.' per month'}}
											</li>
											<li><strong>Payment method</strong>
												:  {{$site->platform}}
											</li>
											<li><strong>Payment date</strong>
												:  {{"Every 1st of the month."}}
											</li>
                                        </ul>
                                    </div>
								</div>
							</div>
							<div class="col-3">
							    <div class="card">
                                    <div class="card-header">
										<div class="card-title">{{ __('Name Server Information') }}</div>
									</div>
                                    <div class="card-body">
										<ul>
											<li><strong>Memory</strong>
												: {{($site->plan->bundle->ramSizeInGb)*1024}} MB
											</li>
											<li><strong>Processor</strong>
												: {{$site->plan->bundle->cpuCount}} Core
											</li>
											<li><strong>SSD Disk</strong>
												:  {{$site->plan->bundle->diskSizeInGb}} GB
											</li>
											<li><strong>Transfer</strong>
												:  {{($site->plan->bundle->transferPerMonthInGb)/1024}} TB
											</li>
                                        </ul>
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