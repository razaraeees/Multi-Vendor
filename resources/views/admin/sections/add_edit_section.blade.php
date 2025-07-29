@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ empty($section['id']) ? 'Add New Section' : 'Edit Section' }}</h1>
        <div class="page-actions">
            <a href="{{ url('admin/sections') }}" class="btn btn-secondary">
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

    <!-- Section Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form 
                action="{{ empty($section['id']) ? url('admin/add-edit-section') : url('admin/add-edit-section/' . $section['id']) }}" 
                method="POST" 
                enctype="multipart/form-data"
                class="needs-validation" 
                novalidate>
                
                @csrf

                <div class="mb-4">
                    <label for="section_name" class="form-label">Section Name</label>
                    <input type="text"
                           class="form-control @error('section_name') is-invalid @enderror"
                           id="section_name"
                           name="section_name"
                           placeholder="Enter Section Name"
                           value="{{ !empty($section['name']) ? $section['name'] : old('section_name') }}"
                           required>
                    <div class="invalid-feedback">
                        Section name is required.
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> {{ empty($section['id']) ? 'Create Section' : 'Update Section' }}
                    </button>
                    <a href="{{ url('admin/sections') }}" class="btn btn-light px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection

<script>
// Bootstrap 5 Form Validation
(() => {
    'use strict'
    const forms = document.querySelectorAll('.needs-validation')
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>