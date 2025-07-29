@extends('front.layout.layout')
@section('content')
    <div class="page-content">
        <section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">User Account</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i>Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="javascript:;">User Account</a>
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>

        <!-- Account Page -->
        <section class="py-5 bg-light">
            <div class="container">
                {{-- Displaying The Validation Errors --}}
                @if (Session::has('success_message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success:</strong> {{ Session::get('success_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if (Session::has('error_message'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> {{ Session::get('error_message') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error:</strong> @php echo implode('', $errors->all('<div>:message</div>')); @endphp
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <div class="row">
                    <!-- Login -->
                    <div class="col-lg-6">
                        <div class="login-wrapper">
                            <h2 class="account-h2 u-s-m-b-20">Login</h2>
                            <p>Welcome back! Sign in to your account.</p>
                            <form id="loginForm" action="javascript:;" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="astk">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Email">
                                    <p id="login-email"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="astk">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Password">
                                    <p id="login-password"></p>
                                </div>
                                <div class="mb-3 text-right">
                                    <a href="{{ url('user/forgot-password') }}">Lost your password?</a>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Register -->
                    <div class="col-lg-6">
                        <div class="reg-wrapper">
                            <h2 class="account-h2 u-s-m-b-20">Register</h2>
                            <p>Registering for this site allows you to access your order status and history.</p>
                            <form id="registerForm" action="javascript:;" method="post">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="astk">*</span></label>
                                    <input type="text" name="name" id="name" class="form-control" placeholder="Vendor Name">
                                    <p id="register-name"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="mobile" class="form-label">Mobile <span class="astk">*</span></label>
                                    <input type="text" name="mobile" id="mobile" class="form-control"
                                        placeholder="Vendor Mobile">
                                    <p id="register-mobile"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="astk">*</span></label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        placeholder="Vendor Email">
                                    <p id="register-email"></p>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="astk">*</span></label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Vendor Password">
                                    <p id="register-password"></p>
                                </div>
                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="accept" name="accept">
                                    <label class="form-check-label" for="accept">I've read and accept the
                                        <a href="terms-and-conditions.html" class="u-c-brand">terms & conditions</a>
                                    </label>
                                    <p id="register-accept"></p>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary w-100">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection