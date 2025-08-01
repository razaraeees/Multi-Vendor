

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Shipping Charges</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/dashboard')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    <?php if(Session::has('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Card -->
    <div class="card shadow-sm border">
        <div class="card-body p-3">
            <!-- Table Responsive (Horizontal Scroll) -->
            <div class="table-responsive" style="min-height: 200px;">
                <table id="shipping" class="table table-bordered table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Country</th>
                            <th>Rate (0g - 500g)</th>
                            <th>Rate (501g - 1000g)</th>
                            <th>Rate (1001g - 2000g)</th>
                            <th>Rate (2001g - 5000g)</th>
                            <th>Rate (Above 5000g)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $shippingCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shipping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($shipping['id']); ?></td>
                                <td><?php echo e($shipping['country']); ?></td>
                                <td>₹<?php echo e($shipping['0_500g']); ?></td>
                                <td>₹<?php echo e($shipping['501g_1000g']); ?></td>
                                <td>₹<?php echo e($shipping['1001_2000g']); ?></td>
                                <td>₹<?php echo e($shipping['2001g_5000g']); ?></td>
                                <td>₹<?php echo e($shipping['above_5000g']); ?></td>
                                <td>
                                    <a class="updateShippingStatus" 
                                       id="shipping-<?php echo e($shipping['id']); ?>" 
                                       shipping_id="<?php echo e($shipping['id']); ?>" 
                                       href="javascript:void(0)">
                                        <?php if($shipping['status'] == 1): ?>
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td>

                                    <a href="<?php echo e(url('admin/edit-shipping-charges/' . $shipping['id'])); ?>" 
                                           class="btn btn-sm btn-outline-info px-2 py-1" 
                                           title="Edit Banner">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function () {
            $('#shipping').DataTable({
                responsive: true,
                scrollX: true, // Enable horizontal scrolling
                scrollCollapse: true,
                paging: true,
                pageLength: 10,
                ordering: true,
                info: true,
                columnDefs: [
                    { targets: [7, 8], orderable: false } // Status & Actions not sortable
                ]
            });
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/shipping/shipping_charges.blade.php ENDPATH**/ ?>