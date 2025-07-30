

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Subscribers</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/export-subscribers')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-download"></i> Export Subscribers
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

    <!-- Subscribers Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="subscribers" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Subscribed On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $subscribers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subscriber): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($subscriber['id']); ?></td>
                                <td><?php echo e($subscriber['email']); ?></td>
                                <td><?php echo e(date('M d, Y h:i A', strtotime($subscriber['created_at']))); ?></td>
                                <td>
                                    <a href="javascript:void(0)" 
                                       class="updateSubscriberStatus" 
                                       subscriber_id="<?php echo e($subscriber['id']); ?>" 
                                       id="subscriber-<?php echo e($subscriber['id']); ?>">
                                        <?php if($subscriber['status'] == 1): ?>
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2">
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="subscriber" 
                                           moduleid="<?php echo e($subscriber['id']); ?>" 
                                           title="Delete Subscriber">
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
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
    $('#subscribers').DataTable({
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

        // Status Update (AJAX)
        $(document).on('click', '.updateSubscriberStatus', function () {
            let subscriberId = $(this).attr('subscriber_id');
            let status = $(this).children('span').attr('status');

            $.ajax({
                url: "<?php echo e(url('admin/update-subscriber-status')); ?>",
                type: "POST",
                 {
                    _token: "<?php echo e(csrf_token()); ?>",
                    subscriber_id: subscriberId,
                    status: status
                },
                success: function (response) {
                    if (response.status == 1) {
                        $('#subscriber-' + subscriberId).html('<span class="badge bg-success" status="Active">Active</span>');
                    } else {
                        $('#subscriber-' + subscriberId).html('<span class="badge bg-danger" status="Inactive">Inactive</span>');
                    }
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/subscribers/subscribers.blade.php ENDPATH**/ ?>