

<?php $__env->startSection('content'); ?>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Users Management</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/users')); ?>" class="btn btn-primary">
                <i class="fas fa-sync"></i> Refresh
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

    <!-- Users Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="users" class="table table-hover align-middle">
                    <thead class="bg-light" style="background-color: black; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Pincode</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($user['id']); ?></td>
                                <td><?php echo e($user['name']); ?></td>
                                <td><?php echo e($user['address']); ?></td>
                                <td><?php echo e($user['city']); ?></td>
                                <td><?php echo e($user['state']); ?></td>
                                <td><?php echo e($user['country']); ?></td>
                                <td><?php echo e($user['pincode']); ?></td>
                                <td><?php echo e($user['mobile']); ?></td>
                                <td><?php echo e($user['email']); ?></td>
                                <td>
                                    <a href="javascript:void(0)" 
                                       class="updateUserStatus" 
                                       user_id="<?php echo e($user['id']); ?>" 
                                       id="user-<?php echo e($user['id']); ?>">
                                        <?php if($user['status'] == 1): ?>
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
                                           module="user" 
                                           moduleid="<?php echo e($user['id']); ?>" 
                                           title="Delete User">
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
        $('#users').DataTable({
            responsive: true,
            scrollY: 400,
            scrollCollapse: true,
            paging: false,
            fixedHeader: true,
            columnDefs: [
                { targets: [9], orderable: false }, // Status column
                { targets: [10], orderable: false } // Actions column
            ]
        });
    });

    // Status Update (AJAX)
    // $(document).on('click', '.updateUserStatus', function () {
    //     let userId = $(this).attr('user_id');
    //     let status = $(this).children('i').attr('status');

    //     $.ajax({
    //         url: "<?php echo e(url('admin/update-user-status')); ?>",
    //         type: "POST",
    //          {
    //             _token: "<?php echo e(csrf_token()); ?>",
    //             user_id: userId,
    //             status: status
    //         },
    //         success: function (response) {
    //             if (response.status == 1) {
    //                 $('#user-' + userId).html('<i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>');
    //             } else {
    //                 $('#user-' + userId).html('<i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>');
    //             }
    //         }
    //     });
    // });
</script>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/users/users.blade.php ENDPATH**/ ?>