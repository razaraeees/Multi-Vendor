

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title"><?php echo e(empty($section['id']) ? 'Add New Section' : 'Edit Section'); ?></h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/sections')); ?>" class="btn btn-secondary">
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

    <!-- Section Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form 
                action="<?php echo e(empty($section['id']) ? url('admin/add-edit-section') : url('admin/add-edit-section/' . $section['id'])); ?>" 
                method="POST" 
                enctype="multipart/form-data"
                class="needs-validation" 
                novalidate>
                
                <?php echo csrf_field(); ?>

                <div class="mb-4">
                    <label for="section_name" class="form-label">Section Name</label>
                    <input type="text"
                           class="form-control <?php $__errorArgs = ['section_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="section_name"
                           name="section_name"
                           placeholder="Enter Section Name"
                           value="<?php echo e(!empty($section['name']) ? $section['name'] : old('section_name')); ?>"
                           required>
                    <div class="invalid-feedback">
                        Section name is required.
                    </div>
                </div>

                <div class="d-flex gap-3">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save"></i> <?php echo e(empty($section['id']) ? 'Create Section' : 'Update Section'); ?>

                    </button>
                    <a href="<?php echo e(url('admin/sections')); ?>" class="btn btn-light px-4">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

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
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/sections/add_edit_section.blade.php ENDPATH**/ ?>