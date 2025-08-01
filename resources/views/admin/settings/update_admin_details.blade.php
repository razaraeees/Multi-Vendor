@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Admin Settings</h1>
    </div>

    <!-- Error Message -->
    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ Session::get('error_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Validation Errors -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Admin Settings Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4">Update Admin Details</h5>
            <form action="{{ url('admin/update-admin-details') }}" method="post" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->email }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Type</label>
                    <input type="text" class="form-control" value="{{ Auth::guard('admin')->user()->type }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="admin_name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="admin_name" name="admin_name"
                           placeholder="Enter Name"
                           value="{{ Auth::guard('admin')->user()->name }}">
                </div>

                <div class="mb-3">
                    <label for="admin_mobile" class="form-label">Mobile</label>
                    <input type="text" class="form-control" id="admin_mobile" name="admin_mobile"
                           placeholder="Enter 10 Digit Mobile Number"
                           value="{{ Auth::guard('admin')->user()->mobile }}"
                           maxlength="10" minlength="10">
                </div>

                <div class="mb-3">
                    <label for="admin_image" class="form-label">Admin Photo</label>
                    <input type="file" class="form-control" id="admin_image" name="admin_image">
                    @if (!empty(Auth::guard('admin')->user()->image))
                        <div class="mt-2">
                            <a target="_blank" 
                               href="{{ url('admin/images/photos/' . Auth::guard('admin')->user()->image) }}">
                                View Current Image
                            </a>
                            <input type="hidden" name="current_admin_image"
                                   value="{{ Auth::guard('admin')->user()->image }}">
                        </div>
                    @endif
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Submit
                    </button>
                    <button type="reset" class="btn btn-light">
                        <i class="fas fa-undo"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
