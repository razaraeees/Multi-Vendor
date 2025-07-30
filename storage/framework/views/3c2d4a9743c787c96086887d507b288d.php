



<?php $__env->startSection('content'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Shipping Charges</h4>
                            


                            
                            
                            
                            <?php if(Session::has('success_message')): ?> <!-- Check AdminController.php, updateAdminPassword() method -->
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>


                            <div class="table-responsive pt-3">
                                
                                <table id="shipping" class="table table-bordered"> 
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Country</th>
                                            <th>Rate (0g to 500g)</th>
                                            <th>Rate (501g to 1000g)</th>
                                            <th>Rate (1001g to 2000g)</th>
                                            <th>Rate (2001g to 5000g)</th>
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
                                                <td><?php echo e($shipping['0_500g']); ?></td>
                                                <td><?php echo e($shipping['501g_1000g']); ?></td>
                                                <td><?php echo e($shipping['1001_2000g']); ?></td>
                                                <td><?php echo e($shipping['2001g_5000g']); ?></td>
                                                <td><?php echo e($shipping['above_5000g']); ?></td>
                                                <td>
                                                    <?php if($shipping['status'] == 1): ?>
                                                        <a class="updateShippingStatus" id="shipping-<?php echo e($shipping['id']); ?>" shipping_id="<?php echo e($shipping['id']); ?>" href="javascript:void(0)"> 
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-check" status="Active"></i> 
                                                        </a>
                                                    <?php else: ?> 
                                                        <a class="updateShippingStatus" id="shipping-<?php echo e($shipping['id']); ?>" shipping_id="<?php echo e($shipping['id']); ?>" href="javascript:void(0)"> 
                                                            <i style="font-size: 25px" class="mdi mdi-bookmark-outline" status="Inactive"></i> 
                                                        </a>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?php echo e(url('admin/edit-shipping-charges/' . $shipping['id'])); ?>">
                                                        <i style="font-size: 25px" class="mdi mdi-pencil-box"></i> 
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
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/shipping/shipping_charges.blade.php ENDPATH**/ ?>