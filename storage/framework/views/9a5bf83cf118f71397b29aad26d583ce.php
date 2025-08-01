


<?php $__env->startSection('content'); ?>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Ratings Management</h1>
    </div>

    <!-- Success Message -->
    <?php if(Session::has('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Ratings Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="ratings" class="table table-bordered dt-responsive nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>User Email</th>
                            <th>Review</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $ratings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rating): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($rating['id']); ?></td>
                                <td>
                                    <a target="_blank" href="<?php echo e(url('product/' . $rating['product']['id'])); ?>">
                                        <?php echo e($rating['product']['product_name']); ?>

                                    </a>
                                </td>
                                <td><?php echo e($rating['user']['email']); ?></td>
                                <td><?php echo e($rating['review']); ?></td>
                                <td><?php echo e($rating['rating']); ?></td>
                                <td>
                                    <?php if($rating['status'] == 1): ?>
                                        <a class="updateRatingStatus" 
                                           id="rating-<?php echo e($rating['id']); ?>" 
                                           rating_id="<?php echo e($rating['id']); ?>" 
                                           href="javascript:void(0)">
                                            <i style="font-size: 22px" 
                                               class="fas fa-check-circle text-success" 
                                               status="Active"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="updateRatingStatus" 
                                           id="rating-<?php echo e($rating['id']); ?>" 
                                           rating_id="<?php echo e($rating['id']); ?>" 
                                           href="javascript:void(0)">
                                            <i style="font-size: 22px" 
                                               class="fas fa-times-circle text-secondary" 
                                               status="Inactive"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2">
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="rating" 
                                           moduleid="<?php echo e($rating['id']); ?>">
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

<script>
    $(document).ready(function () {
        $('#ratings').DataTable({
            responsive: true,
            scrollY: 400,
            scrollCollapse: true,
            paging: false,
            fixedHeader: true,
            columnDefs: [
                { targets: [6], orderable: false } // Actions column not sortable
            ]
        });
    });
</script>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/ratings/ratings.blade.php ENDPATH**/ ?>