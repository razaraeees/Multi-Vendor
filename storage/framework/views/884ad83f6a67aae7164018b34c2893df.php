 


<?php
    
    $productFilters = \App\Models\ProductsFilter::productFilters(); // Get ALL the (enabled/active) Filters
    // dd($productFilters);

    // Note: $category_id may come from TWO places: the AJAX call and gets passed in through categoryFilters() method in Admin/FilterController.php    OR    the $product object in case of 'Edit Product' from addEditProduct() method in Admin/ProductsController    

    // In case of 'Edit a Product' only (NOT 'Add a new Product' and NOT from the $category_id which comes from the AJAX call), where $product is passed from addEditProduct() method in Admin/ProductsController    
    if (isset($product['category_id'])) {
        $category_id = $product['category_id'];
    }
?>


<?php $__currentLoopData = $productFilters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $filter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
    <?php
        // echo '<pre>', var_dump($product), '</pre>';
        // exit;
        // echo '<pre>', var_dump($filter), '</pre>';
        // exit;
        // dd($filter);
    ?>

    <?php if(isset($category_id)): ?> 
        <?php
            // dd($filter);

            // Firstly, for every filter in the `products_filters` table, Get the filter's (from the foreach loop) `cat_ids` using filterAvailable() method, then check if the current category id (using the $category_id variable and depending on the URL) exists in the filter's `cat_ids`. If it exists, then show the filter, if not, then don't show the filter
            $filterAvailable = \App\Models\ProductsFilter::filterAvailable($filter['id'], $category_id); // $category_id comes from the AJAX call (check categoryFilters() method in Admin/FilterController.php
        ?>

        <?php if($filterAvailable == 'Yes'): ?> 
            <div class="form-group">
                <label for="<?php echo e($filter['filter_column']); ?>">Select <?php echo e($filter['filter_name']); ?></label> 
                <select name="<?php echo e($filter['filter_column']); ?>" id="<?php echo e($filter['filter_column']); ?>" class="form-control text-dark"> 
                    <option value="">Select Filter Value</option>
                    <?php $__currentLoopData = $filter['filter_values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                        <?php
                            // echo '<pre>', var_dump($value), '</pre>'; exit;
                        ?>
                        <option value="<?php echo e($value['filter_value']); ?>" <?php if(!empty($product[$filter['filter_column']]) && $product[$filter['filter_column']] == $value['filter_value']): ?> selected <?php endif; ?>><?php echo e(ucwords($value['filter_value'])); ?></option>  
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        <?php endif; ?>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/filters/category_filters.blade.php ENDPATH**/ ?>