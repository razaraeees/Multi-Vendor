@extends('admin.layout.layout')

@section('content')

    <!-- Page Header -->
    <div class="page-header">
        <div class="header-info">
            <h1 class="page-title">{{ $title }}</h1>
            <p class="page-subtitle">Manage all admin accounts and their permissions.</p>
        </div>
        <div class="page-actions">
            <button class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Admin
            </button>
        </div>
    </div>

    <!-- Admins Table Card -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-dark">
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
                                    <span class="badge bg-{{ $admin['type'] == 'superadmin' ? 'danger' : ($admin['type'] == 'admin' ? 'primary' : 'info') }} px-3 py-2">
                                        {{ ucfirst($admin['type']) }}
                                    </span>
                                </td>
                                <td>{{ $admin['mobile'] }}</td>
                                <td>{{ $admin['email'] }}</td>
                                <td>
                                    <div class="avatar">
                                        @if ($admin['image'])
                                            <img src="{{ asset('admin/images/photos/' . $admin['image']) }}"
                                                 alt="Admin Image"
                                                 class="rounded-circle"
                                                 width="50"
                                                 height="50"
                                                 style="object-fit: cover;">
                                        @else
                                            <img src="{{ asset('admin/images/photos/no-image.gif') }}"
                                                 alt="No Image"
                                                 class="rounded-circle"
                                                 width="50"
                                                 height="50"
                                                 style="object-fit: cover;">
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <a class="updateAdminStatus" 
                                       id="admin-{{ $admin['id'] }}" 
                                       admin_id="{{ $admin['id'] }}" 
                                       href="javascript:void(0)">
                                        @if ($admin['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td class="pe-4">
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                        @if ($admin['type'] == 'vendor')
                                            <a href="{{ url('admin/view-vendor-details/' . $admin['id']) }}" 
                                               class="btn btn-sm btn-outline-info px-2 py-1" 
                                               title="View Vendor Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        <a href="javascript:void(0)" 
                                           class="btn btn-sm btn-outline-warning px-2 py-1" 
                                           title="Edit Admin">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="javascript:void(0)" 
                                           class="btn btn-sm btn-outline-danger px-2 py-1" 
                                           title="Delete Admin">
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
<style>

    
</style>