@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Filter Values Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/filters') }}" class="btn btn-outline-primary btn-sm mr-2">
                <i class="fas fa-filter"></i> View Filters
            </a>
            <a href="{{ url('admin/add-edit-filter-value') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Filter Value
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

    <!-- Filter Values Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="filters" class="table table-hover align-middle">
                    <thead class="bg-light" style="background-color: black">
                        <tr>
                            <th>ID</th>
                            <th>Filter ID</th>
                            <th>Filter Name</th>
                            <th>Filter Value</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filters_values as $filter)
                            <tr>
                                <td>{{ $filter['id'] }}</td>
                                <td>{{ $filter['filter_id'] }}</td>
                                <td>
                                    @php
                                        echo $getFilterName = \App\Models\ProductsFilter::getFilterName($filter['filter_id']);
                                    @endphp
                                </td>
                                <td>{{ $filter['filter_value'] }}</td>
                                <td>
                                    <a class="updateFilterValueStatus" 
                                       id="filter-{{ $filter['id'] }}" 
                                       filter_id="{{ $filter['id'] }}" 
                                       href="javascript:void(0)">
                                        @if ($filter['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2">
                                        <a href="{{ url('admin/add-edit-filter-value/' . $filter['id']) }}" 
                                           class="btn btn-sm btn-outline-info px-2 py-1" 
                                           title="Edit Filter Value">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="filter-value" 
                                           moduleid="{{ $filter['id'] }}" 
                                           title="Delete Filter Value">
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
        $('#filters').DataTable({
            responsive: true,
            scrollY: 400, // Set the height of the scrollable area
            scrollCollapse: true,
            paging: false, // Disable pagination if you want only scroll
            fixedHeader: true, // Keep headers fixed while scrolling
            columnDefs: [
                { targets: [4], orderable: false }, // Make status column not sortable
                { targets: [5], orderable: false }  // Make actions column not sortable
            ]
        });
    });
</script>