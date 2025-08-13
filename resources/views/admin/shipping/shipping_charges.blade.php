@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Shipping Charges</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-shipping-charges') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New
            </a>
        </div>
    </div>

    <!-- Success Alert -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card -->
    <div class="card shadow-sm border">
        <div class="card-body p-3">
            <!-- Table Responsive -->
            <div class="table-responsive" style="min-height: 200px;">
                <table id="shipping" class="table table-bordered table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Shipping Charge</th>
                            <th>Free Shipping Min Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shippingCharges as $shipping)
                            <tr>
                                <td>{{ $shipping['id'] }}</td>
                                <td>PKR{{ $shipping['shipping_charge'] }}</td>
                                <td>PKR{{ $shipping['free_shipping_min_amount'] }}</td>
                                <td>
                                    <a href="{{ url('admin/edit-shipping-charges/' . $shipping['id']) }}" 
                                       class="btn btn-sm btn-outline-info px-2 py-1" 
                                       title="Edit Shipping Charges">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            $('#shipping').DataTable({
                responsive: true,
                scrollX: true, // Enable horizontal scrolling
                scrollCollapse: true,
                paging: true,
                pageLength: 10,
                ordering: true,
                info: true,
                columnDefs: [
                    { targets: [5, 6], orderable: false } // Status & Actions not sortable
                ]
            });
        });
    </script>
@endsection