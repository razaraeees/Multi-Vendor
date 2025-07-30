


<?php $__env->startSection('content'); ?>
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin">
                    <div class="row">
                        <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                            <h4 class="card-title">Home Page Banners</h4>
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
                            <h4 class="card-title"><?php echo e($title); ?></h4>


                            
                            
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
                

                            
                            <form class="forms-sample"   <?php if(empty($banner['id'])): ?> action="<?php echo e(url('admin/add-edit-banner')); ?>" <?php else: ?> action="<?php echo e(url('admin/add-edit-banner/' . $banner['id'])); ?>" <?php endif; ?>   method="post" enctype="multipart/form-data"> <!-- If the id is not passed in from the route, this measn 'Add a new Banner', but if the id is passed in from the route, this means 'Edit the Banner' --> <!-- Using the enctype="multipart/form-data" to allow uploading files (images) -->
                                <?php echo csrf_field(); ?>

                                <div class="form-group"> 
                                    <label for="type">Banner Type</label>
                                    <select class="form-control" id="type" name="type" required>
                                        <option value="">Select</option>
                                        <option  <?php if(!empty($banner['type']) && $banner['type'] == 'Slider'): ?> selected <?php endif; ?>  value="Slider">Slider</option>
                                        <option  <?php if(!empty($banner['type']) && $banner['type'] == 'Fix'): ?>    selected <?php endif; ?>  value="Fix">Fix</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="image">Banner Image</label>
                                    <input type="file" class="form-control" id="image" name="image">
                                    
                                        <a target="_blank" href="<?php echo e(url('admin/images/photos/' . Auth::guard('admin')->user()->image)); ?>">View Image</a>  <!-- We used    target="_blank"    to open the image in another separate page -->
                                        <input type="hidden" name="current_banner_image" value="<?php echo e(Auth::guard('admin')->user()->image); ?>"> <!-- to send the current admin image url all the time with all the requests --> 


                                    
                                    <?php if(!empty($banner['image'])): ?>
                                        <a target="_blank" href="<?php echo e(url('front/images/banner_images/' . $banner['image'])); ?>">View Banner Image</a>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group">
                                    <label for="link">Banner Link</label>
                                    <input type="text" class="form-control" id="link" placeholder="Enter Banner Link" name="link" <?php if(!empty($banner['link'])): ?> value="<?php echo e($banner['link']); ?>" <?php else: ?> value="<?php echo e(old('link')); ?>" <?php endif; ?>> 
                                </div>
                                <div class="form-group">
                                    <label for="title">Banner Title</label>
                                    <input type="text" class="form-control" id="title" placeholder="Enter Banner Title" name="title" <?php if(!empty($banner['title'])): ?> value="<?php echo e($banner['title']); ?>" <?php else: ?> value="<?php echo e(old('title')); ?>" <?php endif; ?>> 
                                </div>
                                <div class="form-group">
                                    <label for="alt">Banner Alternate Text (Alt for SEO)</label>
                                    <input type="text" class="form-control" id="alt" placeholder="Enter Banner Alternate Text" name="alt" <?php if(!empty($banner['alt'])): ?> value="<?php echo e($banner['alt']); ?>" <?php else: ?> value="<?php echo e(old('alt')); ?>" <?php endif; ?>> 
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
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/banners/add_edit_banner.blade.php ENDPATH**/ ?>