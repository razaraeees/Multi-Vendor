@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Brands Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-brand') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Brand
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

    <!-- Brands Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="categories" class="table table-bordered dt-responsive nowrap" style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($brands as $brand)
                            <tr>
                                <td>{{ $brand['id'] }}</td>
                                <td>{{ $brand['name'] }}</td>
                                <td>
                                    <a class="updateBrandStatus"
                                       id="brand-{{ $brand['id'] }}"
                                       brand_id="{{ $brand['id'] }}"
                                       href="javascript:void(0)">
                                        @if ($brand['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                        <!-- Edit Brand -->
                                        <a href="{{ url('admin/add-edit-brand/' . $brand['id']) }}"
                                           class="btn btn-sm btn-outline-info px-2 py-1"
                                           title="Edit Brand">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <!-- Delete Brand -->
                                        <a href="JavaScript:void(0)"
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1"
                                           module="brand"
                                           moduleid="{{ $brand['id'] }}"
                                           title="Delete Brand">
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
@push('scripts')
<script>
$(document).ready(function() {
    $('#brands').DataTable({
        "scrollY": "500px",
        "scrollCollapse": true,
        "paging": false,
        "info": false,
        "searching": true
    });
});
</script>
@endpush