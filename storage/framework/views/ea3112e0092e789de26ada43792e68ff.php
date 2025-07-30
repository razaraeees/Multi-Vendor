

<?php $__env->startSection('content'); ?>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Orders Management</h1>
    </div>

    <!-- Success Message -->
    <?php if(Session::has('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Orders Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="orders" class="table table-bordered dt-responsive nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Ordered Products</th>
                            <th>Order Amount</th>
                            <th>Order Status</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($order['orders_products']): ?>
                                <tr>
                                    <td><?php echo e($order['id']); ?></td>
                                    <td><?php echo e(date('Y-m-d h:i:s', strtotime($order['created_at']))); ?></td>
                                    <td><?php echo e($order['name']); ?></td>
                                    <td><?php echo e($order['email']); ?></td>
                                    <td>
                                        <?php $__currentLoopData = $order['orders_products']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php echo e($product['product_code']); ?> (<?php echo e($product['product_qty']); ?>)
                                            <br>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </td>
                                    <td><?php echo e($order['grand_total']); ?></td>
                                    <td><?php echo e($order['order_status']); ?></td>
                                    <td><?php echo e($order['payment_method']); ?></td>
                                    <td>
                                        <div class="action-buttons d-flex gap-2">
                                            <a title="View Order Details" 
                                               href="<?php echo e(url('admin/orders/' . $order['id'])); ?>"
                                               class="btn btn-sm btn-outline-info px-2 py-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a title="View Invoice" 
                                               href="<?php echo e(url('admin/orders/invoice/' . $order['id'])); ?>" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-secondary px-2 py-1">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a title="Download PDF" 
                                               href="<?php echo e(url('admin/orders/invoice/pdf/' . $order['id'])); ?>" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-danger px-2 py-1">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<script>
    $(document).ready(function () {
    $('#orders').DataTable({
        responsive: true,
        scrollY: 400, // Set the height of the scrollable area
        scrollCollapse: true,
        paging: false, // Disable pagination if you want only scroll
        fixedHeader: true, // Keep headers fixed while scrolling
        columnDefs: [
            { targets: [4], orderable: false }, // Make image column not sortable
            { targets: [9], orderable: false }  // Make actions column not sortable
        ]
    });
});
</script>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/orders/orders.blade.php ENDPATH**/ ?>