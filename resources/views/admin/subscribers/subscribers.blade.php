@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Subscribers</h1>
        <div class="page-actions">
            <a href="{{ url('admin/export-subscribers') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-download"></i> Export Subscribers
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

    <!-- Subscribers Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="subscribers" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Subscribed On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($subscribers as $subscriber)
                            <tr>
                                <td>{{ $subscriber['id'] }}</td>
                                <td>{{ $subscriber['email'] }}</td>
                                <td>{{ date('M d, Y h:i A', strtotime($subscriber['created_at'])) }}</td>
                                <td>
                                    <a href="javascript:void(0)" 
                                       class="updateSubscriberStatus" 
                                       subscriber_id="{{ $subscriber['id'] }}" 
                                       id="subscriber-{{ $subscriber['id'] }}">
                                        @if ($subscriber['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2">
                                        <a href="JavaScript:void(0)" 
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1" 
                                           module="subscriber" 
                                           moduleid="{{ $subscriber['id'] }}" 
                                           title="Delete Subscriber">
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
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function () {
    $('#subscribers').DataTable({
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

        // Status Update (AJAX)
        $(document).on('click', '.updateSubscriberStatus', function () {
            let subscriberId = $(this).attr('subscriber_id');
            let status = $(this).children('span').attr('status');

            $.ajax({
                url: "{{ url('admin/update-subscriber-status') }}",
                type: "POST",
                 {
                    _token: "{{ csrf_token() }}",
                    subscriber_id: subscriberId,
                    status: status
                },
                success: function (response) {
                    if (response.status == 1) {
                        $('#subscriber-' + subscriberId).html('<span class="badge bg-success" status="Active">Active</span>');
                    } else {
                        $('#subscriber-' + subscriberId).html('<span class="badge bg-danger" status="Inactive">Inactive</span>');
                    }
                }
            });
        });
    </script>
@endpush