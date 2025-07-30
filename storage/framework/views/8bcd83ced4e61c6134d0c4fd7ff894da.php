

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Brands Management</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/add-edit-brand')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Brand
            </a>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(Session::has('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Brands Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="categories" class="table table-bordered dt-responsive nowrap" style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($brand['id']); ?></td>
                                <td><?php echo e($brand['name']); ?></td>
                                <td>
                                    <a class="updateBrandStatus"
                                       id="brand-<?php echo e($brand['id']); ?>"
                                       brand_id="<?php echo e($brand['id']); ?>"
                                       href="javascript:void(0)">
                                        <?php if($brand['status'] == 1): ?>
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                        <!-- Edit Brand -->
                                        <a href="<?php echo e(url('admin/add-edit-brand/' . $brand['id'])); ?>"
                                           class="btn btn-sm btn-outline-info px-2 py-1"
                                           title="Edit Brand">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <!-- Delete Brand -->
                                        <a href="JavaScript:void(0)"
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1"
                                           module="brand"
                                           moduleid="<?php echo e($brand['id']); ?>"
                                           title="Delete Brand">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function() {
    $('#brands').DataTable({
        "scrollY": "500px",
        "scrollCollapse": true,
        "paging": false,
        "info": false,
        "searching": true
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/brands/brands.blade.php ENDPATH**/ ?>