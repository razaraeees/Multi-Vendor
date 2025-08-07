

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Products Management</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/add-edit-product')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Success Message -->
    <?php if(Session::has('success_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif; ?>

    <!-- Products Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="products" class="table table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Product Color</th>
                            <th>Product Image</th>
                            <th>Category</th>
                            <th>Added by</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($product['id']); ?></td>
                                <td><?php echo e($product['product_name']); ?></td>
                                <td><?php echo e($product['product_code']); ?></td>
                                <td><?php echo e($product['product_color']); ?></td>
                                <td>
                                    <?php if(!empty($product['product_image'])): ?>
                                        <img src="<?php echo e(asset('front/images/product_images/small/' . $product['product_image'])); ?>"
                                            alt="Product Image"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('front/images/product_images/small/no-image.png')); ?>"
                                            alt="No Image"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($product['category']['category_name'] ?? 'No Category'); ?></td>
                                <td>
                                    <?php if($product['admin_type'] == 'vendor'): ?>
                                        <a target="_blank"
                                            href="<?php echo e(url('admin/view-vendor-details/' . $product['admin_id'])); ?>"
                                            class="text-primary font-weight-bold">
                                            <?php echo e(ucfirst($product['admin_type'])); ?>

                                        </a>
                                    <?php else: ?>
                                        <?php echo e(ucfirst($product['admin_type'])); ?>

                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($product['status'] == 1): ?>
                                        <a class="updateProductStatus" id="product-<?php echo e($product['id']); ?>"
                                            product_id="<?php echo e($product['id']); ?>" href="javascript:void(0)">
                                            <i class="fas fa-check-circle text-success" status="Active"></i>
                                        </a>
                                    <?php else: ?>
                                        <a class="updateProductStatus" id="product-<?php echo e($product['id']); ?>"
                                            product_id="<?php echo e($product['id']); ?>" href="javascript:void(0)">
                                            <i class="fas fa-times-circle text-secondary" status="Inactive"></i>
                                        </a>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                        <!-- Edit Product -->
                                        <a href="<?php echo e(url('admin/add-edit-product/' . $product['id'])); ?>"
                                            class="btn btn-sm btn-outline-info px-2 py-1" title="Edit Product">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <!-- Add Attributes -->
                                        <a href="<?php echo e(url('admin/add-edit-attributes/' . $product['id'])); ?>"
                                            class="btn btn-sm btn-outline-warning px-2 py-1" title="Add Attributes">
                                            <i class="fas fa-plus"></i>
                                        </a>

                                        <!-- Add Images -->
                                        

                                        <!-- Delete Product -->
                                        <a href="JavaScript:void(0)"
                                            class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" module="product"
                                            moduleid="<?php echo e($product['id']); ?>" title="Delete Product">
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
    $(document).ready(function() {
        $('#products').DataTable({
            responsive: true,
            scrollY: 400, // Set the height of the scrollable area
            scrollCollapse: true,
            paging: false, // Disable pagination if you want only scroll
            fixedHeader: true, // Keep headers fixed while scrolling
            columnDefs: [{
                    targets: [4],
                    orderable: false
                }, // Make image column not sortable
                {
                    targets: [9],
                    orderable: false
                } // Make actions column not sortable
            ]
        });
    });
</script>

<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/products/products.blade.php ENDPATH**/ ?>