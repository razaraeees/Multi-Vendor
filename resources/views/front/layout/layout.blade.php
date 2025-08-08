<!doctype html>
<html lang="en">


<!-- Mirrored from codervent.com/shopingo/demo/shopingo_V2/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 22 Jul 2025 18:14:51 GMT -->
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!--favicon-->
<link rel="icon" href="{{ asset('assets/images/favicon-32x32.png') }}" type="image/png" />

<!--plugins-->
<link href="{{ asset('assets/plugins/OwlCarousel/css/owl.carousel.min.css') }}" rel="stylesheet" />
<link href="{{ asset('assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />

<!-- loader-->
<link href="{{ asset('assets/css/pace.min.css') }}" rel="stylesheet" />
<script src="{{ asset('assets/js/pace.min.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap CSS -->
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">

<!-- Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Albert+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet">

<!-- App Styles -->
<link href="{{ asset('assets/css/app.css') }}" rel="stylesheet">
<link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
<link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
{{-- <meta name="csrf-token" content="{{ csrf_token() }}"> --}}
<meta name="csrf-token" content="{{ csrf_token() }}">


<title>@yield('title', 'Shopingo - eCommerce HTML Template')</title>

</head>

<body>
	<!--wrapper-->
	<div class="wrapper">
		<!--start top header wrapper-->
        @include('front.layout.partials.header')
		<!--end top header wrapper-->
		<!--start slider section-->
       @yield('sidebar')
		<!--end slider section-->
		<!--start page wrapper -->
		<div class="page-wrapper">
			@yield('content')
		</div>
		<!--end page wrapper -->
		<!--start footer section-->
        @include('front.layout.partials.footer')

	
		<!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
		<!--End Back To Top Button-->
	</div>
	<!--end wrapper-->
	
	<!-- Bootstrap JS -->
	<!-- Bootstrap Bundle -->
	@yield('scripts')
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>

<!-- jQuery -->
{{-- <script src="{{ asset('assets/js/jquery.min.js') }}"></script> --}}

<!-- Owl Carousel -->
<script src="{{ asset('assets/plugins/OwlCarousel/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/plugins/OwlCarousel/js/owl.carousel2.thumbs.min.js') }}"></script>

<!-- Perfect Scrollbar -->
<script src="{{ asset('assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>

<!-- App JS -->
<script>
console.log('jQuery loaded:', typeof $ !== 'undefined');
console.log('jQuery version:', $ ? $.fn.jquery : 'Not loaded');
</script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script src="{{ asset('assets/js/index.js') }}"></script>

<!-- GoDaddy Monitoring Script (keep as is) -->
<script>
    'undefined' === typeof _trfq || (window._trfq = []);
    'undefined' === typeof _trfd && (window._trfd = []);
    _trfd.push(
        {'tccl.baseHost': 'secureserver.net'},
        {'ap': 'cpsh-oh'},
        {'server': 'p3plzcpnl509132'},
        {'dcenter': 'p3'},
        {'cp_id': '10399385'},
        {'cp_cl': '8'}
    );
</script>
<script src="https://img1.wsimg.com/signals/js/clients/scc-c2/scc-c2.min.js"></script>

<!-- Mirrored from codervent.com/shopingo/demo/shopingo_V2/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 22 Jul 2025 18:15:30 GMT -->
</html>