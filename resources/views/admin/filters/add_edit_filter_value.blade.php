@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ empty($filter['id']) ? 'Add New Filter Value' : 'Edit Filter Value' }}</h1>
        <div class="page-actions">
            <a href="{{ url('admin/filter-values') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Success Message -->
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

    <!-- Filter Value Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form 
                action="{{ empty($filter['id']) ? url('admin/add-edit-filter-value') : url('admin/add-edit-filter-value/' . $filter['id']) }}"
                method="POST"
                enctype="multipart/form-data"
                class="needs-validation"
                novalidate>

                @csrf

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="filter_id">Select Filter</label>
                            <select name="filter_id" 
                                    id="filter_id" 
                                    class="form-control @error('filter_id') is-invalid @enderror"
                                    required>
                                <option value="" disabled selected>Select Filter</option>
                                @foreach ($filters as $filterOption)
                                    <option value="{{ $filterOption['id'] }}" 
                                        {{ (!empty($filter['filter_id']) && $filter['filter_id'] == $filterOption['id']) ? 'selected' : '' }}>
                                        {{ $filterOption['filter_name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a filter.
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="filter_value">Filter Value</label>
                            <input type="text"
                                   class="form-control @error('filter_value') is-invalid @enderror"
                                   id="filter_value"
                                   name="filter_value"
                                   placeholder="Enter Filter Value (e.g. Red, Large, Samsung)"
                                   value="{{ old('filter_value', $filter['filter_value'] ?? '') }}"
                                   required>
                            <div class="small text-muted">Enter a specific value for the selected filter</div>
                            <div class="invalid-feedback">
                                Filter value is required.
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex pt-3">
                    <button type="submit" class="btn btn-primary mr-3 px-4">
                        <i class="fas fa-save mr-1"></i> {{ empty($filter['id']) ? 'Create Filter Value' : 'Update Filter Value' }}
                    </button>
                    <a href="{{ url('admin/filter-values') }}" class="btn btn-light px-4">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Bootstrap 4 Validation -->
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