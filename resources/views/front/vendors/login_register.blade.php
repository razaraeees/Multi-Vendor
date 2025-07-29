{{-- This page is accessed from Vendor Login tab in the drop-down menu in the header (in front/layout/header.blade.php) --}}
@extends('front.layout.layout')


@section('content')
    <!-- Page Introduction Wrapper -->

    <div class="page-content">

        <section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">Vendor Account</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i>Home</a>
                                </li>`
                                <li class="breadcrumb-item"><a href="javascript:;">Vendor Account</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!-- Page Introduction Wrapper /- -->
        <!-- Account-Page -->
        <section class="py-5 bg-light">
            <div class="container">

                {{-- Success Message --}}
                @if (Session::has('success_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success:</strong> {{ Session::get('success_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Error Message --}}
                @if (Session::has('error_message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ Session::get('error_message') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong>
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="row g-4">
                    <!-- Login -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <h2 class="h4 mb-3 text-center">Login</h2>
                                <p class="text-muted mb-4 text-center">Welcome back! Sign in to your account.</p>

                                <form action="{{ url('admin/login') }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="vendor-email" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" name="email" id="vendor-email" class="form-control"
                                            placeholder="Email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="vendor-password" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" name="password" id="vendor-password" class="form-control"
                                            placeholder="Password">
                                    </div>
                                    <button class="btn btn-outline-primary w-100">Login</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Login /- -->

                    <!-- Register -->
                    <div class="col-lg-6">
                        <div class="card shadow-sm border-0 h-100">
                            <div class="card-body p-4">
                                <h2 class="h4 mb-3 text-center">Register</h2>
                                <p class="text-muted mb-4 text-center">Registering for this site allows you to access your
                                    order status and history.</p>

                                <form id="vendorForm" action="{{ url('/vendor/register') }}" method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="vendorname" class="form-label">Name <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="vendorname" class="form-control" placeholder="Vendor Name"
                                            name="name">
                                    </div>
                                    <div class="mb-3">
                                        <label for="vendormobile" class="form-label">Mobile <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="vendormobile" class="form-control"
                                            placeholder="Vendor Mobile" name="mobile">
                                    </div>
                                    <div class="mb-3">
                                        <label for="vendoremail" class="form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <input type="email" id="vendoremail" class="form-control"
                                            placeholder="Vendor Email" name="email">
                                    </div>
                                    <div class="mb-3">
                                        <label for="vendorpassword" class="form-label">Password <span
                                                class="text-danger">*</span></label>
                                        <input type="password" id="vendorpassword" class="form-control"
                                            placeholder="Vendor Password" name="password">
                                    </div>
                                    <div class="form-check mb-3">
                                        <input type="checkbox" class="form-check-input" id="accept" name="accept">
                                        <label class="form-check-label" for="accept">
                                            I’ve read and accept the <a href="terms-and-conditions.html"
                                                class="text-primary">terms & conditions</a>
                                        </label>
                                    </div>
                                    <button class="btn btn-primary w-100">Register</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- Register /- -->
                </div>
            </div>
        </section>


    </div>
    <!-- Account-Page /- -->
@endsection
