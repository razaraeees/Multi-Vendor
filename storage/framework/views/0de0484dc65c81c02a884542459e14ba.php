

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title"><?php echo e(empty($brand['id']) ? 'Add New Brand' : 'Edit Brand'); ?></h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/brands')); ?>" class="btn btn-secondary btn-sm">
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

    <!-- Brand Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form 
                action="<?php echo e(empty($brand['id']) ? url('admin/add-edit-brand') : url('admin/add-edit-brand/' . $brand['id'])); ?>"
                method="POST"
                enctype="multipart/form-data"
                class="needs-validation"
                novalidate>

                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label for="brand_name" class="form-label">Brand Name</label>
                    <input type="text"
                           class="form-control <?php $__errorArgs = ['brand_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="brand_name"
                           name="brand_name"
                           placeholder="Enter Brand Name"
                           value="<?php echo e(old('brand_name', $brand['name'] ?? '')); ?>"
                           required>
                    <div class="invalid-feedback">
                        Brand name is required.
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> <?php echo e(empty($brand['id']) ? 'Create Brand' : 'Update Brand'); ?>

                    </button>
                    <a href="<?php echo e(url('admin/brands')); ?>" class="btn btn-light px-4">
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/brands/add_edit_brand.blade.php ENDPATH**/ ?>