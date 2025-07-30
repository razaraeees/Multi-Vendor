@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Categories Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-category') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Category
            </a>
        </div>

        
    </div>
    {{-- <h5>Order #{{ $orderDetails['id'] }}</h5> --}}

    <!-- Alerts -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ Session::get('error_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Order Summary -->
    <div class="row g-4 mb-4">
        <!-- Order Information -->
        <div class="col-lg-6">
            <div class="card border">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-receipt me-2"></i> Order Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Order ID:</th>
                            <td>#{{ $orderDetails['id'] }}</td>
                        </tr>
                        <tr>
                            <th>Date:</th>
                            <td>{{ date('M d, Y h:i A', strtotime($orderDetails['created_at'])) }}</td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                <span class="badge bg-{{ 
                                    $orderDetails['order_status'] == 'Delivered' ? 'success' : 
                                    ($orderDetails['order_status'] == 'Shipped' ? 'info' : 
                                    ($orderDetails['order_status'] == 'New' ? 'primary' : 'warning')) 
                                }}">
                                    {{ $orderDetails['order_status'] }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Total:</th>
                            <td><strong>₹{{ number_format($orderDetails['grand_total'], 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Shipping Charges:</th>
                            <td>₹{{ number_format($orderDetails['shipping_charges'], 2) }}</td>
                        </tr>
                        @if (!empty($orderDetails['coupon_code']))
                            <tr>
                                <th>Coupon:</th>
                                <td>
                                    {{ $orderDetails['coupon_code'] }} 
                                    (-₹{{ number_format($orderDetails['coupon_amount'], 2) }})
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th>Payment Method:</th>
                            <td>{{ ucfirst($orderDetails['payment_method']) }}</td>
                        </tr>
                        <tr>
                            <th>Gateway:</th>
                            <td>{{ ucfirst($orderDetails['payment_gateway']) }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Customer Details -->
        <div class="col-lg-6">
            <div class="card border">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i> Customer Details</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Name:</th>
                            <td>{{ $userDetails['name'] }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>{{ $userDetails['email'] }}</td>
                        </tr>
                        <tr>
                            <th>Mobile:</th>
                            <td>{{ $userDetails['mobile'] }}</td>
                        </tr>
                        @if (!empty($userDetails['address']))
                            <tr>
                                <th>Address:</th>
                                <td>{{ $userDetails['address'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($userDetails['city']))
                            <tr>
                                <th>City:</th>
                                <td>{{ $userDetails['city'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($userDetails['state']))
                            <tr>
                                <th>State:</th>
                                <td>{{ $userDetails['state'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($userDetails['country']))
                            <tr>
                                <th>Country:</th>
                                <td>{{ $userDetails['country'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($userDetails['pincode']))
                            <tr>
                                <th>Pincode:</th>
                                <td>{{ $userDetails['pincode'] }}</td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Delivery Address -->
        <div class="col-lg-6">
            <div class="card border">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Delivery Address</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless mb-0">
                        <tr>
                            <th width="40%">Name:</th>
                            <td>{{ $orderDetails['name'] }}</td>
                        </tr>
                        @if (!empty($orderDetails['address']))
                            <tr>
                                <th>Address:</th>
                                <td>{{ $orderDetails['address'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($orderDetails['city']))
                            <tr>
                                <th>City:</th>
                                <td>{{ $orderDetails['city'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($orderDetails['state']))
                            <tr>
                                <th>State:</th>
                                <td>{{ $orderDetails['state'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($orderDetails['country']))
                            <tr>
                                <th>Country:</th>
                                <td>{{ $orderDetails['country'] }}</td>
                            </tr>
                        @endif
                        @if (!empty($orderDetails['pincode']))
                            <tr>
                                <th>Pincode:</th>
                                <td>{{ $orderDetails['pincode'] }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Mobile:</th>
                            <td>{{ $orderDetails['mobile'] }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Update Order Status -->
        @if (Auth::guard('admin')->user()->type != 'vendor')
            <div class="col-lg-6">
                <div class="card border">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Update Order Status</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ url('admin/update-order-status') }}" method="POST">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $orderDetails['id'] }}">

                            <div class="form-group mb-3">
                                <label for="order_status" class="form-label">Select Status</label>
                                <select name="order_status" id="order_status" class="form-select" required>
                                    <option value="">Select Status</option>
                                    @foreach ($orderStatuses as $status)
                                        <option value="{{ $status['name'] }}"
                                            {{ $orderDetails['order_status'] == $status['name'] ? 'selected' : '' }}>
                                            {{ $status['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3 d-none" id="courierFields">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="courier_name" class="form-control" placeholder="Courier Name">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <input type="text" name="tracking_number" class="form-control" placeholder="Tracking Number">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i> Update Status
                            </button>
                        </form>

                        <!-- Status History -->
                        <div class="mt-4">
                            <h6 class="fw-bold">Status History:</h6>
                            <div class="border rounded p-3" style="max-height: 200px; overflow-y: auto;">
                                @foreach ($orderLog as $log)
                                    <div class="border-bottom pb-2 mb-2">
                                        <strong>{{ $log['order_status'] }}</strong>
                                        @if (isset($log['order_item_id']) && $log['order_item_id'] > 0)
                                            @php $getItemDetails = \App\Models\OrdersLog::getItemDetails($log['order_item_id']); @endphp
                                            <div class="text-muted small">For: {{ $getItemDetails['product_code'] }}</div>
                                            @if (!empty($getItemDetails['courier_name']))
                                                <div class="text-muted small">Courier: {{ $getItemDetails['courier_name'] }}</div>
                                            @endif
                                            @if (!empty($getItemDetails['tracking_number']))
                                                <div class="text-muted small">Track #: {{ $getItemDetails['tracking_number'] }}</div>
                                            @endif
                                        @endif
                                        <div class="text-muted small">{{ date('M d, Y h:i A', strtotime($log['created_at'])) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-lg-6">
                <div class="alert alert-secondary">
                    <i class="fas fa-lock"></i> Order status update is restricted for vendors.
                </div>
            </div>
        @endif
    </div>

    <!-- Ordered Products -->
    <div class="card border">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fas fa-box-open me-2"></i> Ordered Products</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Image</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Size</th>
                            <th>Color</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            @if (Auth::guard('admin')->user()->type != 'vendor')
                                <th>Seller</th>
                                <th>Commission</th>
                                <th>Final Amount</th>
                            @endif
                            <th>Item Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orderDetails['orders_products'] as $product)
                            @php
                                $getProductImage = \App\Models\Product::getProductImage($product['product_id']);
                                $total_price = $product['product_price'] * $product['product_qty'];
                                $commission = $product['vendor_id'] > 0 ? round($total_price * $product['commission'] / 100, 2) : 0;
                                $final_amount = $total_price - $commission;
                            @endphp
                            <tr>
                                <td class="text-center">
                                    <a href="{{ url('product/' . $product['product_id']) }}" target="_blank">
                                        <img src="{{ asset('front/images/product_images/small/' . $getProductImage) }}"
                                             alt="{{ $product['product_name'] }}" class="rounded" width="50">
                                    </a>
                                </td>
                                <td><span class="badge bg-secondary">{{ $product['product_code'] }}</span></td>
                                <td>{{ $product['product_name'] }}</td>
                                <td>{{ $product['product_size'] }}</td>
                                <td>{{ $product['product_color'] }}</td>
                                <td>₹{{ number_format($product['product_price'], 2) }}</td>
                                <td><span class="badge bg-primary">{{ $product['product_qty'] }}</span></td>
                                <td>₹{{ number_format($total_price, 2) }}</td>
                                @if (Auth::guard('admin')->user()->type != 'vendor')
                                    <td>
                                        @if ($product['vendor_id'] > 0)
                                            <a href="{{ url('admin/view-vendor-details/' . $product['admin_id']) }}" target="_blank" class="badge bg-info text-decoration-none">Vendor</a>
                                        @else
                                            <span class="badge bg-secondary">Admin</span>
                                        @endif
                                    </td>
                                    <td>₹{{ number_format($commission, 2) }}</td>
                                    <td>₹{{ number_format($final_amount, 2) }}</td>
                                @endif
                                <td>
                                    <form action="{{ url('admin/update-order-item-status') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="order_item_id" value="{{ $product['id'] }}">
                                        
                                        <div class="form-group mb-2">
                                            <select name="order_item_status" class="form-select form-select-sm" required>
                                                <option value="">Select</option>
                                                @foreach ($orderItemStatuses as $status)
                                                    <option value="{{ $status['name'] }}" {{ $product['item_status'] == $status['name'] ? 'selected' : '' }}>
                                                        {{ $status['name'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        
                                        <div class="form-group mb-2">
                                            <div class="row">
                                                <div class="col-6">
                                                    <input type="text" name="item_courier_name" class="form-control form-control-sm" placeholder="Courier" value="{{ $product['courier_name'] ?? '' }}">
                                                </div>
                                                <div class="col-6">
                                                    <input type="text" name="item_tracking_number" class="form-control form-control-sm" placeholder="Track #" value="{{ $product['tracking_number'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <button type="submit" class="btn btn-sm btn-success w-100">
                                            <i class="fas fa-save me-1"></i> Update
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- JavaScript for Courier Fields -->
    <script>
        document.getElementById('order_status').addEventListener('change', function () {
            const courierFields = document.getElementById('courierFields');
            if (this.value === 'Shipped') {
                courierFields.classList.remove('d-none');
            } else {
                courierFields.classList.add('d-none');
            }
        });
    </script>
@endsection