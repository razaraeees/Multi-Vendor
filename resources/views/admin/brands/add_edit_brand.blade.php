@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ empty($brand['id']) ? 'Add New Brand' : 'Edit Brand' }}</h1>
        <div class="page-actions">
            <a href="{{ url('admin/brands') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ Session::get('error_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

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

    <!-- Brand Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form 
                action="{{ empty($brand['id']) ? url('admin/add-edit-brand') : url('admin/add-edit-brand/' . $brand['id']) }}"
                method="POST"
                enctype="multipart/form-data"
                class="needs-validation"
                novalidate>

                @csrf

                <div class="mb-4">
                    <label for="brand_name" class="form-label">Brand Name</label>
                    <input type="text"
                           class="form-control @error('brand_name') is-invalid @enderror"
                           id="brand_name"
                           name="brand_name"
                           placeholder="Enter Brand Name"
                           value="{{ old('brand_name', $brand['name'] ?? '') }}"
                           required>
                    <div class="invalid-feedback">
                        Brand name is required.
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> {{ empty($brand['id']) ? 'Create Brand' : 'Update Brand' }}
                    </button>
                    <a href="{{ url('admin/brands') }}" class="btn btn-light px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 Form Validation -->
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
    <script>
    $(document).ready(function() {
        $('#category_id').select2({
            placeholder: "Select Category",
            allowClear: true
        });
        $('#brand_id').select2({
            placeholder: "Select Brand",
            allowClear: true
        });
    });
</script>
@endsection