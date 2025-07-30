<div class="header">
    <div style="display: flex; align-items: center;">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <div class="logo-icon">
                <i class="fas fa-code"></i>
            </div>
            DashCode
        </div>
    </div>

    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Search...">
        <i class="fas fa-search search-icon"></i>
    </div>

    <div class="header-actions">
        <div class="laravel-badge">
            <div class="laravel-status"></div>
            Laravel
        </div>

        <a class="header-btn text-decoration-none" href="<?php echo e(url('/')); ?>">
            <i class="fas fa-globe"></i>
        </a>

        <a class="header-btn text-decoration-none " onclick="toggleDarkMode()">
            <i class="fas fa-moon"></i>
        </a>



        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
            alt="Albert Flores" class="user-avatar">
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/layout/header.blade.php ENDPATH**/ ?>