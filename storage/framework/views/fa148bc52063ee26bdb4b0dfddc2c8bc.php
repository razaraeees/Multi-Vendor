

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title"><?php echo e(empty($banner['id']) ? 'Add New Banner' : 'Edit Banner'); ?></h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/banners')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
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

    <!-- Banner Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form
                action="<?php echo e(empty($banner['id']) ? url('admin/add-edit-banner') : url('admin/add-edit-banner/' . $banner['id'])); ?>"
                method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <?php echo csrf_field(); ?>

                <div class="row g-4">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="type">Banner Type</label>
                            <select class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="type" name="type"
                                required>
                                <option value="" disabled selected>Select Type</option>
                                <option value="Slider"
                                    <?php echo e(!empty($banner['type']) && $banner['type'] == 'Slider' ? 'selected' : ''); ?>>
                                    Slider
                                </option>
                                <option value="Fix"
                                    <?php echo e(!empty($banner['type']) && $banner['type'] == 'Fix' ? 'selected' : ''); ?>>
                                    Fix
                                </option>
                            </select>
                            <div class="invalid-feedback">Please select a banner type.</div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="image">Banner Image</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="image"
                                name="image" onchange="previewImage(event)">
                            <div class="form-text">Max size: 2MB, formats: jpg, png, webp</div>

                            <!-- Show existing banner image (if editing) -->
                            <?php if(!empty($banner['image'])): ?>
                                <div class="mt-3">
                                    <strong>Current Image:</strong><br>
                                    <img src="<?php echo e(asset('front/images/banner_images/' . $banner['image'])); ?>"
                                        alt="Current Banner" class="img-thumbnail" style="max-width: 120px;">
                                    &nbsp;|&nbsp;
                                    <a href="javascript:void(0)" class="text-danger confirmDelete" module="banner-image"
                                        moduleid="<?php echo e($banner['id']); ?>">
                                        Delete Image
                                    </a>
                                </div>
                            <?php endif; ?>

                            <!-- New Image Preview -->
                            <div class="mt-3">
                                <img id="imagePreview" src="" alt="Image Preview"
                                    style="max-width: 120px; display: none;" class="img-thumbnail">
                            </div>

                            <div class="invalid-feedback">
                                <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="link">Banner Link</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="link"
                                name="link" placeholder="Enter Banner Link"
                                value="<?php echo e(old('link', $banner['link'] ?? '')); ?>">
                            <div class="invalid-feedback">
                                <?php $__errorArgs = ['link'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="title">Banner Title</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="title"
                                name="title" placeholder="Enter Banner Title"
                                value="<?php echo e(old('title', $banner['title'] ?? '')); ?>">
                            <div class="invalid-feedback">
                                <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="alt">Banner Alternate Text (Alt for SEO)</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['alt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="alt"
                                name="alt" placeholder="Enter Banner Alternate Text"
                                value="<?php echo e(old('alt', $banner['alt'] ?? '')); ?>">
                            <div class="invalid-feedback">
                                <?php $__errorArgs = ['alt'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <?php echo e($message); ?>

                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <?php echo e(empty($banner['id']) ? 'Add Banner' : 'Update Banner'); ?>

                    </button>
                    <button type="reset" class="btn btn-light">Cancel</button>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');

        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
</script>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/banners/add_edit_banner.blade.php ENDPATH**/ ?>