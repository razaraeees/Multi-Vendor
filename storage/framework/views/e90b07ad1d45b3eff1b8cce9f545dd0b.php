

<?php $__env->startPush('name'); ?>
    <style>
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
    </style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Products Management</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/add-edit-product')); ?>" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Back to listing
            </a>
        </div>
    </div>

    <!-- Alerts -->
    <?php if(Session::has('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if(Session::has('error_message')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> <?php echo e(Session::get('error_message')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Product Form -->
    <form id="productForm" class="forms-sample"
        <?php if(empty($product['id'])): ?> action="<?php echo e(url('admin/add-edit-product')); ?>"
        <?php else: ?>
            action="<?php echo e(url('admin/add-edit-product/' . $product['id'])); ?>" <?php endif; ?>
        method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
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
                                    <label for="category_id" class="fw-bold">Select Category</label>
                                    <select name="category_id" id="category_id"
                                        class="form-control shadow-sm border-primary <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        required>
                                        <option value="">Select Category</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category['id']); ?>"
                                                <?php echo e(!empty($product['category_id']) && $product['category_id'] == $category['id'] ? 'selected' : ''); ?>>
                                                <?php echo e($category['path']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>     
                                    </select>
                                    <div class="invalid-feedback category-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-4">
                                    <label for="brand_id">Select Brand</label>
                                    <select name="brand_id" id="brand_id"
                                        class="form-control <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <option value="">Select Brand</option>
                                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($brand['id']); ?>"
                                                <?php echo e(!empty($product['brand_id']) && $product['brand_id'] == $brand['id'] ? 'selected' : ''); ?>>
                                                <?php echo e($brand['name']); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <div class="invalid-feedback brand-error"></div>
                                </div>
                            </div>
                        </div>

                        <?php echo $__env->make('admin.filters.category_filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        <!-- Product Details -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        value="<?php echo e($product['product_name'] ?? old('product_name')); ?>" required>
                                    <div class="invalid-feedback product-name-error"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_code">Product Code</label>
                                    <input type="text" class="form-control" id="product_code" name="product_code"
                                        value="<?php echo e($product['product_code'] ?? old('product_code')); ?>" required>
                                    <div class="invalid-feedback product-code-error"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_color">Product Color</label>
                                    <input type="text" class="form-control" id="product_color" name="product_color"
                                        value="<?php echo e($product['product_color'] ?? old('product_color')); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_price">Product Price</label>
                                    <input type="number" class="form-control" id="product_price" name="product_price"
                                        value="<?php echo e($product['product_price'] ?? old('product_price')); ?>" required>
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
                                        value="<?php echo e($product['product_discount'] ?? old('product_discount')); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_weight">Product Weight (grams)</label>
                                    <input type="number" class="form-control" id="product_weight" name="product_weight"
                                        value="<?php echo e($product['product_weight'] ?? old('product_weight')); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="group_code">Group Code</label>
                                    <input type="text" class="form-control" id="group_code" name="group_code"
                                        value="<?php echo e($product['group_code'] ?? old('group_code')); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description">Product Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"><?php echo e($product['description'] ?? old('description')); ?></textarea>
                        </div>

                        <!-- SEO -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                        value="<?php echo e($product['meta_title'] ?? old('meta_title')); ?>">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description"><?php echo e($product['meta_description'] ?? old('meta_description')); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                        value="<?php echo e($product['meta_keywords'] ?? old('meta_keywords')); ?>">
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
                                            <?php echo e(!empty($product['is_featured']) && $product['is_featured'] == 'Yes' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="is_featured">Featured Item</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_bestseller"
                                            id="is_bestseller" value="Yes"
                                            <?php echo e(!empty($product['is_bestseller']) && $product['is_bestseller'] == 'Yes' ? 'checked' : ''); ?>>
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
                                    class="btn-text"><?php echo e(empty($product['id']) ? 'Create Product' : 'Update Product'); ?></span>
                            </button>
                            <a href="<?php echo e(url('admin/products')); ?>" class="btn btn-light px-4">Cancel</a>
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

                        <?php if(!empty($product['product_images'])): ?>
                            <div class="existing-images">
                                <h6 class="text-muted mb-2">Current Images:</h6>
                                <div class="row g-2">
                                    <?php $__currentLoopData = $product['product_images']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-6" id="existing-image-<?php echo e($image['id']); ?>">
                                            <div class="position-relative">
                                                <img src="<?php echo e(asset('front/images/product_images/small/' . $image['image'])); ?>"
                                                    alt="Product Image" class="img-fluid rounded border">
                                                <button type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1 delete-existing-image"
                                                    data-image-id="<?php echo e($image['id']); ?>"
                                                    style="width: 25px; height: 25px; font-size: 10px;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        <?php endif; ?>
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

                        <?php if(!empty($product['product_video'])): ?>
                            <div class="existing-video">
                                <h6 class="text-muted mb-2">Current Video:</h6>
                                <video width="100%" height="200" controls class="rounded border mb-2">
                                    <source src="<?php echo e(asset('front/videos/product_videos/' . $product['product_video'])); ?>"
                                        type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                                <button type="button" class="btn btn-sm btn-danger w-100 confirmDelete"
                                    module="product-video" moduleid="<?php echo e($product['id']); ?>">
                                    <i class="fas fa-trash me-1"></i> Delete Video
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </form>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 if available
            if ($.fn.select2) {
                $('#category_id').select2({
                    placeholder: "Select Category",
                    allowClear: true
                });
                $('#brand_id').select2({
                    placeholder: "Select Brand",
                    allowClear: true
                });
            }

            // AJAX Form Submission with Better Error Handling
            $('#productForm').on('submit', function(e) {
                e.preventDefault();
                clearErrors();
                showLoadingState();

                const formData = new FormData(this);

                // Add additional debugging
                console.log('Form data being sent:');
                for (let pair of formData.entries()) {
                    console.log(pair[0], pair[1]);
                }

                $.ajax({
                    url: $(this).attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    timeout: 60000, // 60 seconds timeout
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    beforeSend: function(xhr) {
                        console.log('Sending AJAX request to:', xhr.url || 'Unknown URL');
                    },
                    success: function(response, textStatus, xhr) {
                        console.log('Success response:', response);
                        hideLoadingState();

                        if (response.success) {
                            showAlert('success', response.message);
                            if (response.redirect) {
                                setTimeout(() => {
                                    window.location.href = response.redirect;
                                }, 1500);
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
                        console.error('AJAX Error Details:');
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

                                // Show debug info if available
                                if (response.error && window.location.hostname ===
                                    'localhost') {
                                    console.error('Debug Error:', response.error);
                                }
                            } else if (xhr.responseText) {
                                // Try to parse HTML error page for useful info
                                const parser = new DOMParser();
                                const doc = parser.parseFromString(xhr.responseText,
                                    'text/html');
                                const errorTitle = doc.querySelector('title');
                                const errorBody = doc.querySelector(
                                    '.exception-message, .whoops-container');

                                if (errorTitle) {
                                    console.error('Error Page Title:', errorTitle.textContent);
                                }
                                if (errorBody) {
                                    console.error('Error Details:', errorBody.textContent
                                        .substring(0, 500));
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
                                errorMessage =
                                    'Server error occurred. Please check the console for details and try again.';
                                break;
                            case 404:
                                errorMessage =
                                    'The requested page was not found. Please check your route.';
                                break;
                            case 403:
                                errorMessage =
                                    'You do not have permission to perform this action.';
                                break;
                            case 419:
                                errorMessage =
                                    'Your session has expired. Please refresh the page and try again.';
                                break;
                            case 413:
                                errorMessage =
                                    'The uploaded files are too large. Please reduce file sizes.';
                                break;
                            case 0:
                                if (textStatus === 'timeout') {
                                    errorMessage = 'Request timed out. Please try again.';
                                } else {
                                    errorMessage =
                                        'Network error. Please check your connection and try again.';
                                }
                                break;
                        }

                        showAlert('error', errorMessage);
                    },
                    complete: function(xhr, textStatus) {
                        console.log('Request completed with status:', textStatus);
                    }
                });
            });

            // Image Upload with Validation
            $('#product_images').on('change', function(e) {
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
                    // Validate file type
                    if (!file.type.startsWith('image/')) {
                        showAlert('error', `File "${file.name}" is not a valid image.`);
                        return;
                    }

                    // Validate file size (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        showAlert('error',
                            `File "${file.name}" is too large. Maximum size is 2MB.`);
                        return;
                    }

                    validFiles++;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = $(`
                    <div class="col-6">
                        <div class="position-relative">
                            <img src="${e.target.result}" class="img-fluid rounded border" style="max-height: 120px; object-fit: cover;">
                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1 remove-preview" 
                                    data-index="${index}" style="width:25px;height:25px;font-size:10px;">
                                <i class="fas fa-times"></i>
                            </button>
                            <small class="d-block text-muted mt-1">${file.name}</small>
                        </div>
                    </div>
                `);
                        previewContainer.append(img);
                    };
                    reader.readAsDataURL(file);
                });

                if (validFiles === 0) {
                    this.value = '';
                }
            });

            // Video Upload with Validation
            $('#product_video').on('change', function(e) {
                const file = e.target.files[0];
                const container = $('#videoPreviewContainer');
                const video = $('#videoPreview');

                if (file) {
                    // Validate file size (20MB)
                    if (file.size > 20 * 1024 * 1024) {
                        showAlert('error', 'Video must be under 20MB.');
                        this.value = '';
                        return;
                    }

                    // Validate file type
                    const validTypes = ['video/mp4', 'video/mov', 'video/avi', 'video/wmv'];
                    if (!validTypes.includes(file.type)) {
                        showAlert('error', 'Please upload a valid video file (MP4, MOV, AVI, WMV).');
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

            // Remove video preview
            $('#removeVideoPreview').on('click', function() {
                $('#product_video').val('');
                $('#videoPreviewContainer').hide();
                $('#videoPreview').attr('src', '');
            });

            // Remove image preview
            $(document).on('click', '.remove-preview', function() {
                $(this).closest('.col-6').remove();
            });

            // Delete existing image with better error handling
            $(document).on('click', '.delete-existing-image', function() {
                const imageId = $(this).data('image-id');
                const $imageContainer = $(`#existing-image-${imageId}`);

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
                        },
                        success: function(response) {
                            if (response.success) {
                                $imageContainer.fadeOut(300, function() {
                                    $(this).remove();
                                });
                                showAlert('success', 'Image deleted successfully!');
                            } else {
                                showAlert('error', response.message ||
                                    'Failed to delete image!');
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
                if (confirm(`Are you sure you want to delete this ${module.replace('-', ' ')}?`)) {
                    window.location.href =
                        `${window.location.origin}/admin/delete-product-image-video/${module}/${moduleId}`;
                }
            });

            // Utility Functions
            function showLoadingState() {
                $('#loadingOverlay').show();
                $('#submitBtn').prop('disabled', true).find('.btn-text').text('Uploading...');
            }

            function hideLoadingState() {
                $('#loadingOverlay').hide();
                $('#submitBtn').prop('disabled', false).find('.btn-text').text(
                    $('#submitBtn').closest('form').find('input[name="_method"]').length ? 'Update Product' :
                    'Create Product'
                );
            }

            function resetForm() {
                $('#product_images, #product_video').val('');
                $('#imagePreviewContainer').empty();
                $('#videoPreviewContainer').hide();
            }

            function clearErrors() {
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            }

            function displayErrors(errors) {
                $.each(errors, function(field, messages) {
                    const fieldName = field.replace(/\./g, '_').replace(/\[/g, '_').replace(/\]/g, '');
                    const input = $(`[name="${field}"], [name="${field}[]"]`);
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
                // Remove existing alerts
                $('.alert-floating').remove();

                const alertClass = {
                    success: 'alert-success',
                    error: 'alert-danger',
                    warning: 'alert-warning',
                    info: 'alert-info'
                } [type] || 'alert-info';

                const alert = $(`
            <div class="alert ${alertClass} alert-dismissible fade show alert-floating" role="alert">
                <strong>${type.charAt(0).toUpperCase() + type.slice(1)}:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `);

                $('body').append(alert);

                // Auto remove after 8 seconds
                setTimeout(() => {
                    alert.fadeOut(300, () => alert.remove());
                }, 8000);
            }
        });
    </script>

    <style>
        .existing-images img,
        #imagePreviewContainer img {
            max-height: 120px;
            object-fit: cover;
        }

        .position-relative .btn {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
    </style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/products/add_edit_product.blade.php ENDPATH**/ ?>