		<!-- SIDE-BAR -->
		<div class="sidebar sidebar-right sidebar-animate notification-section">
			   <div class="p-4 border-bottom">
					<span class="fs-17">Notifications</span>
					@if(Auth::user()->notifications->where('is_clear',0)->count() > 0)
					<button class="btn btn-primary clear-all-notifi">Clear All</button>
					@endif
					<a href="javascript:void(0);" class="sidebar-icon text-right float-right" data-toggle="sidebar-right" data-target=".sidebar-right"><i class="fe fe-x"></i></a>
				</div>
				@foreach(Auth::user()->notifications->where('is_clear',0) as $notification)
					<div class="list d-flex align-items-center border-bottom p-4 notifications-div">
						<div class="">
							<span class="avatar bg-primary brround avatar-md">{{ Auth::user()->slug  }}</span>
						</div>
						<div class="wrapper w-100 ml-3">
							<p class="mb-0 d-flex">
								{!! $notification->message !!}
							</p>
							<div class="d-flex justify-content-between align-items-center">
								<div class="d-flex align-items-center">
									<i class="mdi mdi-clock text-muted mr-1"></i>
									<small class="text-muted ml-auto">{{ $notification->created_at->diffForHumans() }}</small>
									<p class="mb-0"></p>
								</div>
							</div>
						</div>
					</div><!-- LIST END -->
				@endforeach
			</div>
