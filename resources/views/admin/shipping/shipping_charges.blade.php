@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Shipping Charges</h1>
        <div class="page-actions">
            <a href="{{ url('admin/dashboard') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
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
            <!-- Table Responsive (Horizontal Scroll) -->
            <div class="table-responsive" style="min-height: 200px;">
                <table id="shipping" class="table table-bordered table-striped mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Country</th>
                            <th>Rate (0g - 500g)</th>
                            <th>Rate (501g - 1000g)</th>
                            <th>Rate (1001g - 2000g)</th>
                            <th>Rate (2001g - 5000g)</th>
                            <th>Rate (Above 5000g)</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shippingCharges as $shipping)
                            <tr>
                                <td>{{ $shipping['id'] }}</td>
                                <td>{{ $shipping['country'] }}</td>
                                <td>₹{{ $shipping['0_500g'] }}</td>
                                <td>₹{{ $shipping['501g_1000g'] }}</td>
                                <td>₹{{ $shipping['1001_2000g'] }}</td>
                                <td>₹{{ $shipping['2001g_5000g'] }}</td>
                                <td>₹{{ $shipping['above_5000g'] }}</td>
                                <td>
                                    <a class="updateShippingStatus" 
                                       id="shipping-{{ $shipping['id'] }}" 
                                       shipping_id="{{ $shipping['id'] }}" 
                                       href="javascript:void(0)">
                                        @if ($shipping['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td>

                                    <a href="{{ url('admin/edit-shipping-charges/' . $shipping['id']) }}" 
                                           class="btn btn-sm btn-outline-info px-2 py-1" 
                                           title="Edit Banner">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    {{-- <a href="{{ url('admin/edit-shipping-charges/' . $shipping['id']) }}" 
                                       class="me-2 text-primary" 
                                       title="Edit">
                                        <i class="fas fa-edit" style="font-size: 25px;"></i>
                                    </a> --}}
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
                    { targets: [7, 8], orderable: false } // Status & Actions not sortable
                ]
            });
        });
    </script>
@endsection