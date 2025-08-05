@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Categories Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-category') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Categories
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ Session::get('error_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Categories Table -->
    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped" id="categoriesTable">
                <thead>
                    <tr>
                        <th>Category Name</th>
                        <th>Image</th>
                        <th>URL</th>
                        <th>Discount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @if(isset($categories) && count($categories) > 0)
                        @foreach($categories as $category)
                            @if($category['level'] == 1)
                                <!-- Main Category Row -->
                                <tr class="main-category" data-category-id="{{ $category['id'] }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @php
                                                $hasSubCategories = false;
                                                foreach($categories as $subCat) {
                                                    if($subCat['parent_id'] == $category['id'] && $subCat['level'] == 2) {
                                                        $hasSubCategories = true;
                                                        break;
                                                    }
                                                }
                                            @endphp
                                            
                                            @if($hasSubCategories)
                                                <i class="fas fa-plus-circle text-primary me-2 expand-icon" 
                                                   style="cursor: pointer;" title="Click to expand"></i>
                                            @else
                                                <i class="fas fa-circle text-primary me-2" style="font-size: 8px;"></i>
                                            @endif
                                            
                                            <i class="fas fa-folder text-primary me-2"></i>
                                            <strong>{{ $category['category_name'] }}</strong>
                                        </div>
                                    </td>
                                    <td>
                                        @if(!empty($category['category_image']))
                                            <img src="{{ asset('front/images/category_images/'.$category['category_image']) }}" 
                                                 alt="{{ $category['category_name'] }}" 
                                                 class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px; border-radius: 4px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td><small>{{ $category['url'] ?? '-' }}</small></td>
                                    <td>{{ $category['category_discount'] ?? 0 }}%</td>
                                    <td>
                                        <span class="badge bg-{{ $category['status'] == 1 ? 'success' : 'danger' }}">
                                            {{ $category['status'] == 1 ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ url('admin/add-edit-category/'.$category['id']) }}" 
                                           class="btn btn-sm btn-warning me-1" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-danger confirmDelete" 
                                                module="category" moduleid="{{ $category['id'] }}" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Sub Categories (Initially Hidden) -->
                                @foreach($categories as $subCategory)
                                    @if($subCategory['parent_id'] == $category['id'] && $subCategory['level'] == 2)
                                        <tr class="sub-category sub-category-{{ $category['id'] }}" style="display: none;">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <span class="ms-3"></span>
                                                    @php
                                                        $hasSubSub = false;
                                                        foreach($categories as $subSubCat) {
                                                            if($subSubCat['parent_id'] == $subCategory['id'] && $subSubCat['level'] == 3) {
                                                                $hasSubSub = true;
                                                                break;
                                                            }
                                                        }
                                                    @endphp
                                                    
                                                    @if($hasSubSub)
                                                        <i class="fas fa-plus-circle text-info me-2 expand-sub-icon" 
                                                           style="cursor: pointer;" 
                                                           data-subcategory-id="{{ $subCategory['id'] }}" 
                                                           title="Click to expand"></i>
                                                    @else
                                                        <i class="fas fa-circle text-info me-2" style="font-size: 8px;"></i>
                                                    @endif
                                                    
                                                    <i class="fas fa-folder-open text-info me-2"></i>
                                                    {{ $subCategory['category_name'] }}
                                                </div>
                                            </td>
                                            <td>
                                                @if(!empty($subCategory['category_image']))
                                                    <img src="{{ asset('front/images/category_images/'.$subCategory['category_image']) }}" 
                                                         alt="{{ $subCategory['category_name'] }}" 
                                                         class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px; border-radius: 4px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td><small>{{ $subCategory['url'] ?? '-' }}</small></td>
                                            <td>{{ $subCategory['category_discount'] ?? 0 }}%</td>
                                            <td>
                                                <span class="badge bg-{{ $subCategory['status'] == 1 ? 'success' : 'danger' }}">
                                                    {{ $subCategory['status'] == 1 ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ url('admin/add-edit-category/'.$subCategory['id']) }}" 
                                                   class="btn btn-sm btn-warning me-1" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-sm btn-danger confirmDelete" 
                                                        module="category" moduleid="{{ $subCategory['id'] }}" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- Sub-Sub Categories (Initially Hidden) -->
                                        @foreach($categories as $subSubCategory)
                                            @if($subSubCategory['parent_id'] == $subCategory['id'] && $subSubCategory['level'] == 3)
                                                <tr class="sub-sub-category sub-sub-category-{{ $subCategory['id'] }}" style="display: none;">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="ms-5"></span>
                                                            <i class="fas fa-circle text-secondary me-2" style="font-size: 6px;"></i>
                                                            <i class="fas fa-file text-secondary me-2"></i>
                                                            {{ $subSubCategory['category_name'] }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        @if(!empty($subSubCategory['category_image']))
                                                            <img src="{{ asset('front/images/category_images/'.$subSubCategory['category_image']) }}" 
                                                                 alt="{{ $subSubCategory['category_name'] }}" 
                                                                 class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                                 style="width: 40px; height: 40px; border-radius: 4px;">
                                                                <i class="fas fa-image text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td><small>{{ $subSubCategory['url'] ?? '-' }}</small></td>
                                                    <td>{{ $subSubCategory['category_discount'] ?? 0 }}%</td>
                                                    <td>
                                                        <span class="badge bg-{{ $subSubCategory['status'] == 1 ? 'success' : 'danger' }}">
                                                            {{ $subSubCategory['status'] == 1 ? 'Active' : 'Inactive' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ url('admin/add-edit-category/'.$subSubCategory['id']) }}" 
                                                           class="btn btn-sm btn-warning me-1" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-danger confirmDelete" 
                                                                module="category" moduleid="{{ $subSubCategory['id'] }}" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    @else
                        <!-- No Categories Found -->
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted mb-2">No Categories Found</h5>
                                    <p class="text-muted mb-3">You haven't created any categories yet.</p>
                                    <a href="{{ url('admin/add-edit-category') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Your First Category
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Expand/Collapse Main Categories
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('expand-icon')) {
                const categoryId = e.target.closest('tr').dataset.categoryId;
                const subCategories = document.querySelectorAll(`.sub-category-${categoryId}`);
                const icon = e.target;
                
                if (icon.classList.contains('fa-plus-circle')) {
                    // Expand
                    subCategories.forEach(row => row.style.display = '');
                    icon.classList.remove('fa-plus-circle');
                    icon.classList.add('fa-minus-circle');
                    icon.title = 'Click to collapse';
                } else {
                    // Collapse
                    subCategories.forEach(row => {
                        row.style.display = 'none';
                        // Also hide sub-sub categories
                        const subSubRows = document.querySelectorAll('[class*="sub-sub-category-"]');
                        subSubRows.forEach(subSubRow => {
                            if (subSubRow.classList.toString().includes(`sub-sub-category-`)) {
                                subSubRow.style.display = 'none';
                            }
                        });
                    });
                    icon.classList.remove('fa-minus-circle');
                    icon.classList.add('fa-plus-circle');
                    icon.title = 'Click to expand';
                    
                    // Reset sub-category icons
                    const subIcons = document.querySelectorAll(`.sub-category-${categoryId} .expand-sub-icon`);
                    subIcons.forEach(subIcon => {
                        subIcon.classList.remove('fa-minus-circle');
                        subIcon.classList.add('fa-plus-circle');
                        subIcon.title = 'Click to expand';
                    });
                }
            }
        });

        // Expand/Collapse Sub Categories
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('expand-sub-icon')) {
                const subCategoryId = e.target.dataset.subcategoryId;
                const subSubCategories = document.querySelectorAll(`.sub-sub-category-${subCategoryId}`);
                const icon = e.target;
                
                if (icon.classList.contains('fa-plus-circle')) {
                    // Expand
                    subSubCategories.forEach(row => row.style.display = '');
                    icon.classList.remove('fa-plus-circle');
                    icon.classList.add('fa-minus-circle');
                    icon.title = 'Click to collapse';
                } else {
                    // Collapse
                    subSubCategories.forEach(row => row.style.display = 'none');
                    icon.classList.remove('fa-minus-circle');
                    icon.classList.add('fa-plus-circle');
                    icon.title = 'Click to expand';
                }
            }
        });
    </script>
@endsection