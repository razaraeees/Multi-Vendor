@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Orders Management</h1>
    </div>

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Orders Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="orders" class="table table-bordered dt-responsive nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Order Date</th>
                            <th>Customer Name</th>
                            <th>Customer Email</th>
                            <th>Ordered Products</th>
                            <th>Order Amount</th>
                            <th>Order Status</th>
                            <th>Payment Method</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($orders as $order)
                            @if ($order['orders_products'])
                                <tr>
                                    <td>{{ $order['id'] }}</td>
                                    <td>{{ date('Y-m-d h:i:s', strtotime($order['created_at'])) }}</td>
                                    <td>{{ $order['name'] }}</td>
                                    <td>{{ $order['email'] }}</td>
                                    <td>
                                        @foreach ($order['orders_products'] as $product)
                                            {{ $product['product_code'] }} ({{ $product['product_qty'] }})
                                            <br>
                                        @endforeach
                                    </td>
                                    <td>{{ $order['grand_total'] }}</td>
                                    <td>{{ $order['order_status'] }}</td>
                                    <td>{{ $order['payment_method'] }}</td>
                                    <td>
                                        <div class="action-buttons d-flex gap-2">
                                            <a title="View Order Details" 
                                               href="{{ url('admin/orders/' . $order['id']) }}"
                                               class="btn btn-sm btn-outline-info px-2 py-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a title="View Invoice" 
                                               href="{{ url('admin/orders/invoice/' . $order['id']) }}" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-secondary px-2 py-1">
                                                <i class="fas fa-print"></i>
                                            </a>
                                            <a title="Download PDF" 
                                               href="{{ url('admin/orders/invoice/pdf/' . $order['id']) }}" 
                                               target="_blank"
                                               class="btn btn-sm btn-outline-danger px-2 py-1">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

<script>
    $(document).ready(function () {
    $('#orders').DataTable({
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