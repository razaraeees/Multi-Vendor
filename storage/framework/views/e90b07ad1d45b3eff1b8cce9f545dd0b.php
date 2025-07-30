

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Products Management</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/add-edit-product')); ?>" class="btn btn-primary">
                <i class="fas fa-arrow"></i> Back to listing
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
    <div class="row">
        <div class="col-lg-8">
            <div class="card border">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i> Product Information</h5>
                </div>
                <div class="card-body">
                    <form class="forms-sample"
                        <?php if(empty($product['id'])): ?> action="<?php echo e(url('admin/add-edit-product')); ?>" 
                          <?php else: ?> action="<?php echo e(url('admin/add-edit-product/' . $product['id'])); ?>" <?php endif; ?>
                        method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <!-- Category Selection -->
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
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <optgroup label="<?php echo e($section['name']); ?>">
                                                <?php $__currentLoopData = $section['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($category['id']); ?>"
                                                        <?php echo e(!empty($product['category_id']) && $product['category_id'] == $category['id'] ? 'selected' : ''); ?>>
                                                        <?php echo e($category['category_name']); ?>

                                                    </option>
                                                    <?php $__currentLoopData = $category['sub_categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($subcategory['id']); ?>"
                                                            <?php echo e(!empty($product['category_id']) && $product['category_id'] == $subcategory['id'] ? 'selected' : ''); ?>>
                                                            &nbsp;&nbsp;&nbsp;-- <?php echo e($subcategory['category_name']); ?>

                                                        </option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </optgroup>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                    <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>

                            <!-- Brand Selection -->
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
                                    <?php $__errorArgs = ['brand_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>

                        <!-- Including the related filters -->
                        <div class="loadFilters">
                            <?php echo $__env->make('admin.filters.category_filters', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        </div>

                        <div class="row">
                            <!-- Product Name -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_name">Product Name</label>
                                    <input type="text" class="form-control" id="product_name" name="product_name"
                                        placeholder="Enter Product Name"
                                        value="<?php echo e($product['product_name'] ?? old('product_name')); ?>" required>
                                </div>
                            </div>

                            <!-- Product Code -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_code">Product Code</label>
                                    <input type="text" class="form-control" id="product_code" name="product_code"
                                        placeholder="Enter Product Code"
                                        value="<?php echo e($product['product_code'] ?? old('product_code')); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Product Color -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_color">Product Color</label>
                                    <input type="text" class="form-control" id="product_color" name="product_color"
                                        placeholder="Enter Product Color"
                                        value="<?php echo e($product['product_color'] ?? old('product_color')); ?>">
                                </div>
                            </div>

                            <!-- Product Price -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_price">Product Price</label>
                                    <input type="number" class="form-control" id="product_price" name="product_price"
                                        placeholder="Enter Product Price"
                                        value="<?php echo e($product['product_price'] ?? old('product_price')); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Product Discount -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_discount">Product Discount (%)</label>
                                    <input type="number" class="form-control" id="product_discount"
                                        name="product_discount" placeholder="Enter Discount"
                                        value="<?php echo e($product['product_discount'] ?? old('product_discount')); ?>">
                                </div>
                            </div>

                            <!-- Product Weight -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="product_weight">Product Weight (grams)</label>
                                    <input type="number" class="form-control" id="product_weight" name="product_weight"
                                        placeholder="Enter Product Weight"
                                        value="<?php echo e($product['product_weight'] ?? old('product_weight')); ?>">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Group Code -->
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="group_code">Group Code</label>
                                    <input type="text" class="form-control" id="group_code" name="group_code"
                                        placeholder="Enter Group Code"
                                        value="<?php echo e($product['group_code'] ?? old('group_code')); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Product Description (Full Width) -->
                        <div class="form-group mb-3">
                            <label for="description">Product Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                placeholder="Enter product description"><?php echo e($product['description'] ?? old('description')); ?></textarea>
                        </div>

                        <!-- SEO Meta Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_title">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title"
                                        placeholder="Enter Meta Title"
                                        value="<?php echo e($product['meta_title'] ?? old('meta_title')); ?>">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_description">Meta Description</label>
                                    <textarea type="text" class="form-control" id="meta_description"
                                        name="meta_description" placeholder="Enter Meta Description"
                                        ><?php echo e($product['meta_description'] ?? old('meta_description')); ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="meta_keywords">Meta Keywords</label>
                                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                        placeholder="Enter Meta Keywords"
                                        value="<?php echo e($product['meta_keywords'] ?? old('meta_keywords')); ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Product Options -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_featured"
                                            id="is_featured" value="Yes"
                                            <?php echo e(!empty($product['is_featured']) && $product['is_featured'] == 'Yes' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="is_featured">
                                            Featured Item
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="is_bestseller"
                                            id="is_bestseller" value="Yes"
                                            <?php echo e(!empty($product['is_bestseller']) && $product['is_bestseller'] == 'Yes' ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="is_bestseller">
                                            Best Seller Item
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="fas fa-save"></i>
                                <?php echo e(empty($product['id']) ? 'Create Product' : 'Update Product'); ?>

                            </button>
                            <a href="<?php echo e(url('admin/products')); ?>" class="btn btn-light px-4">
                                Cancel
                            </a>
                        </div>
                    </form>

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
                        <input type="file" class="form-control" id="product_images" name="product_images[]" multiple
                            accept="image/*">
                        <small class="text-muted">Recommended Size: 1000x1000px. You can select multiple images.</small>
                    </div>

                    <!-- Image Preview Container -->
                    <div id="imagePreviewContainer" class="row g-2 mb-3">
                        <!-- New uploaded images preview will appear here -->
                    </div>

                    <!-- Existing Images -->
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

                    <?php if(!empty($product['product_image']) && empty($product['product_images'])): ?>
                        <!-- For old single image format -->
                        <div class="existing-images">
                            <h6 class="text-muted mb-2">Current Image:</h6>
                            <div class="position-relative d-inline-block">
                                <img src="<?php echo e(asset('front/images/product_images/small/' . $product['product_image'])); ?>"
                                    alt="Product Image" class="img-fluid rounded border" style="max-width: 150px;">
                                <button type="button"
                                    class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1 confirmDelete"
                                    module="product-image" moduleid="<?php echo e($product['id']); ?>"
                                    style="width: 25px; height: 25px; font-size: 10px;">
                                    <i class="fas fa-times"></i>
                                </button>
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
                        <small class="text-muted">Maximum Size: 2MB</small>
                    </div>

                    <!-- Video Preview -->
                    <div id="videoPreviewContainer" class="mb-3" style="display: none;">
                        <video id="videoPreview" width="100%" height="200" controls class="rounded border">
                            Your browser does not support the video tag.
                        </video>
                        <button type="button" class="btn btn-sm btn-danger mt-2 w-100" id="removeVideoPreview">
                            <i class="fas fa-times me-1"></i> Remove Video
                        </button>
                    </div>

                    <!-- Existing Video -->
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

    <!-- Custom JavaScript -->
    <script>
        // Multiple Image Preview
        document.getElementById('product_images').addEventListener('change', function(e) {
            const files = e.target.files;
            const previewContainer = document.getElementById('imagePreviewContainer');
            previewContainer.innerHTML = '';

            Array.from(files).forEach((file, index) => {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const imageDiv = document.createElement('div');
                        imageDiv.className = 'col-6';
                        imageDiv.innerHTML = `
                            <div class="position-relative">
                                <img src="${e.target.result}" alt="Preview" class="rounded border">
                                <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-1 rounded-circle p-1 remove-preview" 
                                        data-index="${index}" style="width: 25px; height: 25px; font-size: 10px;">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;
                        previewContainer.appendChild(imageDiv);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Remove image preview
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-preview')) {
                e.target.closest('.col-6').remove();
            }
        });

        // Video Preview
        document.getElementById('product_video').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const previewContainer = document.getElementById('videoPreviewContainer');
            const videoPreview = document.getElementById('videoPreview');

            if (file && file.type.startsWith('video/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    videoPreview.src = e.target.result;
                    previewContainer.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Remove video preview
        document.getElementById('removeVideoPreview').addEventListener('click', function() {
            document.getElementById('product_video').value = '';
            document.getElementById('videoPreviewContainer').style.display = 'none';
            document.getElementById('videoPreview').src = '';
        });

        // Delete existing images
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-existing-image')) {
                const imageId = e.target.closest('.delete-existing-image').getAttribute('data-image-id');
                if (confirm('Are you sure you want to delete this image?')) {
                    // Make AJAX call to delete image
                    fetch(`<?php echo e(url('admin/delete-product-image')); ?>/${imageId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`existing-image-${imageId}`).remove();
                                alert('Image deleted successfully!');
                            } else {
                                alert('Error deleting image!');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Error deleting image!');
                        });
                }
            }
        });

        // Existing delete functionality for single image/video
        document.addEventListener('click', function(e) {
            if (e.target.closest('.confirmDelete')) {
                const element = e.target.closest('.confirmDelete');
                const module = element.getAttribute('module');
                const moduleId = element.getAttribute('moduleid');

                if (confirm('Are you sure you want to delete this ' + module.replace('-', ' ') + '?')) {
                    window.location.href = `<?php echo e(url('admin/delete-product-image-video')); ?>/${module}/${moduleId}`;
                }
            }
        });
    </script>

    <style>
        .form-check-input:checked {
            background-color: #198754;
            border-color: #198754;
        }

        .existing-images img,
        #imagePreviewContainer img {
            max-height: 120px;
            object-fit: cover;
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
    </style>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/products/add_edit_product.blade.php ENDPATH**/ ?>