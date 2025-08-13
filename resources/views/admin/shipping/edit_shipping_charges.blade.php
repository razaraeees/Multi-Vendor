@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Edit Shipping Charges</h1>
        <div class="page-actions">
            <a href="{{ url('admin/edit-shipping-charges') }}" class="btn btn-primary btn-sm">
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
            <form action="{{ $shippingDetails['id'] ? url('admin/edit-shipping-charges/' . $shippingDetails['id']) : url('admin/add-shipping-charges') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="row g-4">
                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="shipping_charge">Shipping Charge</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('shipping_charge') is-invalid @enderror"
                                id="shipping_charge"
                                name="shipping_charge"
                                placeholder="Enter shipping charge"
                                value="{{ old('shipping_charge', $shippingDetails['shipping_charge']) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                @error('shipping_charge') {{ $message }} @enderror
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="free_shipping_min_amount">Free Shipping Minimum Amount</label>
                            <input
                                type="number"
                                step="0.01"
                                class="form-control @error('free_shipping_min_amount') is-invalid @enderror"
                                id="free_shipping_min_amount"
                                name="free_shipping_min_amount"
                                placeholder="Enter minimum amount for free shipping"
                                value="{{ old('free_shipping_min_amount', $shippingDetails['free_shipping_min_amount']) }}"
                                required
                            >
                            <div class="invalid-feedback">
                                @error('free_shipping_min_amount') {{ $message }} @enderror
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