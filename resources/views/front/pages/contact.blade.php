@extends('front.layout.layout')


@section('content')
    <div class="page-content">
				<!--start breadcrumb-->
				<section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
					<div class="container">
						<div class="page-breadcrumb d-flex align-items-center">
							<h3 class="breadcrumb-title pe-3">Contact Us</h3>
							<div class="ms-auto">
								<nav aria-label="breadcrumb">
									<ol class="breadcrumb mb-0 p-0">
										<li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i> Home</a>
										</li>
										<li class="breadcrumb-item"><a href="javascript:;">Pages</a>
										</li>
										<li class="breadcrumb-item active" aria-current="page">Contact Us</li>
									</ol>
								</nav>
							</div>
						</div>
					</div>
				</section>
				<!--end breadcrumb-->
				<!--start page content-->
				<section class="py-4">
					<div class="container">
						<h3 class="d-none">Google Map</h3>
						<div class="contact-map p-3 bg-light rounded-0 shadow-none">
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d805184.6319269302!2d144.49269200596396!3d-37.971237009163936!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad646b5d2ba4df7%3A0x4045675218ccd90!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sin!4v1618835176130!5m2!1sen!2sin" class="w-100" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
						</div>
					</div>
				</section>
				<section class="py-4">
					<div class="container">
						<div class="row">
							<div class="col-lg-8">
								<div class="p-3 bg-light">
									<form>
										<div class="form-body">
											<h6 class="mb-0 text-uppercase">Drop us a line</h6>
											<div class="my-3 border-bottom"></div>
											<div class="mb-3">
												<label class="form-label">Enter Your Name</label>
												<input type="text" class="form-control" />
											</div>
											<div class="mb-3">
												<label class="form-label">Enter Email</label>
												<input type="text" class="form-control" />
											</div>
											<div class="mb-3">
												<label class="form-label">Phone Number</label>
												<input type="text" class="form-control" />
											</div>
											<div class="mb-3">
												<label class="form-label">Message</label>
												<textarea class="form-control" rows="4" cols="4"></textarea>
											</div>
											<div class="mb-3">
												<button class="btn btn-dark btn-ecomm">Send Message</button>
											</div>
										</div>
									</form>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="p-3 bg-light">
									<div class="address mb-3">
										<h6 class="mb-0 text-uppercase">Address</h6>
										<p class="mb-0 font-12">123 Street Name, City, Australia</p>
									</div>
									<div class="phone mb-3">
										<h6 class="mb-0 text-uppercase">Phone</h6>
										<p class="mb-0 font-13">Toll Free (123) 472-796</p>
										<p class="mb-0 font-13">Mobile : +91-9910XXXX</p>
									</div>
									<div class="email mb-3">
										<h6 class="mb-0 text-uppercase">Email</h6>
										<p class="mb-0 font-13">mail@example.com</p>
									</div>
									<div class="working-days mb-3">
										<h6 class="mb-0 text-uppercase">WORKING DAYS</h6>
										<p class="mb-0 font-13">Mon - FRI / 9:30 AM - 6:30 PM</p>
									</div>
								</div>
							</div>
						</div>
						<!--end row-->
					</div>
				</section>
				<!--end start page content-->
			</div>
@endsection