<div class="header">
    <div style="display: flex; align-items: center;">
        <button class="menu-toggle" onclick="toggleSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        <div class="logo">
            <img src="{{ asset('assets/images/logo/logo.jpg') }}" width="180px" alt="">
        </div>
    </div>

    <div class="search-bar">
        <input type="text" class="search-input" placeholder="Search...">
        <i class="fas fa-search search-icon"></i>
    </div>

    <div class="header-actions">
        <a class="header-btn text-decoration-none" href="{{ url('/') }}">
            <i class="fas fa-globe"></i>
        </a>

        <a class="header-btn text-decoration-none " onclick="toggleDarkMode()">
            <i class="fas fa-moon"></i>
        </a>



        <img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=40&h=40&fit=crop&crop=face"
            alt="Albert Flores" class="user-avatar">
    </div>
</div>
