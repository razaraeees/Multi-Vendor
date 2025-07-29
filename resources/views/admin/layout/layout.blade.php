<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DashCode - Laravel Dashboard</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/new_layout/css/style.css') }}">

    <!-- Optional: DataTables, Toastr, etc. -->
    @stack('styles')
    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>Admin Panel</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="{{ url('admin/vendors/feather/feather.css') }}">
    <link rel="stylesheet" href="{{ url('admin/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ url('admin/vendors/css/vendor.bundle.base.css') }}">
    <!-- endinject -->


    <!-- Plugin css for this page (The icons from Skydash Admin Panel template) -->
    <link rel="stylesheet" href="{{ url('admin/vendors/mdi/css/materialdesignicons.min.css') }}">


    <!-- Plugin css for this page -->
    <link rel="stylesheet" href="{{ url('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ url('admin/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('admin/js/select.dataTables.min.css') }}">
    <!-- End plugin css for this page -->

    <link rel="shortcut icon" href="{{ url('admin/images/favicon.jpg') }}" />

    {{-- DataTable --}}
    <link rel="stylesheet" href="{{ url('admin/css/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ url('admin/css/dataTables.bootstrap4.min.css') }}">
</head>
{{-- <body class="{{ session('dark_mode') ? 'dark-mode' : '' }}"> --}}

<!-- Header -->
@include('admin.layout.header')

<!-- Sidebar -->
@include('admin.layout.sidebar')

<!-- Main Content -->
<main class="main-content" id="mainContent">
    @yield('content')
</main>

<!-- Scripts -->
<!-- plugins:js -->
<script src="{{ url('admin/vendors/js/vendor.bundle.base.js') }}"></script>
<!-- endinject -->
<!-- Plugin js for this page -->
<script src="{{ url('admin/vendors/chart.js/Chart.min.js') }}"></script>
<script src="{{ url('admin/vendors/datatables.net/jquery.dataTables.js') }}"></script>
<script src="{{ url('admin/vendors/datatables.net-bs4/dataTables.bootstrap4.js') }}"></script>
<script src="{{ url('admin/js/dataTables.select.min.js') }}"></script>
<!-- End plugin js for this page -->
<!-- inject:js -->
<script src="{{ url('admin/js/off-canvas.js') }}"></script>
<script src="{{ url('admin/js/hoverable-collapse.js') }}"></script>
<script src="{{ url('admin/js/template.js') }}"></script>
<script src="{{ url('admin/js/settings.js') }}"></script>
<script src="{{ url('admin/js/todolist.js') }}"></script>
<!-- endinject -->
<!-- Custom js for this page-->
<script src="{{ url('admin/js/dashboard.js') }}"></script>
<script src="{{ url('admin/js/Chart.roundedBarCharts.js') }}"></script>
<!-- End custom js for this page-->



{{-- NOTE: I MOVED THIS SECTION TO admin/js/My-Sweet-Alert.js FILE! After the SweetAlert2 CDN link block in the Country! I downloaded the library using 'npm' --}}
{{-- The SweetAlert2 package for Confirm Deletion Message in sections.blade.php --}}
{{-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}} <!-- CDNs blocked in Country! -->
{{-- <script type="module" src="{{ url('admin/js/sweetalert2.js') }}"></script>
        <script type="module" src="{{ url('admin/js/My-Sweet-Alert.js') }}"></script> --}}




{{-- Start: Our Custom Admin JS --}}
<script src="{{ url('admin/js/custom.js') }}"></script>
<script>
    let sidebarCollapsed = false;
    let sidebarOpen = false;

    // Sidebar toggle for desktop (collapse/expand)
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        if (window.innerWidth > 1024) {
            // Desktop: collapse/expand sidebar
            sidebarCollapsed = !sidebarCollapsed;
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                mainContent.classList.add('sidebar-collapsed');
            } else {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('sidebar-collapsed');
            }
        } else {
            // Mobile: show/hide sidebar
            sidebarOpen = !sidebarOpen;
            if (sidebarOpen) {
                sidebar.classList.add('open');
            } else {
                sidebar.classList.remove('open');
            }
        }
    }

    // Submenu toggle
    function toggleSubmenu(element) {
        if (sidebarCollapsed) return; // Don't toggle if sidebar is collapsed

        const submenu = element.nextElementSibling;
        const arrow = element.querySelector('.dropdown-arrow');

        if (submenu && submenu.classList.contains('submenu')) {
            element.classList.toggle('expanded');
            submenu.classList.toggle('expanded');
        }
    }



    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', (e) => {
        if (window.innerWidth <= 1024) {
            const sidebar = document.getElementById('sidebar');
            const menuToggle = document.querySelector('.menu-toggle');

            if (!sidebar.contains(e.target) && !menuToggle.contains(e.target) && sidebarOpen) {
                sidebar.classList.remove('open');
                sidebarOpen = false;
            }
        }
    });

    // Handle window resize
    window.addEventListener('resize', () => {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        if (window.innerWidth > 1024) {
            // Desktop: remove mobile classes
            sidebar.classList.remove('open');
            sidebarOpen = false;
        } else {
            // Mobile: remove desktop classes
            sidebar.classList.remove('collapsed');
            mainContent.classList.remove('sidebar-collapsed');
            sidebarCollapsed = false;
        }
    });

    // Animate bars on load
    window.addEventListener('load', () => {
        const bars = document.querySelectorAll('.bar');
        bars.forEach((bar, index) => {
            setTimeout(() => {
                bar.style.opacity = '0';
                bar.style.transform = 'scaleY(0)';
                setTimeout(() => {
                    bar.style.transition = 'all 0.6s ease';
                    bar.style.opacity = '1';
                    bar.style.transform = 'scaleY(1)';
                }, 100);
            }, index * 50);
        });
    });

    // Add hover effects to stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-4px)';
            card.style.boxShadow = '0 12px 40px rgba(0, 0, 0, 0.15)';
        });

        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0)';
            card.style.boxShadow = 'none';
        });
    });

    // Laravel status indicator animation
    setInterval(() => {
        const status = document.querySelector('.laravel-status');
        if (status) {
            status.style.transform = 'scale(1.2)';
            setTimeout(() => {
                status.style.transform = 'scale(1)';
            }, 200);
        }
    }, 3000);
</script>

@stack('scripts')
</body>

</html>
