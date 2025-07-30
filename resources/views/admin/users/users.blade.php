@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Users Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/users') }}" class="btn btn-primary">
                <i class="fas fa-sync"></i> Refresh
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

    <!-- Users Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-3">
            <div class="table-responsive">
                <table id="users" class="table table-hover align-middle">
                    <thead class="bg-light" style="background-color: black; color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Address</th>
                            <th>City</th>
                            <th>State</th>
                            <th>Country</th>
                            <th>Pincode</th>
                            <th>Mobile</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user['id'] }}</td>
                                <td>{{ $user['name'] }}</td>
                                <td>{{ $user['address'] }}</td>
                                <td>{{ $user['city'] }}</td>
                                <td>{{ $user['state'] }}</td>
                                <td>{{ $user['country'] }}</td>
                                <td>{{ $user['pincode'] }}</td>
                                <td>{{ $user['mobile'] }}</td>
                                <td>{{ $user['email'] }}</td>
                                <td>
                                    <a href="javascript:void(0)" 
                                       class="updateUserStatus" 
                                       user_id="{{ $user['id'] }}" 
                                       id="user-{{ $user['id'] }}">
                                        @if ($user['status'] == 1)
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
                                           module="user" 
                                           moduleid="{{ $user['id'] }}" 
                                           title="Delete User">
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
        $('#users').DataTable({
            responsive: true,
            scrollY: 400,
            scrollCollapse: true,
            paging: false,
            fixedHeader: true,
            columnDefs: [
                { targets: [9], orderable: false }, // Status column
                { targets: [10], orderable: false } // Actions column
            ]
        });
    });

    // Status Update (AJAX)
    // $(document).on('click', '.updateUserStatus', function () {
    //     let userId = $(this).attr('user_id');
    //     let status = $(this).children('i').attr('status');

    //     $.ajax({
    //         url: "{{ url('admin/update-user-status') }}",
    //         type: "POST",
    //          {
    //             _token: "{{ csrf_token() }}",
    //             user_id: userId,
    //             status: status
    //         },
    //         success: function (response) {
    //             if (response.status == 1) {
    //                 $('#user-' + userId).html('<i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>');
    //             } else {
    //                 $('#user-' + userId).html('<i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>');
    //             }
    //         }
    //     });
    // });
</script>