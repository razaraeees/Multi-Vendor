

<?php $__env->startSection('content'); ?>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Vendors Details</h1>
        <div class="page-actions">
            <a href="<?php echo e(url('admin/admins/vendor')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to List
            </a>
        </div>
    </div>

    <?php if(empty($vendorDetails)): ?>
        <div class="alert alert-warning">
            <i class="mdi mdi-alert mr-2"></i>
            <strong>No Data Found:</strong> Vendor details are not available.
        </div>
    <?php else: ?>
        <div class="row">
            
            <div class="col-md-6 grid-margin stretch-card mt-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-account mr-2"></i>Personal Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-email mr-1"></i>Email
                                    </label>
                                    <input class="form-control form-control-sm bg-light"
                                        value="<?php echo e($vendorDetails['vendor_personal']['email'] ?? 'N/A'); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-account-outline mr-1"></i>Name
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        value="<?php echo e($vendorDetails['vendor_personal']['name'] ?? 'N/A'); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-map-marker mr-1"></i>Address
                                    </label>
                                    <textarea class="form-control form-control-sm bg-light" rows="2" readonly><?php echo e($vendorDetails['vendor_personal']['address'] ?? 'N/A'); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-city mr-1"></i>City
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        value="<?php echo e($vendorDetails['vendor_personal']['city'] ?? 'N/A'); ?>" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-map mr-1"></i>State
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        value="<?php echo e($vendorDetails['vendor_personal']['state'] ?? 'N/A'); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-flag mr-1"></i>Country
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        value="<?php echo e($vendorDetails['vendor_personal']['country'] ?? 'N/A'); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-mailbox mr-1"></i>Pincode
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        value="<?php echo e($vendorDetails['vendor_personal']['pincode'] ?? 'N/A'); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label class="font-weight-medium text-muted">
                                        <i class="mdi mdi-phone mr-1"></i>Mobile
                                    </label>
                                    <input type="text" class="form-control form-control-sm bg-light"
                                        value="<?php echo e($vendorDetails['vendor_personal']['mobile'] ?? 'N/A'); ?>" readonly>
                                </div>
                            </div>
                        </div>

                        <?php if(!empty($vendorDetails['image'])): ?>
                            <div class="form-group text-center mt-3">
                                <label class="font-weight-medium text-muted d-block">
                                    <i class="mdi mdi-camera mr-1"></i>Vendor Photo
                                </label>
                                <div class="vendor-photo-container">
                                    <img class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;"
                                        src="<?php echo e(url('admin/images/photos/' . $vendorDetails['image'])); ?>" 
                                        alt="Vendor Photo"
                                        onerror="this.src='<?php echo e(asset('admin/images/photos/no-image.gif')); ?>'">
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="form-group text-center mt-3">
                                <label class="font-weight-medium text-muted d-block">
                                    <i class="mdi mdi-camera mr-1"></i>Vendor Photo
                                </label>
                                <div class="vendor-photo-container">
                                    <img class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;"
                                        src="<?php echo e(asset('admin/images/photos/no-image.gif')); ?>" alt="No Photo Available">
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 grid-margin stretch-card mt-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-store mr-2"></i>Business Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if(empty($vendorDetails['vendor_business'])): ?>
                            <div class="alert alert-info">
                                <i class="mdi mdi-information mr-2"></i>
                                Business information not provided yet.
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-store-24-hour mr-1"></i>Shop Name
                                        </label>
                                        <input type="text" class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_name'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-map-marker mr-1"></i>Shop Address
                                        </label>
                                        <textarea class="form-control form-control-sm bg-light" rows="2" readonly><?php echo e($vendorDetails['vendor_business']['shop_address'] ?? 'N/A'); ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-city mr-1"></i>Shop City
                                        </label>
                                        <input type="text" class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_city'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-map mr-1"></i>Shop State
                                        </label>
                                        <input type="text" class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_state'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-flag mr-1"></i>Shop Country
                                        </label>
                                        <input type="text" class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_country'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-mailbox mr-1"></i>Shop Pincode
                                        </label>
                                        <input type="text" class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_pincode'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-phone mr-1"></i>Shop Mobile
                                        </label>
                                        <input type="text" class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_mobile'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-web mr-1"></i>Shop Website
                                        </label>
                                        <input type="text" class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_website'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-email mr-1"></i>Shop Email
                                        </label>
                                        <input class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['shop_email'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-certificate mr-1"></i>Business License
                                        </label>
                                        <input class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['business_license_number'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-file-document mr-1"></i>GST Number
                                        </label>
                                        <input class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['gst_number'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label class="font-weight-medium text-muted">
                                            <i class="mdi mdi-card-account-details mr-1"></i>PAN Number
                                        </label>
                                        <input class="form-control form-control-sm bg-light"
                                            value="<?php echo e($vendorDetails['vendor_business']['pan_number'] ?? 'N/A'); ?>" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="font-weight-medium text-muted">
                                    <i class="mdi mdi-file-check mr-1"></i>Address Proof
                                </label>
                                <input class="form-control form-control-sm bg-light"
                                    value="<?php echo e($vendorDetails['vendor_business']['address_proof'] ?? 'N/A'); ?>" readonly>
                            </div>

                            <?php if(!empty($vendorDetails['vendor_business']['address_proof_image'])): ?>
                                <div class="form-group text-center">
                                    <label class="font-weight-medium text-muted d-block">
                                        <i class="mdi mdi-image mr-1"></i>Address Proof Image
                                    </label>
                                    <div class="proof-image-container">
                                        <img class="img-thumbnail" style="width: 200px;"
                                            src="<?php echo e(url('admin/images/proofs/' . $vendorDetails['vendor_business']['address_proof_image'])); ?>"
                                            alt="Address Proof"
                                            onerror="this.src='<?php echo e(asset('admin/images/photos/no-image.gif')); ?>'">
                                    </div>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 grid-margin stretch-card mt-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-bank mr-2"></i>Bank Information
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if(empty($vendorDetails['vendor_bank'])): ?>
                            <div class="alert alert-info">
                                <i class="mdi mdi-information mr-2"></i>
                                Bank information not provided yet.
                            </div>
                        <?php else: ?>
                            <div class="form-group">
                                <label class="font-weight-medium text-muted">
                                    <i class="mdi mdi-account-outline mr-1"></i>Account Holder Name
                                </label>
                                <input type="text" class="form-control form-control-sm bg-light"
                                    value="<?php echo e($vendorDetails['vendor_bank']['account_holder_name'] ?? 'N/A'); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-medium text-muted">
                                    <i class="mdi mdi-bank mr-1"></i>Bank Name
                                </label>
                                <input type="text" class="form-control form-control-sm bg-light"
                                    value="<?php echo e($vendorDetails['vendor_bank']['bank_name'] ?? 'N/A'); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-medium text-muted">
                                    <i class="mdi mdi-credit-card mr-1"></i>Account Number
                                </label>
                                <input type="text" class="form-control form-control-sm bg-light"
                                    value="<?php echo e($vendorDetails['vendor_bank']['account_number'] ?? 'N/A'); ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label class="font-weight-medium text-muted">
                                    <i class="mdi mdi-barcode mr-1"></i>Bank IFSC Code
                                </label>
                                <input type="text" class="form-control form-control-sm bg-light"
                                    value="<?php echo e($vendorDetails['vendor_bank']['bank_ifsc_code'] ?? 'N/A'); ?>" readonly>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            
            <div class="col-md-6 grid-margin stretch-card mt-5">
                <div class="card border-0 shadow-sm">
                    <div class="card-header text-white">
                        <h4 class="card-title mb-0">
                            <i class="mdi mdi-percent mr-2"></i>Commission Information
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <?php if(Session::has('error_message')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle mr-2"></i>
                                <strong>Error:</strong> <?php echo e(Session::get('error_message')); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php if($errors->any()): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle mr-2"></i>
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($error); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        
                        <?php if(Session::has('success_message')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-circle mr-2"></i>
                                <strong>Success:</strong> <?php echo e(Session::get('success_message')); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        <?php endif; ?>

                        <form method="post" action="<?php echo e(url('admin/update-vendor-commission')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="vendor_id" value="<?php echo e($vendorDetails['vendor_personal']['id'] ?? ''); ?>">

                            <div class="form-group">
                                <label class="font-weight-medium text-muted">
                                    <i class="mdi mdi-percent mr-1"></i>Commission per order item (%)
                                </label>
                                <div class="input-group">
                                    <input class="form-control" type="number" name="commission" step="0.01"
                                        min="0" max="100"
                                        value="<?php echo e($vendorDetails['vendor_personal']['commission'] ?? '0'); ?>"
                                        required>
                                    <div class="input-group-append">
                                        <span class="input-group-text bg-light">%</span>
                                    </div>
                                </div>
                                <small class="text-muted">Enter commission percentage (0-100)</small>
                            </div>

                            <button type="submit" class="btn btn-warning">
                                <i class="mdi mdi-content-save mr-1"></i>Update Commission
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <style>
        .page-header {
            background: #f8f9fa;
            padding: 1.5rem;
            margin: -1.5rem -1.5rem 0 -1.5rem;
            border-bottom: 1px solid #e9ecef;
        }

        .card {
            transition: all 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .form-control {
            transition: all 0.2s ease-in-out;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .font-weight-medium {
            font-weight: 500;
        }

        .img-thumbnail {
            transition: transform 0.2s ease;
        }

        .img-thumbnail:hover {
            transform: scale(1.02);
        }

        .card-title {
            color: #000;
        }

        .card-header {
            background: #CBCBCB;
        }

        .alert-info {
            background-color: #e3f2fd;
            border-color: #bbdefb;
            color: #1976d2;
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/admins/view_vendor_details.blade.php ENDPATH**/ ?>