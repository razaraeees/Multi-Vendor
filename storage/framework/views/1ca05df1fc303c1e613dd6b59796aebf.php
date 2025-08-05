

<?php $__env->startSection('content'); ?>
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Welcome, <?php echo e(Auth::guard('admin')->user()->name); ?></h1>
        <div class="page-actions">
            <button class="btn">
                <i class="fas fa-calendar"></i> Weekly
            </button>
            <button class="btn">
                <i class="fas fa-calendar-alt"></i> Select date
            </button>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Sections</div>
                    <div class="stat-value"><?php echo e($sectionsCount); ?></div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +5.0%
                    </div>
                </div>
                <div class="mini-chart" style="background: linear-gradient(90deg, #06b6d4, #3b82f6);"></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Categories</div>
                    <div class="stat-value"><?php echo e($categoriesCount); ?></div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +3.2%
                    </div>
                </div>
                <div class="mini-chart" style="background: linear-gradient(90deg, #f97316, #ef4444);"></div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="stat-header">
                <div>
                    <div class="stat-title">Total Products</div>
                    <div class="stat-value"><?php echo e($productsCount); ?></div>
                    <div class="stat-change positive">
                        <i class="fas fa-arrow-up"></i> +7.1%
                    </div>
                </div>
                <div class="mini-chart" style="background: linear-gradient(90deg, #8b5cf6, #3b82f6);"></div>
            </div>
        </div>
    </div>

    <!-- Charts Grid -->
    <div class="charts-grid">
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Revenue Report</h3>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-dot blue"></div> Net Profit
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot cyan"></div> Revenue
                    </div>
                    <div class="legend-item">
                        <div class="legend-dot orange"></div> Free Cash Flow
                    </div>
                </div>
            </div>
            <div class="revenue-chart">
                <div class="bar-chart">
                    <div class="bar-group"><div class="bar blue" style="height: 60%;"></div><div class="bar cyan" style="height: 80%;"></div><div class="bar orange" style="height: 40%;"></div></div>
                    <div class="bar-group"><div class="bar blue" style="height: 70%;"></div><div class="bar cyan" style="height: 90%;"></div><div class="bar orange" style="height: 50%;"></div></div>
                    <div class="bar-group"><div class="bar blue" style="height: 80%;"></div><div class="bar cyan" style="height: 100%;"></div><div class="bar orange" style="height: 60%;"></div></div>
                </div>
            </div>
        </div>
        
        <div class="chart-card">
            <div class="chart-header">
                <h3 class="chart-title">Overview</h3>
                <button class="btn"><i class="fas fa-cog"></i></button>
            </div>
            <div class="overview-chart">
                <div class="donut-chart">
                    <div class="chart-center">
                        <div class="total-label">Total</div>
                        <div class="total-value">249</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\multi-vendor\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>