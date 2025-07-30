@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ empty($filter['id']) ? 'Add New Filter' : 'Edit Filter' }}</h1>
        <div class="page-actions">
            <a href="{{ url('admin/filters') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alerts -->
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
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Filter Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form 
                action="{{ empty($filter['id']) ? url('admin/add-edit-filter') : url('admin/add-edit-filter/' . $filter['id']) }}"
                method="POST"
                enctype="multipart/form-data"
                class="needs-validation"
                novalidate>

                @csrf

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="cat_ids">Select Categories</label>
                            <select name="cat_ids[]" 
                                    id="cat_ids" 
                                    class="form-control @error('cat_ids') is-invalid @enderror" 
                                    multiple 
                                    style="height: 200px"
                                    required>
                                <option value="" disabled>Select Categories</option>
                                @foreach ($categories as $section)
                                    <optgroup label="{{ $section['name'] }}">
                                        @foreach ($section['categories'] as $category)
                                            <option value="{{ $category['id'] }}" 
                                                {{ (!empty($filter['category_id']) && $filter['category_id'] == $category['id']) ? 'selected' : '' }}>
                                                {{ $category['category_name'] }}
                                            </option>
                                            @foreach ($category['sub_categories'] as $subcategory)
                                                <option value="{{ $subcategory['id'] }}" 
                                                    {{ (!empty($filter['category_id']) && $filter['category_id'] == $subcategory['id']) ? 'selected' : '' }}>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--&nbsp;{{ $subcategory['category_name'] }}
                                                </option>
                                            @endforeach
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            <div class="small text-muted">Hold Ctrl/Cmd to select multiple categories</div>
                            <div class="invalid-feedback">
                                Please select at least one category.
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="filter_name">Filter Name</label>
                            <input type="text"
                                   class="form-control @error('filter_name') is-invalid @enderror"
                                   id="filter_name"
                                   name="filter_name"
                                   placeholder="Enter Filter Name"
                                   value="{{ old('filter_name', $filter['filter_name'] ?? '') }}"
                                   required>
                            <div class="invalid-feedback">
                                Filter name is required.
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="filter_column">Filter Column</label>
                            <input type="text"
                                   class="form-control @error('filter_column') is-invalid @enderror"
                                   id="filter_column"
                                   name="filter_column"
                                   placeholder="e.g. brand, color, size"
                                   value="{{ old('filter_column', $filter['filter_column'] ?? '') }}"
                                   pattern="[a-z_]+"
                                   required>
                            <div class="small text-muted">Use lowercase letters and underscores only (no spaces)</div>
                            <div class="invalid-feedback">
                                Filter column is required and must contain only lowercase letters and underscores.
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex pt-3">
                    <button type="submit" class="btn btn-primary mr-3 px-4">
                        <i class="fas fa-save mr-1"></i> {{ empty($filter['id']) ? 'Create Filter' : 'Update Filter' }}
                    </button>
                    <a href="{{ url('admin/filters') }}" class="btn btn-light px-4">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Bootstrap 5 Validation -->
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