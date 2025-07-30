

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Home Page Banners</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/add-edit-banner')); ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Banner
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

    <!-- Banners Table -->
    <div class="card shadow-sm border">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="banners" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Link</th>
                            <th>Title</th>
                            <th>Alt</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $banners; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $banner): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($banner['id']); ?></td>
                                <td>
                                    <img src="<?php echo e(asset('front/images/banner_images/' . $banner['image'])); ?>"
                                         alt="<?php echo e($banner['alt']); ?>"
                                         class="rounded"
                                         style="width: 150px; height: auto;">
                                </td>
                                <td>
                                    <?php echo e($banner['type']); ?>

                                </td>
                                <td><?php echo e($banner['link']); ?></td>
                                <td><?php echo e($banner['title']); ?></td>
                                <td><?php echo e($banner['alt']); ?></td>
                                <td>
                                    <a href="javascript:void(0)" 
                                       class="updateBannerStatus" 
                                       banner_id="<?php echo e($banner['id']); ?>" 
                                       id="banner-<?php echo e($banner['id']); ?>">
                                        <?php if($banner['status'] == 1): ?>
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        <?php else: ?>
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        <?php endif; ?>
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2">
                                        <a href="<?php echo e(url('admin/add-edit-banner/' . $banner['id'])); ?>" 
                                           class="btn btn-sm btn-outline-info px-2 py-1" 
                                           title="Edit Banner">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="banner" 
                                           moduleid="<?php echo e($banner['id']); ?>" 
                                           title="Delete Banner">
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
    $('#banners').DataTable({
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

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/banners/banners.blade.php ENDPATH**/ ?>