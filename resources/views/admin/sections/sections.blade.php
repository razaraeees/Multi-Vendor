@extends('admin.layout.layout')

@section('content')
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Sections Management</h1>
        <div class="page-actions">
            <a href="{{ url('admin/add-edit-section') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add Section
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

    <!-- Sections Table -->
    <div class="card shadow-sm border">
        <div class="card-body p-3">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table id="sections" class="table table-hover align-middle mb-0">
                    <thead class="bg-light" style="position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sections as $section)
                            <tr>
                                <td>{{ $section['id'] }}</td>
                                <td>{{ $section['name'] }}</td>
                                <td>
                                    <a class="updateSectionStatus"
                                       id="section-{{ $section['id'] }}"
                                       section_id="{{ $section['id'] }}"
                                       href="javascript:void(0)">
                                        @if ($section['status'] == 1)
                                            <i class="fas fa-check-circle text-success" style="font-size: 20px;" status="Active"></i>
                                        @else
                                            <i class="fas fa-times-circle text-secondary" style="font-size: 20px;" status="Inactive"></i>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <div class="action-buttons d-flex gap-2 justify-content-center">
                                        <!-- Edit Section -->
                                        <a href="{{ url('admin/add-edit-section/' . $section['id']) }}"
                                           class="btn btn-sm btn-outline-info px-2 py-1"
                                           title="Edit Section">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>

                                        <!-- Delete Section -->
                                        <a href="JavaScript:void(0)"
                                           class="confirmDelete btn btn-sm btn-outline-danger px-2 py-1"
                                           module="section"
                                           moduleid="{{ $section['id'] }}"
                                           title="Delete Section">
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