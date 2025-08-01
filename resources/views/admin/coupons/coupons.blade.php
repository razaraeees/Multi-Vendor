@extends('admin.layout.layout')

@section('content')
<div class="main-panel">
    <div class="content-wrapper">

        {{-- Page Header --}}
        <div class="page-header d-flex justify-content-between align-items-center mb-4">
            <h3 class="page-title">Coupons Management</h3>
            <nav aria-label="breadcrumb">
                <a href="{{ url('admin/add-edit-coupon') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Coupon
                </a>
            </nav>
        </div>

        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">

                        {{-- Success Message --}}
                        @if (Session::has('success_message'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <strong>Success:</strong> {{ Session::get('success_message') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Coupons Table --}}
                        <div class="table-responsive">
                            <table id="coupons" class="table table-bordered dt-responsive nowrap" style="width:100%">
                                <thead class="bg-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Coupon Code</th>
                                        <th>Coupon Type</th>
                                        <th>Amount</th>
                                        <th>Expiry Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($coupons as $coupon)
                                        <tr>
                                            <td>{{ $coupon['id'] }}</td>
                                            <td>{{ $coupon['coupon_code'] }}</td>
                                            <td>{{ ucfirst($coupon['coupon_type']) }}</td>
                                            <td>
                                                {{ $coupon['amount'] }}
                                                {{ $coupon['amount_type'] == 'Percentage' ? '%' : 'INR' }}
                                            </td>
                                            <td>{{ $coupon['expiry_date'] }}</td>
                                            <td>
                                                <a class="updateCouponStatus"
                                                   id="coupon-{{ $coupon['id'] }}"
                                                   coupon_id="{{ $coupon['id'] }}"
                                                   href="javascript:void(0)">
                                                    <i class="fas {{ $coupon['status'] == 1 ? 'fa-check-circle text-success' : 'fa-times-circle text-secondary' }}"
                                                       status="{{ $coupon['status'] == 1 ? 'Active' : 'Inactive' }}"
                                                       style="font-size:20px"></i>
                                                </a>
                                            </td>
                                            <td>
                                                <div class="action-buttons d-flex gap-2 justify-content-center">
                                                    <!-- Edit Coupon -->
                                                    <a href="{{ url('admin/add-edit-coupon/' . $coupon['id']) }}"
                                                       class="btn btn-sm btn-outline-info px-2 py-1"
                                                       title="Edit Coupon">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>

                                                    <!-- Delete Coupon -->
                                                    <a href="javascript:void(0)"
                                                       class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1"
                                                       module="coupon"
                                                       moduleid="{{ $coupon['id'] }}"
                                                       title="Delete Coupon">
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
            </div>
        </div>
    </div>

</div>

{{-- DataTable Script --}}
<script>
    $(document).ready(function () {
        $('#coupons').DataTable({
            responsive: true,
            scrollY: 400,
            scrollCollapse: true,
            paging: false,
            fixedHeader: true,
            columnDefs: [
                { targets: [6], orderable: false } // Disable sorting on Actions column
            ]
        });
    });
</script>
@endsection
