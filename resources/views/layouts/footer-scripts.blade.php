		<!-- BACK-TO-TOP -->
		<a href="#top" id="back-to-top"><i class="fa fa-angle-up"></i></a>
		<!-- JQUERY JS -->
		<script src="{{ URL::asset('assets/js/jquery-3.4.1.min.js') }}"></script>

		<!-- BOOTSTRAP JS -->
		<script src="{{ URL::asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
		<script src="{{ URL::asset('assets/plugins/bootstrap/js/popper.min.js') }}"></script>

		<!-- SPARKLINE JS-->
		<script src="{{ URL::asset('assets/js/jquery.sparkline.min.js') }}"></script>

		<!-- CHART-CIRCLE JS -->
		<script src="{{ URL::asset('assets/js/circle-progress.min.js') }}"></script>

		<!-- RATING STAR JS-->
		<script src="{{ URL::asset('assets/plugins/rating/jquery.rating-stars.js') }}"></script>

		<!-- C3.JS CHART JS -->
		<script src="{{ URL::asset('assets/plugins/charts-c3/d3.v5.min.js') }}"></script>
		<script src="{{ URL::asset('assets/plugins/charts-c3/c3-chart.js') }}"></script>

		<!-- INPUT MASK JS-->
		<script src="{{ URL::asset('assets/plugins/input-mask/jquery.mask.min.js') }}"></script>

		<!-- SIDE-MENU JS-->
		<script src="{{ URL::asset('assets/plugins/horizontal-menu/horizontal-menu.js') }}"></script>

        <!-- CUSTOM SCROLLBAR JS-->
		<script src="{{ URL::asset('assets/plugins/scroll-bar/jquery.mCustomScrollbar.concat.min.js') }}"></script>
		<script>
			var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            // ______________ PAGE LOADING
            $(window).on("load", function (e) {
                $("#global-loader").fadeOut("slow");
            })
		</script>

		@yield('js')

		<!-- SIDEBAR JS -->
		<script src="{{ URL::asset('assets/plugins/sidebar/sidebar.js') }}"></script>

		<script src="{{ URL::asset('assets/js/stiky.js') }}"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
		<!--CUSTOM JS -->
		<script src="{{ URL::asset('assets/js/custom.js') }}?v=0.1"></script>
		@php $alert_class = '' @endphp

		@if(Session::has('success'))
			@php $alert_class = 'success'; @endphp
		@elseif(Session::has('warning'))
			@php $alert_class = 'warning'; @endphp
		@elseif(Session::has('danger'))
			@php $alert_class = 'danger';  @endphp
		@elseif(Session::has('info'))
			@php $alert_class = 'info';  @endphp
		@endif

		<script>
		var message = '<?php echo $alert_class; ?>';
		$(document).ready(function(){
			if(message){
				if(message=='success'){
					toastr.success("<?php echo Session::get($alert_class) ?>");
				}else if(message=='danger'){
					toastr.error("<?php echo Session::get($alert_class) ?>");
				}
			}
		});
		</script>



