@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Edit Shipping Charges</h1>
        <div class="page-actions">
            <a href="{{ url('admin/shipping-charges') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Shipping Charges Form -->
    <div class="card shadow-sm border">
        <div class="card-body p-4">
            <form action="{{ url('admin/edit-shipping-charges/' . $shippingDetails['id']) }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="country">Country</label>
                            <input
                                type="text"
                                class="form-control"
                                value="{{ $shippingDetails['country'] }}"
                                readonly
                            >
                        </div>

                        <div class="form-group mb-4">
                            <label for="0_500g">Rate (0g - 500g)</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('0_500g') is-invalid @enderror"
                                id="0_500g"
                                name="0_500g"
                                placeholder="Enter rate for 0-500g"
                                value="{{ old('0_500g', $shippingDetails['0_500g']) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                @error('0_500g') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="501g_1000g">Rate (501g - 1000g)</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('501g_1000g') is-invalid @enderror"
                                id="501g_1000g"
                                name="501g_1000g"
                                placeholder="Enter rate for 501-1000g"
                                value="{{ old('501g_1000g', $shippingDetails['501g_1000g']) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                @error('501g_1000g') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="1001_2000g">Rate (1001g - 2000g)</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('1001_2000g') is-invalid @enderror"
                                id="1001_2000g"
                                name="1001_2000g"
                                placeholder="Enter rate for 1001-2000g"
                                value="{{ old('1001_2000g', $shippingDetails['1001_2000g']) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                @error('1001_2000g') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="2001g_5000g">Rate (2001g - 5000g)</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('2001g_5000g') is-invalid @enderror"
                                id="2001g_5000g"
                                name="2001g_5000g"
                                placeholder="Enter rate for 2001-5000g"
                                value="{{ old('2001g_5000g', $shippingDetails['2001g_5000g']) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                @error('2001g_5000g') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="above_5000g">Rate (Above 5000g)</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('above_5000g') is-invalid @enderror"
                                id="above_5000g"
                                name="above_5000g"
                                placeholder="Enter rate for above 5000g"
                                value="{{ old('above_5000g', $shippingDetails['above_5000g']) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                @error('above_5000g') {{ $message }} @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-3 pt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Update Charges
                    </button>
                    <a href="{{ url('admin/shipping-charges') }}" class="btn btn-light px-4">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection

<!-- Bootstrap 5 Form Validation Script -->
@section('scripts')
<script>
    (() => {
        'use strict';
        const forms = document.querySelectorAll('.needs-validation');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>
@endsection