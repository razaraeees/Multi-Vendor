{{-- This view is rendered by ratings() method in Admin/RatingController.php --}}
@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Ratings Management</h1>
    </div>

    <!-- Success Message -->
    @if (Session::has('success_message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success:</strong> {{ Session::get('success_message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Ratings Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="ratings" class="table table-bordered dt-responsive nowrap">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>User Email</th>
                            <th>Review</th>
                            <th>Rating</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ratings as $rating)
                            <tr>
                                <td>{{ $rating['id'] }}</td>
                                <td>
                                    <a target="_blank" href="{{ url('product/' . $rating['product']['id']) }}">
                                        {{ $rating['product']['product_name'] }}
                                    </a>
                                </td>
                                <td>{{ $rating['user']['email'] }}</td>
                                <td>{{ $rating['review'] }}</td>
                                <td>{{ $rating['rating'] }}</td>
                                <td>
                                    @if ($rating['status'] == 1)
                                        <a class="updateRatingStatus" 
                                           id="rating-{{ $rating['id'] }}" 
                                           rating_id="{{ $rating['id'] }}" 
                                           href="javascript:void(0)">
                                            <i style="font-size: 22px" 
                                               class="fas fa-check-circle text-success" 
                                               status="Active"></i>
                                        </a>
                                    @else
                                        <a class="updateRatingStatus" 
                                           id="rating-{{ $rating['id'] }}" 
                                           rating_id="{{ $rating['id'] }}" 
                                           href="javascript:void(0)">
                                            <i style="font-size: 22px" 
                                               class="fas fa-times-circle text-secondary" 
                                               status="Inactive"></i>
                                        </a>
                                    @endif
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2">
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="rating" 
                                           moduleid="{{ $rating['id'] }}">
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
        $('#ratings').DataTable({
            responsive: true,
            scrollY: 400,
            scrollCollapse: true,
            paging: false,
            fixedHeader: true,
            columnDefs: [
                { targets: [6], orderable: false } // Actions column not sortable
            ]
        });
    });
</script>
