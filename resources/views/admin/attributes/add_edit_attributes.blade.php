{{-- This page is rendered by addAttributes() method in Admin/ProductsController.php --}}
@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Product Attributes Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/products') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Products
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ Session::get('error_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Please fix the following errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="row">
        <!-- Product Details Card -->
        <div class="col-md-4 mb-4">
            <div class="card shadow-sm border-0 h-100 rounded-lg">
                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="fas fa-box mr-2 text-primary"></i>Product Details
                    </h5>
                </div>
                <div class="card-body bg-white">
                    <div class="product-info">
                        <div class="text-center mb-3">
                            @if (!empty($product['product_image']))
                                <img class="img-thumbnail rounded" style="width: 150px; height: 150px; object-fit: cover;" 
                                     src="{{ url('front/images/product_images/small/' . $product['product_image']) }}" 
                                     alt="Product Image">
                            @else
                                <img class="img-thumbnail rounded" style="width: 150px; height: 150px; object-fit: cover;" 
                                     src="{{ url('front/images/product_images/small/no-image.png') }}" 
                                     alt="No Image">
                            @endif
                        </div>
                        
                        <div class="info-item mb-2">
                            <strong>Name:</strong> 
                            <span class="text-muted">{{ $product['product_name'] }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <strong>Code:</strong> 
                            <span class="badge badge-secondary rounded-pill">{{ $product['product_code'] }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <strong>Color:</strong> 
                            <span class="text-muted">{{ $product['product_color'] }}</span>
                        </div>
                        <div class="info-item mb-2">
                            <strong>Price:</strong> 
                            <span class="text-success font-weight-bold">${{ $product['product_price'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Attributes Form -->
        <div class="col-md-8 mb-4">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
                    <h5 class="card-title mb-0 text-dark">
                        <i class="fas fa-plus-circle mr-2 text-success"></i>Add New Attributes
                    </h5>
                </div>
                <div class="card-body bg-white">
                    <form class="forms-sample" action="{{ url('admin/add-edit-attributes/' . $product['id']) }}" method="post">
                        @csrf

                        <!-- Dynamic Fields -->
                        <div class="form-group">
                            <div class="field_wrapper">
                                <div class="attribute-row bg-light border rounded p-3 mb-2">
                                    <div class="row align-items-center">
                                        <div class="col-md-2">
                                            <input type="text" name="size[]" placeholder="Size" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-3">
                                            <input type="text" name="sku[]" placeholder="SKU" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="price[]" placeholder="Price" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-2">
                                            <input type="number" name="stock[]" placeholder="Stock" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-3">
                                            <button type="button" class="add_button btn btn-sm btn-success rounded-pill" title="Add More Attributes">
                                                <i class="fas fa-plus mr-1"></i> Add More
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex pt-2">
                            <button type="submit" class="btn btn-success mr-2 rounded-pill">
                                <i class="fas fa-save mr-1"></i> Add Attributes
                            </button>
                            <button type="reset" class="btn btn-light rounded-pill">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Existing Attributes Table -->
    <div class="card shadow-sm border-0 rounded-lg">
        <div class="card-header" style="background-color: #f8f9fa; border-bottom: 1px solid #e9ecef;">
            <h5 class="card-title mb-0 text-dark">
                <i class="fas fa-list mr-2 text-info"></i>Existing Product Attributes
            </h5>
        </div>
        <div class="card-body bg-white p-3">
            <form method="post" action="{{ url('admin/edit-attributes/' . $product['id']) }}">
                @csrf

                <div class="table-responsive">
                    <table id="products" class="table table-hover align-middle">
                        <thead style="background-color: #6c757d;">
                            <tr>
                                <th class="text-white">ID</th>
                                <th class="text-white">Size</th>
                                <th class="text-white">SKU</th>
                                <th class="text-white">Price</th>
                                <th class="text-white">Stock</th>
                                <th class="text-white">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($product['attributes'] as $attribute)
                                <input type="hidden" name="attributeId[]" value="{{ $attribute['id'] }}">
                                <tr>
                                    <td>{{ $attribute['id'] }}</td>
                                    <td>
                                        <span class="badge badge-primary rounded-pill">{{ $attribute['size'] }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-secondary rounded-pill">{{ $attribute['sku'] }}</span>
                                    </td>
                                    <td>
                                        <div class="input-group input-group-sm" style="width: 80px;">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" name="price[]" value="{{ $attribute['price'] }}" 
                                                   class="form-control form-control-sm" required>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name="stock[]" value="{{ $attribute['stock'] }}" 
                                               class="form-control form-control-sm rounded" required style="width: 70px;">
                                    </td>
                                    <td>
                                        <a class="updateAttributeStatus" 
                                           id="attribute-{{ $attribute['id'] }}" 
                                           attribute_id="{{ $attribute['id'] }}" 
                                           href="javascript:void(0)">
                                            @if ($attribute['status'] == 1)
                                                <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                            @else
                                                <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                            @endif
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="pt-3">
                    <button type="submit" class="btn btn-primary rounded-pill">
                        <i class="fas fa-sync-alt mr-1"></i> Update Attributes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
      
        
        .page-header .page-title {
            color: #000;
            font-weight: 600;
            margin: 0;
        }
        
        .card {
            border-radius: 10px;
            overflow: hidden;
        }
        
        .card-header {
            font-weight: 600;
        }
        
        .info-item {
            padding: 0.25rem 0;
            border-bottom: 1px solid #f1f1f1;
        }
        
        .info-item:last-child {
            border-bottom: none;
        }
        
        .table th {
            font-weight: 600;
            color: white !important;
            vertical-align: middle;
        }
        
        .table td {
            vertical-align: middle;
            padding: 0.75rem;
        }
        
        .table-hover tbody tr:hover {
            background-color: rgba(0,123,255,0.05);
        }
        
        .field_wrapper .row {
            background: #f8f9fa;
        }
        
        .input-group-text {
            font-size: 0.875rem;
        }
        
        .badge {
            font-size: 0.75rem;
        }
    </style>

@endsection

<!-- Dynamic Add/Remove Fields Script -->
<script>
$(document).ready(function(){
    var maxField = 10; // Input fields increment limit
    var addButton = $('.add_button'); // Add button selector
    var wrapper = $('.field_wrapper'); // Input field wrapper
    var fieldHTML = '<div class="attribute-row bg-light border rounded p-3 mb-2"><div class="row align-items-center"><div class="col-md-2"><input type="text" name="size[]" placeholder="Size" class="form-control form-control-sm" required></div><div class="col-md-3"><input type="text" name="sku[]" placeholder="SKU" class="form-control form-control-sm" required></div><div class="col-md-2"><input type="number" name="price[]" placeholder="Price" class="form-control form-control-sm" required></div><div class="col-md-2"><input type="number" name="stock[]" placeholder="Stock" class="form-control form-control-sm" required></div><div class="col-md-3"><button type="button" class="remove_button btn btn-sm btn-danger rounded-pill" title="Remove"><i class="fas fa-trash mr-1"></i> Remove</button></div></div></div>';
    var x = 1; // Initial field counter
    
    // Once add button is clicked
    $(addButton).click(function(){
        // Check maximum number of input fields
        if(x < maxField){ 
            x++; // Increment field counter
            $(wrapper).append(fieldHTML); // Add field html
        }
    });
    
    // Once remove button is clicked
    $(wrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).closest('.attribute-row').remove(); // Remove field html
        x--; // Decrement field counter
    });
});
</script>