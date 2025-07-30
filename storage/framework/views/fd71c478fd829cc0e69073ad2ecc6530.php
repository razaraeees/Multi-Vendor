

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Admins Management</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/add-edit-admin')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Admin
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

    <!-- Admins Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="admins" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td class="ps-4 text-muted"><strong>#<?php echo e($admin['id']); ?></strong></td>
                                <td><?php echo e($admin['name']); ?></td>
                                <td>
                                    <span
                                        class="badge bg-<?php echo e($admin['type'] == 'superadmin' ? 'danger' : ($admin['type'] == 'admin' ? 'primary' : 'info')); ?> px-3 py-2">
                                        <?php echo e(ucfirst($admin['type'])); ?>

                                    </span>
                                </td>
                                <td><?php echo e($admin['mobile']); ?></td>
                                <td><?php echo e($admin['email']); ?></td>
                                <td>
                                    <div class="avatar">
                                        <?php if($admin['image']): ?>
                                            <img src="<?php echo e(asset('admin/images/photos/' . $admin['image'])); ?>"
                                                alt="Admin Image" class="rounded-circle" width="50" height="50"
                                                style="object-fit: cover;">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('admin/images/photos/no-image.gif')); ?>" alt="No Image"
                                                class="rounded-circle" width="50" height="50"
                                                style="object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <a class="updateAdminStatus" id="admin-<?php echo e($admin['id']); ?>"
                                        admin_id="<?php echo e($admin['id']); ?>" href="javascript:void(0)">
                                        <?php if($admin['status'] == 1): ?>
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;"
                                                status="Active"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;"
                                                status="Inactive"></i>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                       
                                        <a href="<?php echo e(url('admin/view-vendor-details/' . $admin['id'])); ?>"
                                            class="btn btn-sm btn-outline-info px-2 py-1" title="View Vendor Details">
                                            <i class="fas fa-eye"></i>
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

<?php $__env->startSection('scripts'); ?>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#admins').DataTable({
                responsive: true,
                scrollY: '400px',
                scrollCollapse: true,
                paging: true,
                pageLength: 10,
                fixedHeader: true,
                language: {
                    search: "Search Admins:",
                    lengthMenu: "Show _MENU_ admins per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ admins",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [{
                        targets: [5], // Image column (0-indexed)
                        orderable: false
                    },
                    {
                        targets: [6], // Status column
                        orderable: false
                    },
                    {
                        targets: [7], // Actions column
                        orderable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ] // Sort by ID descending
            });
        });

        // Update Admin Status
        $(document).on('click', '.updateAdminStatus', function() {
            let adminId = $(this).attr('admin_id');
            let status = $(this).children('i').attr('status');

            $.ajax({
                url: "<?php echo e(url('admin/update-admin-status')); ?>",
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    admin_id: adminId,
                    status: status
                },
                success: function(response) {
                    if (response.status == 1) {
                        $('#admin-' + adminId).html(
                            '<i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>'
                        );
                    } else {
                        $('#admin-' + adminId).html(
                            '<i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/admins/admins.blade.php ENDPATH**/ ?>