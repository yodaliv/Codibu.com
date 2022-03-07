				<!-- HEADER -->
				<div class="header hor-header">
					<div class="container">
						<div class="d-flex">
							<a class="animated-arrow hor-toggle horizontal-navtoggle"><span></span></a>
							<div class="" @guest style="margin:auto;" @endguest>
								<a class="header-brand1" href="{{ url('/') }}">
									<img src="{{URL::asset('/images/new-logo.svg')}}" class="header-brand-img desktop-logo" alt="logo">

									<img src="{{URL::asset('/images/new-logo-white.svg')}}" class="header-brand-img light-logo" alt="logo">
								</a><!-- LOGO -->
							</div>
							@auth
								<div class="d-flex  ml-auto header-right-icons header-search-icon">
									<div class="dropdown d-md-flex header-settings notifications">
										@php $notifications = Auth::user()->notifications()->where('seen' , 0)->count(); @endphp
										<a  href="javascript:void(0);"  class="nav-link nav-link-header icon" data-notification="{{ $notifications }}" data-toggle="sidebar-right" data-target=".sidebar-right">
											<i class="fe fe-bell"></i>
											@if($notifications)
												<span class="nav-unread badge badge-success badge-pill">{{$notifications}}</span>
											@endif
										</a>
									</div><!-- NOTIFICATIONS -->
									<div class="dropdown profile-1">
										<a href="#" data-toggle="dropdown" class="nav-link pr-2 leading-none d-flex">
											<span class="avatar bg-primary brround avatar-md">{{ Auth::user()->slug }}</span>
										</a>
										<div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
											<div class="drop-heading">
												<div class="text-center">
													<h5 class="text-dark mb-0">{{ auth()->user()->name }}</h5>
													<small class="text-muted">{{ __('Starter membership' )}}</small>
												</div>
											</div>
											<div class="dropdown-divider m-0"></div>
											<a class="dropdown-item" href="{{ url('/profile') }}">
												<i class="dropdown-icon mdi mdi-account-outline"></i> Edit Profile
											</a>
{{--                                            <a class="dropdown-item" href="{{ url('change-password') }}">--}}
{{--                                                <i class="dropdown-icon mdi mdi-account-outline"></i> Change Password--}}
{{--                                            </a>--}}
											<a class="dropdown-item" href="{{ route('stripe-card.index') }}">
												<i class="dropdown-icon  zmdi zmdi-card"></i> Payment
											</a>
											<a class="dropdown-item" href="{{ route('billing.index') }}">
												<i class="dropdown-icon mdi  mdi-message-outline"></i> Billing & History
											</a>
<!--											<a class="dropdown-item" href="#">
												<span class="float-right"></span>
												<i class="dropdown-icon mdi  mdi-message-outline"></i> Inbox
											</a>
											<a class="dropdown-item" href="#">
												<i class="dropdown-icon mdi mdi-comment-check-outline"></i> Message
											</a>
											<div class="dropdown-divider mt-0"></div>
											<a class="dropdown-item" href="#">
												<i class="dropdown-icon mdi mdi-compass-outline"></i> Need help?
											</a>-->
                                            <a class="dropdown-item" role="button" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                                <i class="dropdown-icon mdi  mdi-logout-variant"></i> {{ __('Sign out') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
										</div>
									</div>
								</div>
							@endauth
						</div>
					</div>
				</div>
				<!-- End HEADER -->
