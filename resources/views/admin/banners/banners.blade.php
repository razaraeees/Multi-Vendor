@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Home Page Banners</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-banner') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add Banner
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

    <!-- Banners Table -->
    <div class="card shadow-sm border">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="banners" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Type</th>
                            <th>Link</th>
                            <th>Title</th>
                            <th>Alt</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($banners as $banner)
                            <tr>
                                <td>{{ $banner['id'] }}</td>
                                <td>
                                    <img src="{{ asset('front/images/banner_images/' . $banner['image']) }}"
                                         alt="{{ $banner['alt'] }}"
                                         class="rounded"
                                         style="width: 150px; height: auto;">
                                </td>
                                <td>
                                    {{ ucfirst($banner['type']) }}
                                </td>
                                <td>{{ $banner['link'] }}</td>
                                <td>{{ $banner['title'] }}</td>
                                <td>{{ $banner['alt'] }}</td>
                                <td>
                                    <a href="javascript:void(0)" 
                                       class="updateBannerStatus" 
                                       banner_id="{{ $banner['id'] }}" 
                                       id="banner-{{ $banner['id'] }}">
                                        @if ($banner['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2">
                                        <a href="{{ url('admin/add-edit-banner/' . $banner['id']) }}" 
                                           class="btn btn-sm btn-outline-info px-2 py-1" 
                                           title="Edit Banner">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="banner" 
                                           moduleid="{{ $banner['id'] }}" 
                                           title="Delete Banner">
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
    $('#banners').DataTable({
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
