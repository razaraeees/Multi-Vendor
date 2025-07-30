
<div class="product-grid">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-3 g-3 g-sm-4">
        <?php $__currentLoopData = $categoryProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        <div class="add-cart position-absolute top-0 end-0 mt-2 me-2 z-10">
                            <a href="javascript:;"><i class='bx bx-cart-add fs-4 text-dark'></i></a>
                        </div>
                        <div class="quick-view position-absolute start-0 bottom-0 w-100 text-center bg-dark bg-opacity-50 py-2">
                            <a href="javascript:;" class="text-white text-decoration-none" data-bs-toggle="modal" data-bs-target="#QuickViewProduct">Quick View</a>
                        </div>
                        <a href="<?php echo e(url('product/' . $product->id)); ?>">
                            <img src="<?php echo e(asset('front/images/product_images/small/' . ($product->product_image ?? 'no-image.png'))); ?>"
                                alt="<?php echo e($product->product_name); ?>"
                                class="img-fluid h-100 w-100 object-fit-cover">
                        </a>
                    </div>
                    <div class="card-body px-2">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <p class="mb-1 text-muted small">
                                    <?php echo e($product->brand->name ?? 'Brand'); ?></p>
                                <h6 class="mb-0 fw-bold"><?php echo e($product->product_name); ?></h6>
                            </div>
                            <div class="icon-wishlist">
                                <a href="javascript:;"><i class="bx bx-heart"></i></a>
                            </div>
                        </div>

                        
                        <?php
                            $averageRating = round($product->ratings->avg('rating'));
                        ?>
                        <div class="rating mt-2 text-warning small">
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= $averageRating): ?>
                                    <i class="bx bxs-star"></i>
                                <?php else: ?>
                                    <i class="bx bx-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>

                        
                        <div class="product-price d-flex gap-2 mt-2">
                            <?php if($product->product_discount > 0): ?>
                                <span class="text-secondary text-decoration-line-through">$<?php echo e($product->product_price); ?></span>
                                <span class="fw-bold text-success">
                                    $<?php echo e(number_format($product->product_price - ($product->product_price * $product->product_discount / 100), 2)); ?>

                                </span>
                            <?php else: ?>
                                <span class="fw-bold">$<?php echo e($product->product_price); ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/front/products/ajax_products_listing.blade.php ENDPATH**/ ?>