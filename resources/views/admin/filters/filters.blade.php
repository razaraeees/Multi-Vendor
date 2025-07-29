@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Filters Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-filter') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Filter Column
            </a>
            <a href="{{ url('admin/filters-values') }}" class="btn btn-info">
                <i class="fas fa-list"></i> View Filter Values
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

    <!-- Filters Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table id="filters" class="table table-hover align-middle mb-0">
                    <thead class="bg-light" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th>ID</th>
                            <th>Filter Name</th>
                            <th>Filter Column</th>
                            <th>Categories</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($filters as $filter)
                            <tr>
                                <td>{{ $filter['id'] }}</td>
                                <td>{{ $filter['filter_name'] }}</td>
                                <td>{{ $filter['filter_column'] }}</td>
                                <td>
                                    @php
                                        $catIds = explode(',', $filter['cat_ids']);
                                        $categoryNames = [];
                                        foreach ($catIds as $catId) {
                                            $name = \App\Models\Category::getCategoryName($catId);
                                            if ($name) $categoryNames[] = $name;
                                        }
                                        echo implode(', ', $categoryNames);
                                    @endphp
                                </td>
                                <td>
                                    <a class="updateFilterStatus"
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
    $('#filters').DataTable({
        "scrollY": "500px",
        "scrollCollapse": true,
        "paging": false,
        "info": false,
        "searching": true
    });
});

</script>
@endpush