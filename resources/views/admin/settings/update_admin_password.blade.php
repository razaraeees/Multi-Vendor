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

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
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

    <!-- Update Password Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4">Update Admin Password</h5>
            <form action="{{ url('admin/update-admin-password') }}" method="post">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Admin Email</label>
                    <input type="text" class="form-control" value="{{ $adminDetails['email'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Admin Type</label>
                    <input type="text" class="form-control" value="{{ $adminDetails['type'] }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" class="form-control" id="current_password" 
                           name="current_password" placeholder="Enter Current Password" required>
                    <span id="check_password" class="small text-muted"></span>
                </div>

                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="new_password" 
                           name="new_password" placeholder="Enter New Password" required>
                </div>

                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" 
                           name="confirm_password" placeholder="Confirm Password" required>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-lock"></i> Update Password
                    </button>
                    <button type="reset" class="btn btn-light">
                        <i class="fas fa-undo"></i> Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection
