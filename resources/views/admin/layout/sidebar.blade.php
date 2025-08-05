<style>
    .submenu-item.active {
    background-color: #e3f2fd !important;
    color: #1976d2 !important;
    font-weight: 500;
}

.menu-item.active {
    background-color: #f5f5f5;
    color: #1976d2;
}

.dropdown-arrow.rotate {
    transform: rotate(90deg);
    transition: transform 0.3s ease;
}
</style>

<div class="sidebar" id="sidebar">
    <!-- Menu Section: Main Menu -->
    <div class="menu-section">
        <div class="menu-title">Menu</div>

        <!-- Dashboard -->
        <a href="{{ url('admin/dashboard') }}" 
            class="menu-item text-decoration-none {{ Session::get('page') == 'dashboard' ? 'active' : '' }}">
            <div class="menu-item-content">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </div>
        </a>

        @if (Auth::guard('admin')->user()->type == 'vendor')
            <div class="menu-item {{ Request::is('admin/sections*') || Request::is('admin/categories*') || Request::is('admin/products*') || Request::is('admin/brands*') || Request::is('admin/filters*') || Request::is('admin/coupons*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Catalogue</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow {{ Request::is('admin/sections*') || Request::is('admin/categories*') || Request::is('admin/products*') || Request::is('admin/brands*') || Request::is('admin/filters*') || Request::is('admin/coupons*') ? 'rotate' : '' }}"></i>
            </div>
            <div class="submenu {{ Request::is('admin/sections*') || Request::is('admin/categories*') || Request::is('admin/products*') || Request::is('admin/brands*') || Request::is('admin/filters*') || Request::is('admin/coupons*') ? 'show' : '' }}">
                <a href="{{ url('admin/products') }}" 
                    class="submenu-item text-decoration-none {{ Request::is('admin/products*') ? 'active' : '' }}">
                    Products
                </a>
            </div>
        @endif
        <!-- Catalogue Management -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <div class="menu-item {{ Request::is('admin/sections*') || Request::is('admin/categories*') || Request::is('admin/products*') || Request::is('admin/brands*') || Request::is('admin/filters*') || Request::is('admin/coupons*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-shopping-bag"></i>
                    <span>Catalogue</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow {{ Request::is('admin/sections*') || Request::is('admin/categories*') || Request::is('admin/products*') || Request::is('admin/brands*') || Request::is('admin/filters*') || Request::is('admin/coupons*') ? 'rotate' : '' }}"></i>
            </div>
            <div class="submenu {{ Request::is('admin/sections*') || Request::is('admin/categories*') || Request::is('admin/products*') || Request::is('admin/brands*') || Request::is('admin/filters*') || Request::is('admin/coupons*') ? 'show' : '' }}">
                <a href="{{ url('admin/products') }}" 
                    class="submenu-item text-decoration-none {{ Request::is('admin/products*') ? 'active' : '' }}">
                    Products
                </a>
                <a href="{{ url('admin/categories') }}" 
                class="submenu-item text-decoration-none {{ Request::is('admin/categories*') ? 'active' : '' }}">
                Categories
            </a>
                
            <a href="{{ url('admin/brands') }}" 
            class="submenu-item text-decoration-none {{ Request::is('admin/brands*') ? 'active' : '' }}">
            Brands
                </a>
                <a href="{{ url('admin/sections') }}" 
                    class="submenu-item text-decoration-none {{ Request::is('admin/sections*') ? 'active' : '' }}">
                    Attributes
                </a>
            </div>
            @endif

        <!-- Vendor Details -->
        @if (Auth::guard('admin')->user()->type == 'vendor')
            <div class="menu-item {{ Request::is('admin/update-vendor-details*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-store"></i>
                    <span>Vendor Details</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow {{ Request::is('admin/update-vendor-details*') ? 'rotate' : '' }}"></i>
            </div>
            <div class="submenu {{ Request::is('admin/update-vendor-details*') ? 'show' : '' }}">
                <a href="{{ url('admin/update-vendor-details/personal') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/update-vendor-details/personal*') ? 'active' : '' }}">
                   Personal Details
                </a>
                <a href="{{ url('admin/update-vendor-details/business') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/update-vendor-details/business*') ? 'active' : '' }}">
                   Business Details
                </a>
                <a href="{{ url('admin/update-vendor-details/bank') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/update-vendor-details/bank*') ? 'active' : '' }}">
                   Bank Details
                </a>
            </div>
        @endif

        <!-- Orders -->
        <div class="menu-item {{ Request::is('admin/orders*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
            <div class="menu-item-content">
                <i class="fas fa-truck"></i>
                <span>Orders</span>
            </div>
            <i class="fas fa-chevron-right dropdown-arrow {{ Request::is('admin/orders*') ? 'rotate' : '' }}"></i>
        </div>
        <div class="submenu {{ Request::is('admin/orders*') ? 'show' : '' }}">
            <a href="{{ url('admin/orders') }}" 
               class="submenu-item text-decoration-none {{ Request::is('admin/orders*') ? 'active' : '' }}">
               All Orders
            </a>
        </div>

        <!-- Users -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <div class="menu-item {{ Request::is('admin/users*') || Request::is('admin/subscribers*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow {{ Request::is('admin/users*') || Request::is('admin/subscribers*') ? 'rotate' : '' }}"></i>
            </div>
            <div class="submenu {{ Request::is('admin/users*') || Request::is('admin/subscribers*') ? 'show' : '' }}">
                <a href="{{ url('admin/users') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/users*') ? 'active' : '' }}">
                   Users
                </a>
                <a href="{{ url('admin/subscribers') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/subscribers*') ? 'active' : '' }}">
                   Subscribers
                </a>
            </div>
        @endif

        <!-- Admin Management -->
        @if (Auth::guard('admin')->user()->type == 'superadmin')
            <div class="menu-item {{ Request::is('admin/admins*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
                <div class="menu-item-content">
                    <i class="fas fa-user-shield"></i>
                    <span>Admins</span>
                </div>
                <i class="fas fa-chevron-right dropdown-arrow {{ Request::is('admin/admins*') ? 'rotate' : '' }}"></i>
            </div>
            <div class="submenu {{ Request::is('admin/admins*') ? 'show' : '' }}">
                <a href="{{ url('admin/admins/admin') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/admins/admin*') ? 'active' : '' }}">
                   Admins
                </a>
                <a href="{{ url('admin/admins/subadmin') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/admins/subadmin*') ? 'active' : '' }}">
                   Sub Admins
                </a>
                <a href="{{ url('admin/admins/vendor') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/admins/vendor*') ? 'active' : '' }}">
                   Vendors
                </a>
                <a href="{{ url('admin/admins') }}" 
                   class="submenu-item text-decoration-none {{ Request::is('admin/admins') && !Request::is('admin/admins/*') ? 'active' : '' }}">
                   All Admins
                </a>
            </div>
        @endif

        <!-- Banners -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <a href="{{ url('admin/banners') }}" 
               class="menu-item text-decoration-none {{ Request::is('admin/banners*') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fas fa-image"></i>
                    <span>Banners</span>
                </div>
            </a>
        @endif

            <a href="{{ url('admin/coupons') }}" 
               class="menu-item text-decoration-none {{ Request::is('admin/coupons*') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fa-solid fa-tags"></i>
                    <span>Coupons</span>
                </div>
            </a>

        <!-- Shipping -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <a href="{{ url('admin/shipping-charges') }}" 
               class="menu-item text-decoration-none {{ Request::is('admin/shipping-charges*') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Shipping</span>
                </div>
            </a>
        @endif

        <!-- Ratings -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <a href="{{ url('admin/ratings') }}" 
               class="menu-item text-decoration-none {{ Request::is('admin/ratings*') ? 'active' : '' }}">
                <div class="menu-item-content">
                    <i class="fas fa-star"></i>
                    <span>Ratings</span>
                </div>
            </a>
        @endif

        <!-- Settings -->
        <div class="menu-item {{ Request::is('admin/update-admin-password*') || Request::is('admin/update-admin-details*') || Request::is('admin/logout*') ? 'active' : '' }}" onclick="toggleSubmenu(this)">
            <div class="menu-item-content">
                <i class="fas fa-cog"></i>
                <span>Settings</span>
            </div>
            <i class="fas fa-chevron-right dropdown-arrow {{ Request::is('admin/update-admin-password*') || Request::is('admin/update-admin-details*') || Request::is('admin/logout*') ? 'rotate' : '' }}"></i>
        </div>
        <div class="submenu {{ Request::is('admin/update-admin-password*') || Request::is('admin/update-admin-details*') || Request::is('admin/logout*') ? 'show' : '' }}">
            <a href="{{ url('admin/update-admin-password') }}" 
               class="submenu-item text-decoration-none {{ Request::is('admin/update-admin-password*') ? 'active' : '' }}">
               Change Password
            </a>
            <a href="{{ url('admin/update-admin-details') }}" 
               class="submenu-item text-decoration-none {{ Request::is('admin/update-admin-details*') ? 'active' : '' }}">
               Update Details
            </a>
            <a href="{{ url('admin/logout') }}" 
               class="submenu-item text-decoration-none">
               Logout
            </a>
        </div>
    </div>
</div>

<script>
function toggleSubmenu(element) {
    const submenu = element.nextElementSibling;
    const arrow = element.querySelector('.dropdown-arrow');

    // Agar submenu ke andar active link hai to woh hamesha open rahe
    const hasActiveChild = submenu.querySelector('.submenu-item.active');

    if (submenu.classList.contains('show') && !hasActiveChild) {
        submenu.classList.remove('show');
        arrow.classList.remove('rotate');
    } else {
        // Pehle sab submenus close karen jinke andar active child nahi hai
        document.querySelectorAll('.submenu').forEach(s => {
            const hasActive = s.querySelector('.submenu-item.active');
            if (!hasActive && s !== submenu) { // current submenu ko close na karo
                s.classList.remove('show');
                const parentArrow = s.previousElementSibling.querySelector('.dropdown-arrow');
                if (parentArrow) parentArrow.classList.remove('rotate');
            }
        });

        submenu.classList.add('show');
        arrow.classList.add('rotate');
    }
}

// Auto-expand submenu agar usme active child ho
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.submenu-item.active').forEach(activeItem => {
        const parentSubmenu = activeItem.closest('.submenu');
        const parentMenuItem = parentSubmenu.previousElementSibling;
        const arrow = parentMenuItem.querySelector('.dropdown-arrow');

        parentSubmenu.classList.add('show');
        if (arrow) arrow.classList.add('rotate');
    });
});

</script>