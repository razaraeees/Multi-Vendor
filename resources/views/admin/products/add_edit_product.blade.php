@extends('admin.layout.layout')

@push('name')
    <style>
        #attribute_value_id {
            background-color: #fff;
            font-size: 1rem;
            line-height: 1.5;
        }

        #attribute_value_id option {
            padding: 8px 10px;
            transition: background-color 0.2s;
        }

        #attribute_value_id option:hover {
            background-color: #f8f9fa;
        }

        .form-control:focus~.text-muted {
            opacity: 0.8;
        }

        /* ✅ Category and Brand Select Padding Fix */
        #category_id,
        #brand_id {
            padding: 12px 16px !important;
            border-radius: 6px;
            border: 2px solid #e9ecef;
            background-color: #fff;
            font-size: 14px;
            line-height: 1.5;
            transition: all 0.3s ease;
        }

        #category_id:focus,
        #brand_id:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        #category_id option,
        #brand_id option {
            padding: 10px 16px;
            font-size: 14px;
            line-height: 1.4;
        }

        /* Form Group Spacing Fix */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
            display: block;
        }

        /* Select2 Integration (if using Select2) */
        .select2-container--default .select2-selection--single {
            padding: 15px 20px !important;
            border: 2px solid #e9ecef !important;
            border-radius: 6px !important;
            height: auto !important;
            min-height: 46px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            padding-left: 4px !important;
            padding-right: 20px !important;
            line-height: 1.5 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            right: 8px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
        }

        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: 9999;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .loading-spinner {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .existing-images img,
        #imagePreviewContainer img {
            max-height: 120px;
            object-fit: cover;
            width: 100%;
        }

        .position-relative .btn {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .card {
            transition: box-shadow 0.15s ease-in-out;
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            display: block;
            color: #dc3545;
            font-size: 0.875em;
            margin-top: 0.25rem;
        }

        .alert-floating {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }

        /* Better Form Control Consistency */
        .form-control {
            padding: 12px 16px;
            border-radius: 6px;
            border: 2px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        /* Textarea Styling */
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }
    </style>
@endpush

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Products Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-product') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to listing
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

    <!-- Product Form -->
    <form id="productForm" class="forms-sample"
        @if (empty($product['id'])) action="{{ url('admin/add-edit-product') }}"
        @else
            action="{{ url('admin/add-edit-product/' . $product['id']) }}" @endif
        method="post" enctype="multipart/form-data">
        @csrf
        <!-- Loading Overlay -->
        <div id="loadingOverlay" class="loading-overlay" style="display: none;">
            <div class="loading-spinner">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Uploading... Please wait</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-box me-2"></i> Product Information</h5>
                    </div>
                    <div class="card-body">
                        <!-- Category & Brand -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="category_id" class="fw-bold">Select Category <span
                                            class="text-danger">*</span></label>
                                    <select name="category_id" id="category_id"
                                        class="form-control @error('category_id') is-invalid @enderror" required>
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category['id'] }}"
                                                {{ !empty($product['category_id']) && $product['category_id'] == $category['id'] ? 'selected' : '' }}>
                                                {{ $category['path'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback category-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="brand_id" class="fw-bold">Select Brand</label>
                                    <select name="brand_id" id="brand_id"
                                        class="form-control @error('brand_id') is-invalid @enderror">
                                        <option value="">Select Brand</option>
                                        @foreach ($brands as $brand)
                                            <option value="{{ $brand['id'] }}"
                                                {{ !empty($product['brand_id']) && $product['brand_id'] == $brand['id'] ? 'selected' : '' }}>
                                                {{ $brand['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback brand-error"></div>
                                </div>
                            </div>
                        </div>

                        @include('admin.filters.category_filters')

                        <!-- Product Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        value="{{ $product['product_name'] ?? old('product_name') }}" required>
                                    <div class="invalid-feedback product-name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_code">Product Code</label>
                                    <input type="text" class="form-control" id="product_code" name="product_code"
                                        value="{{ $product['product_code'] ?? old('product_code') }}" required>
                                    <div class="invalid-feedback product-code-error"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="stock">Product Stock</label>
                                    <input type="number" class="form-control" id="stock" name="stock"
                                        value="{{ $product['stock'] ?? old('stock') }}" required>
                                    <div class="invalid-feedback product-stock-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="stock_status" class="fw-bold">Stock Status</label>
                                    <select name="stock_status" id="stock_status"
                                        class="form-control @error('stock_status') is-invalid @enderror">
                                        <option value="">Select Brand</option>
                                        <option value="0">Stock Available</option>
                                            <option value="1">Out Of Stock</option>
                                    </select>
                                    <div class="invalid-feedback stock-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_color">Product Color</label>
                                    <input type="text" class="form-control" id="product_color" name="product_color"
                                        value="{{ $product['product_color'] ?? old('product_color') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_price">Product Price</label>
                                    <input type="number" class="form-control" id="product_price" name="product_price"
                                        value="{{ $product['product_price'] ?? old('product_price') }}" required>
                                    <div class="invalid-feedback product-price-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_discount">Product Discount (%)</label>
                                    <input type="number" class="form-control" id="product_discount"
                                        name="product_discount"
                                        value="{{ $product['product_discount'] ?? old('product_discount') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_weight">Product Weight (grams)</label>
                                    <input type="number" class="form-control" id="product_weight" name="product_weight"
                                        value="{{ $product['product_weight'] ?? old('product_weight') }}">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="group_code">Group Code</label>
                                    <input type="text" class="form-control" id="group_code" name="group_code"
                                        value="{{ $product['group_code'] ?? old('group_code') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Product Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ $product['description'] ?? old('description') }}</textarea>
                        </div>

                        <!-- SEO -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                        value="{{ $product['meta_title'] ?? old('meta_title') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description">{{ $product['meta_description'] ?? old('meta_description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                        value="{{ $product['meta_keywords'] ?? old('meta_keywords') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_featured"
                                            id="is_featured" value="Yes"
                                            {{ !empty($product['is_featured']) && $product['is_featured'] == 'Yes' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">Featured Item</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_bestseller"
                                            id="is_bestseller" value="Yes"
                                            {{ !empty($product['is_bestseller']) && $product['is_bestseller'] == 'Yes' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_bestseller">Best Seller Item</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary px-4" id="submitBtn">
                                <i class="fas fa-save"></i>
                                <span
                                    class="btn-text">{{ empty($product['id']) ? 'Create Product' : 'Update Product' }}</span>
                            </button>
                            <a href="{{ url('admin/products') }}" class="btn btn-light px-4">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Media Upload Section -->
            <div class="col-lg-4">
                <!-- Product Images -->
                <div class="card border mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-images me-2"></i> Product Images</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="product_images" class="form-label">Upload Images (Multiple)</label>
                            <input type="file" class="form-control" id="product_images" name="product_images[]"
                                multiple accept="image/*">
                            <small class="text-muted">Recommended Size: 1000x1000px. Max 5 images.</small>
                            <div class="invalid-feedback images-error"></div>
                        </div>

                        <div id="imagePreviewContainer" class="row g-2 mb-3"></div>

                        @if (!empty($product['product_images']))
                            <div class="existing-images">
                                <h6 class="text-muted mb-2">Current Images:</h6>
                                <div class="row g-2">
                                    @foreach ($product['product_images'] as $image)
                                        <div class="col-6" id="existing-image-{{ $image['id'] }}">
                                            <div class="position-relative">
                                                <img src="{{ asset('front/images/product_images/small/' . $image['image']) }}"
                                                    alt="Product Image" class="img-fluid rounded border">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1 delete-existing-image"
                                                    data-image-id="{{ $image['id'] }}"
                                                    style="width: 25px; height: 25px; font-size: 10px;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Product Video -->
                <div class="card border">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-video me-2"></i> Product Video</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label for="product_video" class="form-label">Upload Video</label>
                            <input type="file" class="form-control" id="product_video" name="product_video"
                                accept="video/*">
                            <small class="text-muted">Max Size: 20MB</small>
                            <div class="invalid-feedback video-error"></div>
                        </div>

                        <div id="videoPreviewContainer" class="mb-3" style="display: none;">
                            <video id="videoPreview" width="100%" height="200" controls
                                class="rounded border"></video>
                            <button type="button" class="btn btn-sm btn-danger mt-2 w-100" id="removeVideoPreview">
                                <i class="fas fa-times me-1"></i> Remove Video
                            </button>
                        </div>

                        @if (!empty($product['product_video']))
                            <div class="existing-video">
                                <h6 class="text-muted mb-2">Current Video:</h6>
                                <video width="100%" height="200" controls class="rounded border mb-2">
                                    <source src="{{ asset('front/videos/product_videos/' . $product['product_video']) }}"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <button type="button" class="btn btn-sm btn-danger w-100 confirmDelete"
                                    module="product-video" moduleid="{{ $product['id'] }}">
                                    <i class="fas fa-trash me-1"></i> Delete Video
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card border shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary">Attribute Selection</h6>
                    </div>
                    <div class="card-body">
                        <!-- Single Select: Attribute -->
                        <div class="form-group mb-3">
                            <label for="attribute_id" class="font-weight-bold">Select Attribute</label>
                            <select name="attribute_id" id="attribute_id" class="form-control">
                                <option value="" disabled selected>-- Choose Attribute --</option>
                                @foreach ($attributes as $attr)
                                    <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Multiple Select: Attribute Values -->
                        <div class="form-group mb-3">
                            <label for="attribute_value_id" class="font-weight-bold">Select Values (Multiple)</label>
                            <select name="attribute_value_id[]" id="attribute_value_id" class="form-control" multiple
                                style="min-height: 140px; border-radius: 6px;" disabled>
                                <option value="" disabled selected>-- Select Attribute First --</option>
                            </select>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle text-info mr-1"></i>
                                Hold <strong>Ctrl</strong> to select multiple values.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        console.log('Product form JavaScript initialized');

        // ============ LOAD EXISTING ATTRIBUTES ON PAGE LOAD (FOR EDIT) ============
        // Check if we're editing a product and have existing attributes
        const productId = "{{ isset($product) && !empty($product->id) ? $product->id : '' }}";
        
        if (productId) {
            console.log('Edit mode detected for product ID:', productId);
            loadExistingAttributes(productId);
        }
        
        function loadExistingAttributes(productId) {
            $.ajax({
                url: "{{ url('admin/get-product-attributes') }}/" + productId,
                type: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                success: function(response) {
                    console.log('Existing attributes response:', response);
                    
                    if (response.success && response.data && response.data.length > 0) {
                        const firstAttr = response.data[0];
                        const attributeId = firstAttr.attribute_id;
                        const valueIds = response.data.map(attr => attr.attribute_value_id.toString());
                        
                        console.log('Setting attribute ID:', attributeId);
                        console.log('Setting value IDs:', valueIds);
                        
                        // Set the attribute dropdown
                        $('#attribute_id').val(attributeId);
                        
                        // Load the values for this attribute with pre-selection
                        loadAttributeValues(attributeId, valueIds);
                    } else {
                        console.log('No existing attributes found');
                    }
                },
                error: function(xhr) {
                    console.log('Error loading existing attributes:', xhr.responseText);
                }
            });
        }

        // ============ ATTRIBUTE VALUES AJAX ============
        let fetchValuesUrl = "{{ url('admin/attribute-values') }}";
        
        $('#attribute_id').on('change', function() {
            let attributeId = $(this).val();
            console.log("Selected Attribute ID:", attributeId);
            loadAttributeValues(attributeId, []);
        });

        // Function to load attribute values (reusable for both new and edit)
        function loadAttributeValues(attributeId, selectedValues = []) {
            let url = fetchValuesUrl + '/' + attributeId;
            console.log("AJAX Request URL:", url);

            let $select = $('#attribute_value_id').empty().prop('disabled', true);

            if (!attributeId) {
                $select.append('<option value="" disabled selected>-- Select Attribute First --</option>');
                return;
            }

            $select.append('<option disabled selected>Loading values...</option>');

            $.ajax({
                url: url,
                type: 'GET',
                success: function(res) {
                    console.log("Attribute Values Response:", res);
                    $select.empty();
                    if (res.values && res.values.length > 0) {
                        res.values.forEach(v => {
                            const isSelected = selectedValues.includes(v.id.toString()) ? 'selected' : '';
                            $select.append(`<option value="${v.id}" ${isSelected}>${v.value}</option>`);
                        });
                        $select.prop('disabled', false);
                        
                        // Show success message if values were preselected
                        if (selectedValues.length > 0) {
                            console.log(`Pre-selected ${selectedValues.length} attribute values`);
                            showAlert('success', `Loaded existing attribute selections`);
                        }
                    } else {
                        $select.append('<option disabled>No values found</option>');
                    }
                },
                error: function(xhr) {
                    console.error("Attribute Values Error:", xhr.status, xhr.statusText);
                    console.error("Response Text:", xhr.responseText);
                    $select.empty().append('<option disabled>Error loading values</option>');
                }
            });
        }

        // ============ FORM SUBMISSION ============
        $('#productForm').on('submit', function(e) {
            e.preventDefault();
            console.log('=== FORM SUBMISSION STARTED ===');
            
            clearErrors();
            showLoadingState();

            const formData = new FormData(this);

            // ✅ Debug: Check if attributes are being sent
            console.log('Selected Attribute ID:', $('#attribute_id').val());
            console.log('Selected Values:', $('#attribute_value_id').val());
            
            // Log all form data
            console.log('Form data being sent:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ':', pair[1]);
            }

            // ✅ Validate attributes if selected
            const attributeId = $('#attribute_id').val();
            const selectedValues = $('#attribute_value_id').val();
            
            if (attributeId && (!selectedValues || selectedValues.length === 0)) {
                hideLoadingState();
                showAlert('warning', 'Please select at least one attribute value.');
                $('#attribute_value_id').focus();
                return;
            }

            // ✅ Additional validations
            if (!$('#category_id').val()) {
                hideLoadingState();
                showAlert('error', 'Please select a category.');
                $('#category_id').focus();
                return;
            }

            if (!$('#product_name').val().trim()) {
                hideLoadingState();
                showAlert('error', 'Please enter product name.');
                $('#product_name').focus();
                return;
            }

            if (!$('#product_price').val() || $('#product_price').val() <= 0) {
                hideLoadingState();
                showAlert('error', 'Please enter valid product price.');
                $('#product_price').focus();
                return;
            }

            if (!$('#stock').val() || $('#stock').val() < 0) {
                hideLoadingState();
                showAlert('error', 'Please enter valid stock quantity.');
                $('#stock').focus();
                return;
            }

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                timeout: 120000, // 2 minutes timeout
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                beforeSend: function(xhr) {
                    console.log('Sending AJAX request to:', $('#productForm').attr('action'));
                },
                success: function(response, textStatus, xhr) {
                    console.log('=== SUCCESS RESPONSE ===');
                    console.log('Response:', response);
                    hideLoadingState();

                    if (response.success) {
                        showAlert('success', response.message);
                        if (response.redirect) {
                            console.log('Redirecting to:', response.redirect);
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 2000);
                        }
                        resetForm();
                    } else {
                        showAlert('error', response.message || 'Something went wrong!');
                        if (response.errors) {
                            displayErrors(response.errors);
                        }
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error('=== AJAX ERROR ===');
                    console.error('Status:', xhr.status);
                    console.error('Status Text:', xhr.statusText);
                    console.error('Response Text:', xhr.responseText);
                    console.error('Text Status:', textStatus);
                    console.error('Error Thrown:', errorThrown);

                    hideLoadingState();

                    let errorMessage = 'An error occurred. Please try again.';
                    let errors = null;

                    try {
                        if (xhr.responseJSON) {
                            const response = xhr.responseJSON;
                            errorMessage = response.message || errorMessage;
                            errors = response.errors || null;

                            console.log('Parsed JSON Response:', response);

                            // Show debug info if available
                            if (response.error && (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1')) {
                                console.error('Debug Error:', response.error);
                            }
                        } else if (xhr.responseText) {
                            // Try to parse HTML error page
                            console.log('HTML Error Response detected');
                            if (xhr.responseText.includes('Fatal error') || xhr.responseText.includes('ParseError')) {
                                errorMessage = 'PHP syntax error in controller. Check server logs.';
                            } else if (xhr.responseText.includes('Class') && xhr.responseText.includes('not found')) {
                                errorMessage = 'Missing class or model. Check imports in controller.';
                            }
                        }
                    } catch (parseError) {
                        console.error('Error parsing response:', parseError);
                    }

                    // Handle specific error types
                    switch (xhr.status) {
                        case 422:
                            errorMessage = 'Please fix the validation errors below.';
                            if (errors) {
                                displayErrors(errors);
                            }
                            break;
                        case 500:
                            errorMessage = 'Server error occurred. Please check console and server logs.';
                            break;
                        case 404:
                            errorMessage = 'Route not found. Please check your controller route.';
                            break;
                        case 403:
                            errorMessage = 'Access denied. Check permissions.';
                            break;
                        case 419:
                            errorMessage = 'Session expired. Please refresh page and try again.';
                            break;
                        case 413:
                            errorMessage = 'Files too large. Please reduce file sizes.';
                            break;
                        case 0:
                            if (textStatus === 'timeout') {
                                errorMessage = 'Request timed out. Please try again.';
                            } else {
                                errorMessage = 'Network error. Check your connection.';
                            }
                            break;
                    }

                    showAlert('error', errorMessage);
                },
                complete: function(xhr, textStatus) {
                    console.log('=== REQUEST COMPLETED ===');
                    console.log('Final Status:', textStatus);
                }
            });
        });

        // ============ IMAGE UPLOAD HANDLING ============
        $('#product_images').on('change', function(e) {
            console.log('Image files selected');
            const files = e.target.files;
            const previewContainer = $('#imagePreviewContainer');
            previewContainer.empty();

            if (files.length > 5) {
                showAlert('warning', 'Maximum 5 images allowed.');
                this.value = '';
                return;
            }

            let validFiles = 0;
            Array.from(files).forEach((file, index) => {
                console.log(`Processing file ${index + 1}:`, file.name, file.type, file.size);

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showAlert('error', `File "${file.name}" is not a valid image.`);
                    return;
                }

                // Validate file size (5MB)
                if (file.size > 5 * 1024 * 1024) {
                    showAlert('error', `File "${file.name}" is too large. Maximum size is 5MB.`);
                    return;
                }

                validFiles++;
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = $(`
                        <div class="col-6">
                            <div class="position-relative">
                                <img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 120px; object-fit: cover; width: 100%;">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1 remove-preview" 
                                        data-index="${index}" style="width:25px;height:25px;font-size:10px;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <small class="d-block text-muted mt-1 text-truncate">${file.name}</small>
                            </div>
                        </div>
                    `);
                    previewContainer.append(img);
                };
                reader.readAsDataURL(file);
            });

            if (validFiles === 0) {
                this.value = '';
            } else {
                console.log(`${validFiles} valid images selected`);
            }
        });

        // ============ VIDEO UPLOAD HANDLING ============
        $('#product_video').on('change', function(e) {
            console.log('Video file selected');
            const file = e.target.files[0];
            const container = $('#videoPreviewContainer');
            const video = $('#videoPreview');

            if (file) {
                console.log('Video details:', file.name, file.type, file.size);

                // Validate file size (50MB)
                if (file.size > 50 * 1024 * 1024) {
                    showAlert('error', 'Video must be under 50MB.');
                    this.value = '';
                    return;
                }

                // Validate file type
                const validTypes = ['video/mp4', 'video/mov', 'video/avi', 'video/wmv', 'video/webm'];
                if (!validTypes.includes(file.type)) {
                    showAlert('error', 'Please upload a valid video file (MP4, MOV, AVI, WMV, WebM).');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    video.attr('src', e.target.result);
                    container.show();
                };
                reader.readAsDataURL(file);
            }
        });

        // ============ REMOVE HANDLERS ============
        // Remove video preview
        $('#removeVideoPreview').on('click', function() {
            console.log('Removing video preview');
            $('#product_video').val('');
            $('#videoPreviewContainer').hide();
            $('#videoPreview').attr('src', '');
        });

        // Remove image preview
        $(document).on('click', '.remove-preview', function() {
            console.log('Removing image preview');
            $(this).closest('.col-6').remove();
        });

        // ============ DELETE EXISTING MEDIA ============
        // Delete existing image
        $(document).on('click', '.delete-existing-image', function() {
            const imageId = $(this).data('image-id');
            const $imageContainer = $(`#existing-image-${imageId}`);

            console.log('Deleting existing image:', imageId);

            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: `${window.location.origin}/admin/delete-product-image/${imageId}`,
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function() {
                        $imageContainer.find('button').prop('disabled', true);
                        console.log('Delete image request sent');
                    },
                    success: function(response) {
                        console.log('Delete image response:', response);
                        if (response.success) {
                            $imageContainer.fadeOut(300, function() {
                                $(this).remove();
                            });
                            showAlert('success', 'Image deleted successfully!');
                        } else {
                            showAlert('error', response.message || 'Failed to delete image!');
                            $imageContainer.find('button').prop('disabled', false);
                        }
                    },
                    error: function(xhr) {
                        console.error('Delete image error:', xhr);
                        showAlert('error', 'Error deleting image!');
                        $imageContainer.find('button').prop('disabled', false);
                    }
                });
            }
        });

        // Delete video
        $(document).on('click', '.confirmDelete', function() {
            const module = $(this).attr('module');
            const moduleId = $(this).attr('moduleid');
            console.log('Deleting:', module, moduleId);
            
            if (confirm(`Are you sure you want to delete this ${module.replace('-', ' ')}?`)) {
                window.location.href = `${window.location.origin}/admin/delete-product-image-video/${module}/${moduleId}`;
            }
        });

        // ============ UTILITY FUNCTIONS ============
        function showLoadingState() {
            console.log('Showing loading state');
            $('#loadingOverlay').show();
            $('#submitBtn').prop('disabled', true).find('.btn-text').text('Uploading...');
        }

        function hideLoadingState() {
            console.log('Hiding loading state');
            $('#loadingOverlay').hide();
            const isEdit = $('#productForm').find('input[name="_method"]').length > 0 || 
                          window.location.href.includes('/admin/add-edit-product/');
            $('#submitBtn').prop('disabled', false).find('.btn-text').text(isEdit ? 'Update Product' : 'Create Product');
        }

        function resetForm() {
            console.log('Resetting form');
            $('#product_images, #product_video').val('');
            $('#imagePreviewContainer').empty();
            $('#videoPreviewContainer').hide();
            $('#attribute_id').val('').trigger('change');
            $('#attribute_value_id').empty().prop('disabled', true).append('<option value="" disabled selected>-- Select Attribute First --</option>');
        }

        function clearErrors() {
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').text('');
        }

        function displayErrors(errors) {
            console.log('Displaying errors:', errors);
            $.each(errors, function(field, messages) {
                const fieldName = field.replace(/\./g, '_').replace(/\[/g, '_').replace(/\]/g, '');
                const input = $(`[name="${field}"], [name="${field}[]"], #${field}`);
                const errorContainer = $(`.${fieldName}-error`);

                input.addClass('is-invalid');
                if (errorContainer.length) {
                    errorContainer.text(messages[0]);
                } else {
                    // Create error message if container doesn't exist
                    input.after(`<div class="invalid-feedback">${messages[0]}</div>`);
                }
            });

            // Scroll to first error
            const firstError = $('.is-invalid').first();
            if (firstError.length) {
                $('html, body').animate({
                    scrollTop: firstError.offset().top - 100
                }, 500);
            }
        }

        function showAlert(type, message) {
            console.log(`Showing ${type} alert:`, message);
            
            // Remove existing alerts
            $('.alert-floating').remove();

            const alertClass = {
                success: 'alert-success',
                error: 'alert-danger',
                warning: 'alert-warning',
                info: 'alert-info'
            }[type] || 'alert-info';

            const alert = $(`
                <div class="alert ${alertClass} alert-dismissible fade show alert-floating" role="alert">
                    <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);

            $('body').append(alert);

            // Auto remove after 10 seconds
            setTimeout(() => {
                alert.fadeOut(300, () => alert.remove());
            }, 10000);
        }

        // ============ STOCK STATUS CHANGE ============
        $('#stock_status').on('change', function() {
            const status = $(this).val();
            const stockField = $('#stock');
            
            if (status === '1') { // Out of stock
                stockField.val('0').prop('readonly', true);
                console.log('Stock status set to Out of Stock, stock set to 0');
            } else { // In stock
                stockField.prop('readonly', false);
                console.log('Stock status set to Available');
            }
        });

        // ============ PRICE CALCULATION ============
        $('#product_price, #product_discount').on('input', function() {
            const price = parseFloat($('#product_price').val()) || 0;
            const discount = parseFloat($('#product_discount').val()) || 0;
            
            if (discount > 0 && discount <= 100) {
                const discountedPrice = price - (price * discount / 100);
                console.log(`Price: ${price}, Discount: ${discount}%, Final: ${discountedPrice.toFixed(2)}`);
            }
        });

        console.log('=== Product Form JavaScript Loaded Successfully ===');
    });
</script>

<style>
    .existing-images img,
    #imagePreviewContainer img {
        max-height: 120px;
        object-fit: cover;
        transition: transform 0.2s;
    }

    .existing-images img:hover,
    #imagePreviewContainer img:hover {
        transform: scale(1.05);
    }

    .position-relative .btn {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: all 0.2s;
    }

    .position-relative .btn:hover {
        transform: scale(1.1);
    }

    .card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
    }

    .loading-overlay {
        backdrop-filter: blur(5px);
    }

    .alert-floating {
        animation: slideInRight 0.3s ease-out;
    }

    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
</style>
@endpush