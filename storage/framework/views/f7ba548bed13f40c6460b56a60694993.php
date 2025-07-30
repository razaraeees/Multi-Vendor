

<div class="col-12 col-xl-3 filter-column">
    <nav class="navbar navbar-expand-xl flex-wrap p-0">
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbarFilter"
            aria-labelledby="offcanvasNavbarFilterLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title mb-0 fw-bold" id="offcanvasNavbarFilterLabel">Filters</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="filter-sidebar">
                    <form id="filterForm" method="GET" action="<?php echo e(url()->current()); ?>">
                        <div class="card rounded-0 shadow-none border">
                            <div class="card-header d-none d-xl-block bg-transparent">
                                <h5 class="mb-0 fw-bold">Filters</h5>
                            </div>
                            <div class="card-body">

                                
                                <?php if(isset($categories) && $categories->count() > 0): ?>
                                    <h6 class="p-1 fw-bold bg-light">Categories</h6>
                                    <div class="categories-wrapper height-1 p-1">
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-check">
                                                <input class="form-check-input" name="category[]" type="checkbox"
                                                    value="<?php echo e($category->id); ?>"
                                                    <?php echo e(in_array($category->id, request()->get('category', [])) ? 'checked' : ''); ?>>
                                                <label class="form-check-label">
                                                    <span><?php echo e($category->category_name); ?></span>
                                                    <span
                                                        class="product-number">(<?php echo e($category->products->count()); ?>)</span>
                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <hr>
                                <?php endif; ?>

                                
                                <?php if(isset($brands) && $brands->count() > 0): ?>
                                    <h6 class="p-1 fw-bold bg-light">Brands</h6>
                                    <div class="brands-wrapper height-1 p-1">
                                        <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="form-check">
                                                <input class="form-check-input" name="brand[]" type="checkbox"
                                                    value="<?php echo e($brand->id); ?>"
                                                    <?php echo e(in_array($brand->id, request()->get('brand', [])) ? 'checked' : ''); ?>>
                                                <label class="form-check-label">
                                                    <span><?php echo e($brand->name); ?></span>
                                                    <span
                                                        class="product-number">(<?php echo e($brand->products->count()); ?>)</span>
                                                </label>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <hr>
                                <?php endif; ?>

                                
                                <h6 class="p-1 fw-bold bg-light">Price</h6>
                                <div class="Price-wrapper p-1">
                                    <div class="input-group">
                                        <input type="number" class="form-control rounded-0" name="min_price"
                                            placeholder="Min" min="0" value="<?php echo e(request('min_price')); ?>">
                                        <span class="input-group-text bg-section-1 border-0">-</span>
                                        <input type="number" class="form-control rounded-0" name="max_price"
                                            placeholder="Max" min="0" value="<?php echo e(request('max_price')); ?>">
                                        <button type="submit" class="btn btn-outline-dark rounded-0 ms-2">
                                            <i class='bx bx-chevron-right me-0'></i>
                                        </button>
                                    </div>
                                </div>
                                <hr>

                                
                                <?php if(isset($colors) && $colors->count() > 0): ?>
                                    <h6 class="p-1 fw-bold bg-light">Colors</h6>
                                    <div class="color-wrapper height-1 p-1">
                                        <?php $__currentLoopData = $colors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($color): ?>
                                                
                                                <div class="form-check">
                                                    <input class="form-check-input" name="color[]" type="checkbox"
                                                        value="<?php echo e($color); ?>"
                                                        <?php echo e(in_array($color, request()->get('color', [])) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label">
                                                        <i class="bi bi-circle-fill me-1"
                                                            style="color: <?php echo e($color); ?>"></i>
                                                        <span><?php echo e(ucfirst($color)); ?></span>
                                                    </label>
                                                </div>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <hr>
                                <?php endif; ?>

                                
                                <h6 class="p-1 fw-bold bg-light">Discount Range</h6>
                                <div class="discount-wrapper p-1">
                                    <?php $__currentLoopData = [10, 20, 30, 40, 50, 60, 70, 80]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $discount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="discount"
                                                value="<?php echo e($discount); ?>"
                                                <?php echo e(request('discount') == $discount ? 'checked' : ''); ?>>
                                            <label class="form-check-label">
                                                <?php echo e($discount); ?>% and Above
                                            </label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                
                                <div class="mt-3">
                                    <a href="<?php echo e(url()->current()); ?>" class="btn btn-outline-secondary w-100">Clear All
                                        Filters</a>
                                </div>

                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-dark w-100">Apply Filters</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </nav>
</div>

<script>
    $(document).ready(function() {
        // Auto-submit on filter change
        $('#filterForm input[type="checkbox"], #filterForm input[type="radio"]').on('change', function() {
            applyFilters();
        });

        // Price filter submit
        $('#filterForm').on('submit', function(e) {
            e.preventDefault();
            applyFilters();
        });

        function applyFilters() {
            let form = $('#filterForm');
            let formData = form.serialize();

            // Current page URL add karo
            let currentUrl = window.location.pathname;
            formData += '&current_url=' + encodeURIComponent(currentUrl);
            formData += '&ajax=1';

            console.log('Filter data:', formData);

            $.ajax({
                url: form.attr('action'),
                method: 'GET',
                data: formData,
                beforeSend: function() {
                    $('#product-listing').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading products...</p>
                    </div>
                `);
                },
                success: function(response) {
                    console.log('Success:', response.length, 'characters received');
                    $('#product-listing').html(response);

                    // URL update karo without page reload
                    let newUrl = form.attr('action') + '?' + form.serialize();
                    window.history.pushState({}, '', newUrl);
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error
                    });

                    $('#product-listing').html(`
                    <div class="alert alert-danger text-center">
                        <h5>Oops! Something went wrong</h5>
                        <p>Error: ${xhr.status} - ${xhr.statusText}</p>
                        <button class="btn btn-primary" onclick="location.reload()">Reload Page</button>
                    </div>
                `);
                }
            });
        }

        // Clear filters function
        window.clearFilters = function() {
            $('#filterForm')[0].reset();
            window.location.href = window.location.pathname;
        };
    });
</script>
<?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/front/products/filters.blade.php ENDPATH**/ ?>