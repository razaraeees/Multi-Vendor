


<?php $__env->startSection('content'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h3 class="font-weight-bold">Admin Settings</h3>
                        </div>
                        <div class="col-12 col-xl-4">
                            <div class="justify-content-end d-flex">
                                <div class="dropdown flex-md-grow-1 flex-xl-grow-0">
                                    <button class="btn btn-sm btn-light bg-white dropdown-toggle" type="button" id="dropdownMenuDate2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="mdi mdi-calendar"></i> Today (10 Jan 2021)
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuDate2">
                                        <a class="dropdown-item" href="#">January - March</a>
                                        <a class="dropdown-item" href="#">March - June</a>
                                        <a class="dropdown-item" href="#">June - August</a>
                                        <a class="dropdown-item" href="#">August - November</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Update Admin Details</h4>


                            
                            
                            <?php if(Session::has('error_message')): ?> <!-- Check AdminController.php, updateAdminPassword() method -->
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <strong>Error:</strong> <?php echo e(Session::get('error_message')); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php endif; ?>



                                
                            <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                

                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <?php endif; ?>



                            
                            
                            
                            <?php if(Session::has('success_message')): ?> <!-- Check AdminController.php, updateAdminPassword() method -->
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>
                

                            
                            <form class="forms-sample" action="<?php echo e(url('admin/update-admin-details')); ?>" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?> <!-- Using the enctype="multipart/form-data" to allow uploading files (images) -->
                                <div class="form-group">
                                    <label>Admin Username/Email</label>
                                    <input class="form-control" value="<?php echo e(Auth::guard('admin')->user()->email); ?>" readonly> <!-- Check updateAdminPassword() method in AdminController.php --> 
                                </div>
                                <div class="form-group">
                                    <label>Admin Type</label>
                                    <input class="form-control" value="<?php echo e(Auth::guard('admin')->user()->type); ?>" readonly> 
                                </div>
                                <div class="form-group">
                                    <label for="admin_name">Name</label>
                                    <input type="text" class="form-control" id="admin_name" placeholder="Enter Name" name="admin_name" value="<?php echo e(Auth::guard('admin')->user()->name); ?>"> 
                                </div>
                                <div class="form-group">
                                    <label for="admin_mobile">Mobile</label>
                                    <input type="text" class="form-control" id="admin_mobile" placeholder="Enter 10 Digit Mobile Number" name="admin_mobile" value="<?php echo e(Auth::guard('admin')->user()->mobile); ?>" maxlength="10" minlength="10"> 
                                </div>
                                <div class="form-group">
                                    <label for="admin_image">Admin Photo</label>
                                    <input type="file" class="form-control" id="admin_image" name="admin_image">
                                    
                                    <?php if(!empty(Auth::guard('admin')->user()->image)): ?> 
                                        <a target="_blank" href="<?php echo e(url('admin/images/photos/' . Auth::guard('admin')->user()->image)); ?>">View Image</a> <!-- We used    target="_blank"    to open the image in another separate page --> 
                                        <input type="hidden" name="current_admin_image" value="<?php echo e(Auth::guard('admin')->user()->image); ?>"> <!-- to send the current admin image url all the time with all the requests --> 
                                    <?php endif; ?>
                                </div>
                                <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                <button type="reset"  class="btn btn-light">Cancel</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <?php echo $__env->make('admin.layout.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- partial -->
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/settings/update_admin_details.blade.php ENDPATH**/ ?>