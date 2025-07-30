@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Admins Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-admin') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Admin
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

    <!-- Admins Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="admins" class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">ID</th>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Status</th>
                            <th class="pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <td class="ps-4 text-muted"><strong>#{{ $admin['id'] }}</strong></td>
                                <td>{{ $admin['name'] }}</td>
                                <td>
                                    <span
                                        class="badge bg-{{ $admin['type'] == 'superadmin' ? 'danger' : ($admin['type'] == 'admin' ? 'primary' : 'info') }} px-3 py-2">
                                        {{ ucfirst($admin['type']) }}
                                    </span>
                                </td>
                                <td>{{ $admin['mobile'] }}</td>
                                <td>{{ $admin['email'] }}</td>
                                <td>
                                    <div class="avatar">
                                        @if ($admin['image'])
                                            <img src="{{ asset('admin/images/photos/' . $admin['image']) }}"
                                                alt="Admin Image" class="rounded-circle" width="50" height="50"
                                                style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('admin/images/photos/no-image.gif') }}" alt="No Image"
                                                class="rounded-circle" width="50" height="50"
                                                style="object-fit: cover;">
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a class="updateAdminStatus" id="admin-{{ $admin['id'] }}"
                                        admin_id="{{ $admin['id'] }}" href="javascript:void(0)">
                                        @if ($admin['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;"
                                                status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;"
                                                status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                       
                                        <a href="{{ url('admin/view-vendor-details/' . $admin['id']) }}"
                                            class="btn btn-sm btn-outline-info px-2 py-1" title="View Vendor Details">
                                            <i class="fas fa-eye"></i>
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

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#admins').DataTable({
                responsive: true,
                scrollY: '400px',
                scrollCollapse: true,
                paging: true,
                pageLength: 10,
                fixedHeader: true,
                language: {
                    search: "Search Admins:",
                    lengthMenu: "Show _MENU_ admins per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ admins",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [{
                        targets: [5], // Image column (0-indexed)
                        orderable: false
                    },
                    {
                        targets: [6], // Status column
                        orderable: false
                    },
                    {
                        targets: [7], // Actions column
                        orderable: false
                    }
                ],
                order: [
                    [0, 'desc']
                ] // Sort by ID descending
            });
        });

        // Update Admin Status
        $(document).on('click', '.updateAdminStatus', function() {
            let adminId = $(this).attr('admin_id');
            let status = $(this).children('i').attr('status');

            $.ajax({
                url: "{{ url('admin/update-admin-status') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    admin_id: adminId,
                    status: status
                },
                success: function(response) {
                    if (response.status == 1) {
                        $('#admin-' + adminId).html(
                            '<i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>'
                        );
                    } else {
                        $('#admin-' + adminId).html(
                            '<i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                    alert('Something went wrong. Please try again.');
                }
            });
        });
    </script>
@endsection
