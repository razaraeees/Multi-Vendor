@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Products Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-product') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Product
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Products Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="products" class="table table-bordered dt-responsive nowrap" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Product Code</th>
                            <th>Product Color</th>
                            <th>Product Image</th>
                            <th>Category</th>
                            <th>Added by</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>{{ $product['id'] }}</td>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['product_code'] }}</td>
                                <td>{{ $product['product_color'] }}</td>
                                <td>
                                    @if (!empty($product['product_image']))
                                        <img src="{{ asset('front/images/product_images/small/' . $product['product_image']) }}"
                                            alt="Product Image"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                    @else
                                        <img src="{{ asset('front/images/product_images/small/no-image.png') }}"
                                            alt="No Image"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                    @endif
                                </td>
                                <td>{{ $product['category']['category_name'] ?? 'No Category' }}</td>
                                <td>
                                    @if ($product['admin_type'] == 'vendor')
                                        <a target="_blank"
                                            href="{{ url('admin/view-vendor-details/' . $product['admin_id']) }}"
                                            class="text-primary font-weight-bold">
                                            {{ ucfirst($product['admin_type']) }}
                                        </a>
                                    @else
                                        {{ ucfirst($product['admin_type']) }}
                                    @endif
                                </td>
                                <td>
                                    @if ($product['status'] == 1)
                                        <a class="updateProductStatus" id="product-{{ $product['id'] }}"
                                            product_id="{{ $product['id'] }}" href="javascript:void(0)">
                                            <i class="fas fa-check-circle text-success" status="Active"></i>
                                        </a>
                                    @else
                                        <a class="updateProductStatus" id="product-{{ $product['id'] }}"
                                            product_id="{{ $product['id'] }}" href="javascript:void(0)">
                                            <i class="fas fa-times-circle text-secondary" status="Inactive"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                        <!-- Edit Product -->
                                        <a href="{{ url('admin/add-edit-product/' . $product['id']) }}"
                                            class="btn btn-sm btn-outline-info px-2 py-1" title="Edit Product">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <!-- Add Attributes -->
                                        <a href="{{ url('admin/add-edit-attributes/' . $product['id']) }}"
                                            class="btn btn-sm btn-outline-warning px-2 py-1" title="Add Attributes">
                                            <i class="fas fa-plus"></i>
                                        </a>

                                        <!-- Add Images -->
                                        {{-- <a href="{{ url('admin/add-images/' . $product['id']) }}"
                                           class="btn btn-sm btn-outline-success px-2 py-1"
                                           title="Add Images">
                                            <i class="fas fa-images"></i>
                                        </a> --}}

                                        <!-- Delete Product -->
                                        <a href="JavaScript:void(0)"
                                            class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" module="product"
                                            moduleid="{{ $product['id'] }}" title="Delete Product">
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
    $(document).ready(function() {
        $('#products').DataTable({
            responsive: true,
            scrollY: 400, // Set the height of the scrollable area
            scrollCollapse: true,
            paging: false, // Disable pagination if you want only scroll
            fixedHeader: true, // Keep headers fixed while scrolling
            columnDefs: [{
                    targets: [4],
                    orderable: false
                }, // Make image column not sortable
                {
                    targets: [9],
                    orderable: false
                } // Make actions column not sortable
            ]
        });
    });
</script>
