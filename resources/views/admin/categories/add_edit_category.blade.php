@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        {{-- <h1 class="page-title">{{ empty($category['id']) ? 'Add New Category' : 'Edit Category' }}</h1> --}}
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
                method="POST"
                enctype="multipart/form-data"
                class="needs-validation"
                novalidate>

                @csrf

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="category_name">Category Name</label>
                            <input type="text"
                                   class="form-control @error('category_name') is-invalid @enderror"
                                   id="category_name"
                                   name="category_name"
                                   placeholder="Enter Category Name"
                                   value="{{ old('category_name', $category['category_name'] ?? '') }}"
                                   required>
                            <div class="invalid-feedback">
                                Category name is required.
                            </div>
                        </div>

                        <div class="form-group mb-4" id="appendCategoriesLevel">
                            @include('admin.categories.append_categories_level')
                        </div>

                        <div class="form-group mb-4">
                            <label for="category_discount">Discount (%)</label>
                            <input type="number" step="0.01"
                                   class="form-control @error('category_discount') is-invalid @enderror"
                                   id="category_discount"
                                   name="category_discount"
                                   placeholder="e.g. 10"
                                   value="{{ old('category_discount', $category['category_discount'] ?? '') }}">
                            <div class="invalid-feedback">
                                Enter a valid discount value.
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="url">URL</label>
                            <input type="text"
                                   class="form-control @error('url') is-invalid @enderror"
                                   id="url"
                                   name="url"
                                   placeholder="e.g. electronics-mobiles"
                                   value="{{ old('url', $category['url'] ?? '') }}"
                                   required>
                            <div class="invalid-feedback">
                                URL is required and must be unique.
                            </div>
                        </div>

                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">


                        <div class="form-group mb-4">
                            <label for="section_id">Select Section</label>
                            <select name="section_id" id="section_id" 
                                    class="form-control @error('section_id') is-invalid @enderror" 
                                    required>
                                <option value="" disabled selected>Select Section</option>
                                @foreach ($getSections as $section)
                                    <option value="{{ $section['id'] }}"
                                        {{ (!empty($category['section_id']) && $category['section_id'] == $section['id']) ? 'selected' : '' }}>
                                        {{ $section['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback">
                                Please select a section.
                            </div>
                        </div>
                        

                        <div class="form-group mb-4">
                            <label for="meta_title">Meta Title</label>
                            <input type="text"
                                   class="form-control @error('meta_title') is-invalid @enderror"
                                   id="meta_title"
                                   name="meta_title"
                                   placeholder="SEO Meta Title"
                                   value="{{ old('meta_title', $category['meta_title'] ?? '') }}">
                        </div>

                        <div class="form-group mb-4">
                            <label for="meta_description">Meta Description</label>
                            <input type="text"
                                   class="form-control @error('meta_description') is-invalid @enderror"
                                   id="meta_description"
                                   name="meta_description"
                                   placeholder="SEO Meta Description"
                                   value="{{ old('meta_description', $category['meta_description'] ?? '') }}">
                        </div>

                        <div class="form-group mb-4">
                            <label for="meta_keywords">Meta Keywords</label>
                            <input type="text"
                                   class="form-control @error('meta_keywords') is-invalid @enderror"
                                   id="meta_keywords"
                                   name="meta_keywords"
                                   placeholder="e.g. mobile, phone, android"
                                   value="{{ old('meta_keywords', $category['meta_keywords'] ?? '') }}">
                        </div>

                    </div>
                </div>

                <!-- Full Width Fields -->
                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label for="category_image">Category Image</label>
                            <input type="file"
                                   class="form-control @error('category_image') is-invalid @enderror"
                                   id="category_image"
                                   name="category_image"
                                   onchange="previewImage(event)">
                            <div class="form-text">Max size: 2MB, formats: jpg, png, webp</div>

                            @if (!empty($category['category_image']))
                                <div class="mt-2">
                                    <img src="{{ asset('front/images/category_images/' . $category['category_image']) }}"
                                         alt="Current"
                                         class="img-thumbnail"
                                         style="max-width: 120px;">
                                    &nbsp;|&nbsp;
                                    <a href="javascript:void(0)" class="text-danger confirmDelete" module="category-image" moduleid="{{ $category['id'] }}">Delete Image</a>
                                </div>
                            @endif

                            <div class="mt-2">
                                <img id="imagePreview"
                                     src=""
                                     alt="Preview"
                                     style="max-width: 120px; display: none;">
                            </div>

                            <input type="hidden" name="current_category_image" value="{{ $category['category_image'] ?? '' }}">
                            <div class="invalid-feedback">
                                Please upload a valid image.
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group mb-4">
                            <label for="description">Description</label>
                            <textarea name="description" id="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      rows="4"
                                      placeholder="Enter detailed category description">{{ old('description', $category['description'] ?? '') }}</textarea>
                            <div class="invalid-feedback">
                                Description is required.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-3 pt-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> {{ empty($category['id']) ? 'Create Category' : 'Update Category' }}
                    </button>
                    <a href="{{ url('admin/categories') }}" class="btn btn-light px-4">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    <!-- Image Preview Script -->
    <script>
        function previewImage(event) {
            const input = event.target;
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

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
