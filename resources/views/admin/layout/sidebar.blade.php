<div class="sidebar" id="sidebar">
    <!-- Menu Section: Main Menu -->
    <div class="menu-section">
        <div class="menu-title">Menu</div>

        <!-- Dashboard -->
        <a href="{{ url('admin/dashboard') }}" class="menu-item {{ Session::get('page') == 'dashboard' ? 'active' : '' }}">
            <div class="menu-item-content">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </div>
        </a>

        <!-- Catalogue Management -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Catalogue</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu {{ in_array(Session::get('page'), ['sections','categories','products','brands','filters','coupons']) ? 'show' : '' }}">
                <a href="{{ url('admin/sections') }}" class="submenu-item {{ Session::get('page') == 'sections' ? 'active' : '' }}">Sections</a>
                <a href="{{ url('admin/categories') }}" class="submenu-item {{ Session::get('page') == 'categories' ? 'active' : '' }}">Categories</a>
                <a href="{{ url('admin/products') }}" class="submenu-item {{ Session::get('page') == 'products' ? 'active' : '' }}">Products</a>
                <a href="{{ url('admin/brands') }}" class="submenu-item {{ Session::get('page') == 'brands' ? 'active' : '' }}">Brands</a>
                <a href="{{ url('admin/filters') }}" class="submenu-item {{ Session::get('page') == 'filters' ? 'active' : '' }}">Filters</a>
                <a href="{{ url('admin/coupons') }}" class="submenu-item {{ Session::get('page') == 'coupons' ? 'active' : '' }}">Coupons</a>
            </div>
        @endif

        <!-- Vendor Details -->
        @if (Auth::guard('admin')->user()->type == 'vendor')
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-store"></i>
                    <span>Vendor Details</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu {{ in_array(Session::get('page'), ['update_personal_details','update_business_details','update_bank_details']) ? 'show' : '' }}">
                <a href="{{ url('admin/update-vendor-details/personal') }}" class="submenu-item {{ Session::get('page') == 'update_personal_details' ? 'active' : '' }}">Personal Details</a>
                <a href="{{ url('admin/update-vendor-details/business') }}" class="submenu-item {{ Session::get('page') == 'update_business_details' ? 'active' : '' }}">Business Details</a>
                <a href="{{ url('admin/update-vendor-details/bank') }}" class="submenu-item {{ Session::get('page') == 'update_bank_details' ? 'active' : '' }}">Bank Details</a>
            </div>
        @endif

        <!-- Orders -->
        <div class="menu-item" onclick="toggleSubmenu(this)">
            <div class="menu-item-content">
                <i class="fas fa-truck"></i>
                <span>Orders</span>
            </div>
            <i class="fas fa-chevron-right dropdown-arrow"></i>
        </div>
        <div class="submenu {{ Session::get('page') == 'orders' ? 'show' : '' }}">
            <a href="{{ url('admin/orders') }}" class="submenu-item {{ Session::get('page') == 'orders' ? 'active' : '' }}">All Orders</a>
        </div>

        <!-- Users -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu {{ in_array(Session::get('page'), ['users','subscribers']) ? 'show' : '' }}">
                <a href="{{ url('admin/users') }}" class="submenu-item {{ Session::get('page') == 'users' ? 'active' : '' }}">Users</a>
                <a href="{{ url('admin/subscribers') }}" class="submenu-item {{ Session::get('page') == 'subscribers' ? 'active' : '' }}">Subscribers</a>
            </div>
        @endif

        <!-- Admin Management -->
        @if (Auth::guard('admin')->user()->type == 'superadmin')
            <div class="menu-item" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-user-shield"></i>
                    <span>Admins</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow"></i>
            </div>
            <div class="submenu {{ in_array(Session::get('page'), ['view_admins','view_subadmins','view_vendors','view_all']) ? 'show' : '' }}">
                <a href="{{ url('admin/admins/admin') }}" class="submenu-item {{ Session::get('page') == 'view_admins' ? 'active' : '' }}">Admins</a>
                <a href="{{ url('admin/admins/subadmin') }}" class="submenu-item {{ Session::get('page') == 'view_subadmins' ? 'active' : '' }}">Sub Admins</a>
                <a href="{{ url('admin/admins/vendor') }}" class="submenu-item {{ Session::get('page') == 'view_vendors' ? 'active' : '' }}">Vendors</a>
                <a href="{{ url('admin/admins') }}" class="submenu-item {{ Session::get('page') == 'view_all' ? 'active' : '' }}">All Admins</a>
            </div>
        @endif

        <!-- Banners -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <a href="{{ url('admin/banners') }}" class="menu-item {{ Session::get('page') == 'banners' ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fas fa-image"></i>
                    <span>Banners</span>
                </div>
            </a>
        @endif

        <!-- Shipping -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <a href="{{ url('admin/shipping-charges') }}" class="menu-item {{ Session::get('page') == 'shipping' ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Shipping</span>
                </div>
            </a>
        @endif

        <!-- Ratings -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <a href="{{ url('admin/ratings') }}" class="menu-item {{ Session::get('page') == 'ratings' ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fas fa-star"></i>
                    <span>Ratings</span>
                </div>
            </a>
        @endif

        <!-- Settings -->
        <div class="menu-item" onclick="toggleSubmenu(this)">
            <div class="menu-item-content">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </div>
            <i class="fas fa-chevron-right dropdown-arrow"></i>
        </div>
        <div class="submenu {{ in_array(Session::get('page'), ['update_admin_password','update_admin_details']) ? 'show' : '' }}">
            <a href="{{ url('admin/update-admin-password') }}" class="submenu-item {{ Session::get('page') == 'update_admin_password' ? 'active' : '' }}">Change Password</a>
            <a href="{{ url('admin/update-admin-details') }}" class="submenu-item {{ Session::get('page') == 'update_admin_details' ? 'active' : '' }}">Update Details</a>
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
</script>