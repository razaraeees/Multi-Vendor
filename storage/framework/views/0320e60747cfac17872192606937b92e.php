

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Sections Management</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/add-edit-section')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Section
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

    <!-- Sections Table -->
    <div class="card shadow-sm border">
        <div class="card-body p-3">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table id="sections" class="table table-hover align-middle mb-0">
                    <thead class="bg-light" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $sections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $section): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($section['id']); ?></td>
                                <td><?php echo e($section['name']); ?></td>
                                <td>
                                    <a class="updateSectionStatus"
                                       id="section-<?php echo e($section['id']); ?>"
                                       section_id="<?php echo e($section['id']); ?>"
                                       href="javascript:void(0)">
                                        <?php if($section['status'] == 1): ?>
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                        <!-- Edit Section -->
                                        <a href="<?php echo e(url('admin/add-edit-section/' . $section['id'])); ?>"
                                           class="btn btn-sm btn-outline-info px-2 py-1"
                                           title="Edit Section">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <!-- Delete Section -->
                                        <a href="JavaScript:void(0)"
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1"
                                           module="section"
                                           moduleid="<?php echo e($section['id']); ?>"
                                           title="Delete Section">
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
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/sections/sections.blade.php ENDPATH**/ ?>