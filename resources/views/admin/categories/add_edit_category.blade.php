@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">{{ empty($category['id']) ? 'Add New Category' : 'Edit Category' }}</h1>
        <div class="page-actions">
            <a href="{{ url('admin/categories') }}" class="btn btn-primary btn-sm">
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

    <!-- Category Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form
                action="{{ empty($category['id']) ? url('admin/add-edit-category') : url('admin/add-edit-category/' . $category['id']) }}"
                method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

                @csrf

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Parent Category Selection -->
                        <!-- Category Level Selection -->
                        <div class="form-group mb-4">
                            <label for="level">Category Level <span class="text-danger">*</span></label>
                            <select name="level" id="level" class="form-control @error('level') is-invalid @enderror"
                                required onchange="updateParentOptions()">
                                <option value="" disabled selected>Select Category Level</option>
                                <option value="1" {{ old('level', $category['level'] ?? '') == 1 ? 'selected' : '' }}>
                                    Level 1 - Main Category
                                </option>
                                <option value="2" {{ old('level', $category['level'] ?? '') == 2 ? 'selected' : '' }}>
                                    Level 2 - Sub Category
                                </option>
                                <option value="3" {{ old('level', $category['level'] ?? '') == 3 ? 'selected' : '' }}>
                                    Level 3 - Sub-Sub Category
                                </option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a category level.
                            </div>
                            {{-- <div class="form-text">
                                <small>
                                    <i class="fas fa-info-circle text-primary"></i>
                                    <strong>Level 1:</strong> Main categories (e.g., Electronics, Clothing)<br>
                                    <strong>Level 2:</strong> Sub categories (e.g., Mobile Phones, Laptops)<br>
                                    <strong>Level 3:</strong> Sub-sub categories (e.g., Android, iPhone)
                                </small>
                            </div> --}}
                        </div>
                        <div class="form-group mb-4" id="parentCategorySection" style="display: none;">
                            <label for="parent_id">Parent Category <span class="text-danger">*</span></label>
                            <select name="parent_id" id="parent_id"
                                class="form-control @error('parent_id') is-invalid @enderror">
                                <option value="" disabled selected>Select Parent Category</option>
                                @if (!empty($getParentCategories))
                                    @foreach ($getParentCategories as $parentCat)
                                        <option value="{{ $parentCat['id'] }}"
                                            {{ old('parent_id', $category['parent_id'] ?? '') == $parentCat['id'] ? 'selected' : '' }}>
                                            {{ $parentCat['category_name'] }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            {{-- <div class="invalid-feedback">
                                Please select a parent category.
                            </div> --}}
                        </div>

                        <div class="form-group mb-4">
                            <label for="category_name">Category Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('category_name') is-invalid @enderror"
                                id="category_name" name="category_name" placeholder="Enter Category Name"
                                value="{{ old('category_name', $category['category_name'] ?? '') }}" required>
                            <div class="invalid-feedback">
                                Category name is required.
                            </div>
                        </div>


                        <div class="form-group mb-4">
                            <label for="category_discount">Discount (%)</label>
                            <input type="number" step="0.01" min="0" max="100"
                                class="form-control @error('category_discount') is-invalid @enderror" id="category_discount"
                                name="category_discount" placeholder="e.g. 10"
                                value="{{ old('category_discount', $category['category_discount'] ?? '') }}">
                            {{-- <div class="invalid-feedback">
                                Enter a valid discount value between 0 and 100.
                            </div> --}}
                        </div>

                        <div class="form-group mb-4">
                            <label for="url">SEO URL <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('url') is-invalid @enderror" id="url"
                                name="url" placeholder="e.g. electronics-mobiles"
                                value="{{ old('url', $category['url'] ?? '') }}" required>
                            {{-- <div class="invalid-feedback">
                                URL is required and must be unique.
                            </div> --}}
                            {{-- <div class="form-text">
                                <small><i class="fas fa-lightbulb text-warning"></i> URL will be auto-generated from
                                    category name if left empty</small>
                            </div> --}}
                        </div>

                        <!-- Status Field -->
                        <div class="form-group mb-4">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="1"
                                    {{ old('status', $category['status'] ?? 1) == 1 ? 'selected' : '' }}>
                                    <i class="fas fa-check-circle text-success"></i> Active
                                </option>
                                <option value="0"
                                    {{ old('status', $category['status'] ?? 1) == 0 ? 'selected' : '' }}>
                                    <i class="fas fa-times-circle text-danger"></i> Inactive
                                </option>
                            </select>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                id="meta_title" name="meta_title" placeholder="SEO Meta Title"
                                value="{{ old('meta_title', $category['meta_title'] ?? '') }}">
                            {{-- <div class="form-text">
                                <small><i class="fas fa-search text-info"></i> Recommended length: 50-60 characters</small>
                            </div> --}}
                        </div>

                        <div class="form-group mb-4">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                id="meta_keywords" name="meta_keywords" placeholder="e.g. mobile, phone, android"
                                value="{{ old('meta_keywords', $category['meta_keywords'] ?? '') }}">
                            {{-- <div class="form-text">
                                <small><i class="fas fa-tags text-secondary"></i> Separate keywords with commas</small>
                            </div> --}}
                        </div>

                        <!-- Category Image -->
                        <div class="form-group mb-4">
                            <label for="category_image">Category Image</label>
                            <input type="file" class="form-control @error('category_image') is-invalid @enderror"
                                id="category_image" name="category_image" accept="image/*"
                                onchange="previewImage(event)">
                            {{-- <div class="form-text">Max size: 2MB, formats: jpg, png, webp</div> --}}

                            @if (!empty($category['category_image']))
                                <div class="mb-4">
                                    <label class="form-label">Current Image:</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="{{ asset('front/images/category_images/' . $category['category_image']) }}"
                                            alt="Current" class="img-thumbnail border"
                                            style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                        <div>
                                            <a href="javascript:void(0)"
                                                class="btn btn-outline-danger btn-sm confirmDelete"
                                                module="category-image" moduleid="{{ $category['id'] }}">
                                                <i class="fas fa-trash"></i> Delete Image
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="mt-3" id="imagePreviewContainer" style="display: none;">
                                <label class="form-label">New Image Preview:</label>
                                <div>
                                    <img id="imagePreview" src="" alt="Preview" class="img-thumbnail border"
                                        style="max-width: 120px; max-height: 120px; object-fit: cover;">
                                </div>
                            </div>

                            <input type="hidden" name="current_category_image"
                                value="{{ $category['category_image'] ?? '' }}">
                            <div class="invalid-feedback">
                                Please upload a valid image.
                            </div>
                        </div>
                        <div class="form-group mb-4">
                            <label for="meta_description">Meta Description</label>
                            <textarea name="meta_description" id="meta_description"
                                class="form-control @error('meta_description') is-invalid @enderror" rows="3"
                                placeholder="SEO Meta Description">{{ old('meta_description', $category['meta_description'] ?? '') }}</textarea>
                            {{-- <div class="form-text">
                                <small><i class="fas fa-search text-info"></i> Recommended length: 150-160 characters</small>
                            </div> --}}
                        </div>

                    </div>
                </div>

                <!-- Full Width Fields -->
                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                rows="5" placeholder="Enter detailed category description">{{ old('description', $category['description'] ?? '') }}</textarea>
                            <div class="form-text">
                                <small><i class="fas fa-align-left text-secondary"></i> Provide detailed information about
                                    this category</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-3 pt-4 border-top">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i>
                        {{ empty($category['id']) ? 'Create Category' : 'Update Category' }}
                    </button>
                    <a href="{{ url('admin/categories') }}" class="btn btn-light px-4">
                        <i class="fas fa-times me-1"></i> Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    <style>
        .form-label {
            font-weight: 600;
            color: #333;
        }

        .form-text small {
            color: #6c757d;
        }

        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }

        #parentCategorySection {
            transition: all 0.3s ease;
        }
    </style>

    <!-- JavaScript -->
    <script>
        // Image Preview Function
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            const container = document.getElementById('imagePreviewContainer');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    container.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                container.style.display = 'none';
            }
        }

        // Update Parent Options based on Level
        function updateParentOptions() {
            const level = document.getElementById('level').value;
            const parentSection = document.getElementById('parentCategorySection');
            const parentSelect = document.getElementById('parent_id');

            if (level === '1') {
                parentSection.style.display = 'none';
                parentSelect.innerHTML = '<option value="" disabled selected>Select Parent Category</option>';
                parentSelect.removeAttribute('required');
            } else {
                parentSection.style.display = 'block';
                parentSelect.setAttribute('required', 'required');

                // AJAX call to get parent categories based on level
                fetchParentCategories(level);
            }
        }

        // Fetch Parent Categories via AJAX
        function fetchParentCategories(level) {
            const parentSelect = document.getElementById('parent_id');
            const currentParentId = '{{ $category['parent_id'] ?? '' }}';

            // Show loading
            parentSelect.innerHTML = '<option value="" disabled selected>Loading...</option>';

            // AJAX call
            fetch(`{{ url('admin/get-parent-categories') }}?level=${level}`)
                .then(response => response.json())
                .then(data => {
                    let options = '<option value="" disabled selected>Select Parent Category</option>';

                    data.forEach(category => {
                        const selected = (currentParentId == category.id) ? 'selected' : '';
                        options +=
                            `<option value="${category.id}" ${selected}>${category.category_name}</option>`;
                    });

                    parentSelect.innerHTML = options;
                })
                .catch(error => {
                    console.error('Error fetching parent categories:', error);
                    parentSelect.innerHTML = '<option value="" disabled selected>Error loading categories</option>';
                });
        }

        // Auto-generate URL from category name
        document.getElementById('category_name').addEventListener('keyup', function() {
            const categoryName = this.value;
            const urlField = document.getElementById('url');

            if (categoryName) {
                const slug = categoryName.toLowerCase()
                    .replace(/[^a-z0-9 -]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-');
                urlField.value = slug;
            }
        });


        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateParentOptions();
        });

        // Bootstrap 5 Validation
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', function(event) {
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
