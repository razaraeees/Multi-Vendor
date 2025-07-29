@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Categories Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-category') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Categories Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="categories" class="table table-hover align-middle ">
                    <thead class="bg-light" style="background-color: black" >
                        <tr>
                            <th>ID</th>
                            <th>Category Name</th>
                            <th>Parent Category</th>
                            <th>Parent Section</th>
                            <th>URL</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category['id'] }}</td>
                                <td>{{ $category['category_name'] }}</td>
                                <td>
                                    @if (isset($category['parent_category']['category_name']) && !empty($category['parent_category']['category_name']))
                                        {{ $category['parent_category']['category_name'] }}
                                    @else
                                        Root
                                    @endif
                                </td>
                                <td>{{ $category['section']['name'] }}</td>
                                <td>{{ $category['url'] }}</td>
                                <td>
                                    <a class="updateCategoryStatus" 
                                       id="category-{{ $category['id'] }}" 
                                       category_id="{{ $category['id'] }}" 
                                       href="javascript:void(0)">
                                        @if ($category['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2 ">
                                        <a href="{{ url('admin/add-edit-category/' . $category['id']) }}" 
                                           class="btn btn-sm btn-outline-info px-2 py-1" 
                                           title="Edit Category">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="category" 
                                           moduleid="{{ $category['id'] }}" 
                                           title="Delete Category">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
<script>
    $(document).ready(function () {
    $('#categories').DataTable({
        responsive: true,
        scrollY: 400, // Set the height of the scrollable area
        scrollCollapse: true,
        paging: false, // Disable pagination if you want only scroll
        fixedHeader: true, // Keep headers fixed while scrolling
        columnDefs: [
            { targets: [4], orderable: false }, // Make image column not sortable
            { targets: [9], orderable: false }  // Make actions column not sortable
        ]
    });
});
</script>