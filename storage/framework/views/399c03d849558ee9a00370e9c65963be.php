

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title"><?php echo e(empty($filter['id']) ? 'Add New Filter' : 'Edit Filter'); ?></h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/filters')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Alerts -->
    <?php if(Session::has('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <?php if(Session::has('error_message')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> <?php echo e(Session::get('error_message')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Filter Form -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            <form 
                action="<?php echo e(empty($filter['id']) ? url('admin/add-edit-filter') : url('admin/add-edit-filter/' . $filter['id'])); ?>"
                method="POST"
                enctype="multipart/form-data"
                class="needs-validation"
                novalidate>

                <?php echo csrf_field(); ?>

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">

                        <div class="form-group mb-4">
                            <label for="cat_ids">Select Categories</label>
                            <select name="cat_ids[]" 
                                    id="cat_ids" 
                                    class="form-control <?php $__errorArgs = ['cat_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                    multiple 
                                    style="height: 200px"
                                    required>
                                <option value="" disabled>Select Categories</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <optgroup label="<?php echo e($section['name']); ?>">
                                        <?php $__currentLoopData = $section['categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($category['id']); ?>" 
                                                <?php echo e((!empty($filter['category_id']) && $filter['category_id'] == $category['id']) ? 'selected' : ''); ?>>
                                                <?php echo e($category['category_name']); ?>

                                            </option>
                                            <?php $__currentLoopData = $category['sub_categories']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($subcategory['id']); ?>" 
                                                    <?php echo e((!empty($filter['category_id']) && $filter['category_id'] == $subcategory['id']) ? 'selected' : ''); ?>>
                                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--&nbsp;<?php echo e($subcategory['category_name']); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </optgroup>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                   class="form-control <?php $__errorArgs = ['filter_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="filter_name"
                                   name="filter_name"
                                   placeholder="Enter Filter Name"
                                   value="<?php echo e(old('filter_name', $filter['filter_name'] ?? '')); ?>"
                                   required>
                            <div class="invalid-feedback">
                                Filter name is required.
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="filter_column">Filter Column</label>
                            <input type="text"
                                   class="form-control <?php $__errorArgs = ['filter_column'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="filter_column"
                                   name="filter_column"
                                   placeholder="e.g. brand, color, size"
                                   value="<?php echo e(old('filter_column', $filter['filter_column'] ?? '')); ?>"
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
                        <i class="fas fa-save mr-1"></i> <?php echo e(empty($filter['id']) ? 'Create Filter' : 'Update Filter'); ?>

                    </button>
                    <a href="<?php echo e(url('admin/filters')); ?>" class="btn btn-light px-4">
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/filters/add_edit_filter.blade.php ENDPATH**/ ?>