



<?php $__env->startSection('content'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Ratings</h4>
                        
                            

                            
                            
                            
                            <?php if(Session::has('success_message')): ?> <!-- Check AdminController.php, updateAdminPassword() method -->
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>


                            <div class="table-responsive pt-3">
                                
                                <table id="ratings" class="table table-bordered"> 
                                    <thead>
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
                                                        <a class="updateRatingStatus" id="rating-<?php echo e($rating['id']); ?>" rating_id="<?php echo e($rating['id']); ?>" href="javascript:void(0)"> 
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check" status="Active"></i> 
                                                        </a>
                                                    <?php else: ?> 
                                                        <a class="updateRatingStatus" id="rating-<?php echo e($rating['id']); ?>" rating_id="<?php echo e($rating['id']); ?>" href="javascript:void(0)"> 
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline" status="Inactive"></i> 
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    
                                                         
                                                    

                                                    
                                                    
                                                         
                                                    
                                                    <a href="JavaScript:void(0)" class="confirmDelete" module="rating" moduleid="<?php echo e($rating['id']); ?>">
                                                        <i style="font-size: 25px" class="mdi mdi-file-excel-box"></i> 
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->
        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2022. All rights reserved.</span>
            </div>
        </footer>
        <!-- partial -->
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/ratings/ratings.blade.php ENDPATH**/ ?>