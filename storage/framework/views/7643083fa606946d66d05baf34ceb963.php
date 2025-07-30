<div class="sidebar" id="sidebar">
    <!-- Menu Section: Main Menu -->
    <div class="menu-section">
        <div class="menu-title">Menu</div>

        <!-- Dashboard -->
        <a href="<?php echo e(url('admin/dashboard')); ?>" class="menu-item <?php echo e(Session::get('page') == 'dashboard' ? 'active' : ''); ?>">
            <div class="menu-item-content">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <!-- Catalogue Management -->
        <?php if(Auth::guard('admin')->user()->type != 'vendor'): ?>
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Catalogue</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu <?php echo e(in_array(Session::get('page'), ['sections','categories','products','brands','filters','coupons']) ? 'show' : ''); ?>">
                <a href="<?php echo e(url('admin/sections')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'sections' ? 'active' : ''); ?>">Sections</a>
                <a href="<?php echo e(url('admin/categories')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'categories' ? 'active' : ''); ?>">Categories</a>
                <a href="<?php echo e(url('admin/products')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'products' ? 'active' : ''); ?>">Products</a>
                <a href="<?php echo e(url('admin/brands')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'brands' ? 'active' : ''); ?>">Brands</a>
                <a href="<?php echo e(url('admin/filters')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'filters' ? 'active' : ''); ?>">Filters</a>
                <a href="<?php echo e(url('admin/coupons')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'coupons' ? 'active' : ''); ?>">Coupons</a>
            </div>
        <?php endif; ?>

        <!-- Vendor Details -->
        <?php if(Auth::guard('admin')->user()->type == 'vendor'): ?>
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-store"></i>
                    <span>Vendor Details</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu <?php echo e(in_array(Session::get('page'), ['update_personal_details','update_business_details','update_bank_details']) ? 'show' : ''); ?>">
                <a href="<?php echo e(url('admin/update-vendor-details/personal')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'update_personal_details' ? 'active' : ''); ?>">Personal Details</a>
                <a href="<?php echo e(url('admin/update-vendor-details/business')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'update_business_details' ? 'active' : ''); ?>">Business Details</a>
                <a href="<?php echo e(url('admin/update-vendor-details/bank')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'update_bank_details' ? 'active' : ''); ?>">Bank Details</a>
            </div>
        <?php endif; ?>

        <!-- Orders -->
        <div class="menu-item" onclick="toggleSubmenu(this)">
            <div class="menu-item-content">
                <i class="fas fa-truck"></i>
                <span>Orders</span>
            </div>
            <i class="fas fa-chevron-right dropdown-arrow"></i>
        </div>
        <div class="submenu <?php echo e(Session::get('page') == 'orders' ? 'show' : ''); ?>">
            <a href="<?php echo e(url('admin/orders')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'orders' ? 'active' : ''); ?>">All Orders</a>
        </div>

        <!-- Users -->
        <?php if(Auth::guard('admin')->user()->type != 'vendor'): ?>
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu <?php echo e(in_array(Session::get('page'), ['users','subscribers']) ? 'show' : ''); ?>">
                <a href="<?php echo e(url('admin/users')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'users' ? 'active' : ''); ?>">Users</a>
                <a href="<?php echo e(url('admin/subscribers')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'subscribers' ? 'active' : ''); ?>">Subscribers</a>
            </div>
        <?php endif; ?>

        <!-- Admin Management -->
        <?php if(Auth::guard('admin')->user()->type == 'superadmin'): ?>
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-user-shield"></i>
                    <span>Admins</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu <?php echo e(in_array(Session::get('page'), ['view_admins','view_subadmins','view_vendors','view_all']) ? 'show' : ''); ?>">
                <a href="<?php echo e(url('admin/admins/admin')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'view_admins' ? 'active' : ''); ?>">Admins</a>
                <a href="<?php echo e(url('admin/admins/subadmin')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'view_subadmins' ? 'active' : ''); ?>">Sub Admins</a>
                <a href="<?php echo e(url('admin/admins/vendor')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'view_vendors' ? 'active' : ''); ?>">Vendors</a>
                <a href="<?php echo e(url('admin/admins')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'view_all' ? 'active' : ''); ?>">All Admins</a>
            </div>
        <?php endif; ?>

        <!-- Banners -->
        <?php if(Auth::guard('admin')->user()->type != 'vendor'): ?>
            <a href="<?php echo e(url('admin/banners')); ?>" class="menu-item <?php echo e(Session::get('page') == 'banners' ? 'active' : ''); ?>">
                <div class="menu-item-content">
                    <i class="fas fa-image"></i>
                    <span>Banners</span>
                </div>
            </a>
        <?php endif; ?>

        <!-- Shipping -->
        <?php if(Auth::guard('admin')->user()->type != 'vendor'): ?>
            <a href="<?php echo e(url('admin/shipping-charges')); ?>" class="menu-item <?php echo e(Session::get('page') == 'shipping' ? 'active' : ''); ?>">
                <div class="menu-item-content">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Shipping</span>
                </div>
            </a>
        <?php endif; ?>

        <!-- Ratings -->
        <?php if(Auth::guard('admin')->user()->type != 'vendor'): ?>
            <a href="<?php echo e(url('admin/ratings')); ?>" class="menu-item <?php echo e(Session::get('page') == 'ratings' ? 'active' : ''); ?>">
                <div class="menu-item-content">
                    <i class="fas fa-star"></i>
                    <span>Ratings</span>
                </div>
            </a>
        <?php endif; ?>

        <!-- Settings -->
        <div class="menu-item" onclick="toggleSubmenu(this)">
            <div class="menu-item-content">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </div>
            <i class="fas fa-chevron-right dropdown-arrow"></i>
        </div>
        <div class="submenu <?php echo e(in_array(Session::get('page'), ['update_admin_password','update_admin_details']) ? 'show' : ''); ?>">
            <a href="<?php echo e(url('admin/update-admin-password')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'update_admin_password' ? 'active' : ''); ?>">Change Password</a>
            <a href="<?php echo e(url('admin/update-admin-details')); ?>" class="submenu-item <?php echo e(Session::get('page') == 'update_admin_details' ? 'active' : ''); ?>">Update Details</a>
        </div>
    </div>

</div>

<script>
function toggleSubmenu(element) {
    const submenu = element.nextElementSibling;
    const arrow = element.querySelector('.dropdown-arrow');

    if (submenu.classList.contains('show')) {
        submenu.classList.remove('show');
        arrow.classList.remove('rotate');
    } else {
        // Optional: Close other submenus
        document.querySelectorAll('.submenu').forEach(s => s.classList.remove('show'));
        document.querySelectorAll('.dropdown-arrow').forEach(a => a.classList.remove('rotate'));
        
        submenu.classList.add('show');
        arrow.classList.add('rotate');
    }
}
</script><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/layout/sidebar.blade.php ENDPATH**/ ?>