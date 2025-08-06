@extends('admin.layout.layout')
@push('styles')
    <style>
        .page-header {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: #333;
            margin: 0;
        }

        .nav-tabs {
            border-bottom: 2px solid #e9ecef;
        }

        .nav-tabs .nav-link {
            border: none;
            color: #6c757d;
            font-weight: 500;
            padding: 15px 25px;
            border-radius: 0;
            border-bottom: 3px solid transparent;
            transition: all 0.3s ease;
        }

        .nav-tabs .nav-link:hover {
            border-color: transparent;
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            background: none;
            border-color: transparent transparent #007bff transparent;
            color: #007bff;
            font-weight: 600;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
        }

        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: #495057;
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }

        .action-buttons .btn {
            margin: 0 2px;
        }

        .attribute-value-item {
            margin-bottom: 10px;
        }

        .modal-content {
            border-radius: 15px;
            border: none;
        }

        .modal-header {
            border-bottom: 1px solid #e9ecef;
            border-radius: 15px 15px 0 0;
        }
    </style>
@endpush

@section('content')
    <div class="page-header">
        <h1 class="page-title">Attributes Management</h1>
        <div class="page-actions">
            <button class="btn btn-primary" data-toggle="modal" data-target="#addAttributeModal">
                <i class="fas fa-plus"></i> Add New Attribute
            </button>
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

    @if (Session::has('error_message'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error:</strong> {{ Session::get('error_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs mb-3" id="attributeTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="attributes-tab" data-toggle="tab" data-target="#attributes" type="button"
                role="tab" aria-controls="attributes" aria-selected="true">
                <i class="fas fa-list me-2"></i>Attributes
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="attribute-values-tab" data-toggle="tab" data-target="#attribute-values"
                type="button" role="tab" aria-controls="attribute-values" aria-selected="false">
                <i class="fas fa-tags me-2"></i>Attribute Values
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="attributeTabContent">
        <!-- Attributes Tab -->
        <div class="tab-pane fade show active" id="attributes" role="tabpanel" aria-labelledby="attributes-tab">
            <div class="card shadow-sm border-0">
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <table id="attributesTable" class="table table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Attribute Name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attributes as $att)
                                    <tr id="row-{{ $att->id }}">
                                        <td>{{ $att->id }}</td>
                                        <td>{{ $att->name }}</td>
                                        <td>
                                            @if ($att->status == 1)
                                                <i class="fas fa-check-circle text-success" title="Active"></i>
                                            @else
                                                <i class="fas fa-times-circle text-danger" title="Inactive"></i>
                                            @endif
                                        </td>
                                        <td>{{ $att->created_at ?? 'No Date' }}</td>
                                        <td>
                                            <div class="action-buttons d-flex gap-2 justify-content-center">
                                                <button type="button" class="btn btn-sm btn-outline-info editAttribute"
                                                    data-id="{{ $att->id }}" data-name="{{ $att->name }}"
                                                    data-status="{{ $att->status }}" title="Edit Attribute">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger deleteAttribute"
                                                    data-id="{{ $att->id }}" title="Delete Attribute">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No data found</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Attribute Values Tab -->
        <div class="tab-pane fade" id="attribute-values" role="tabpanel" aria-labelledby="attribute-values-tab">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Attribute Values</h5>
                        <button class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addAttributeValueModal">
                            <i class="fas fa-plus me-1"></i>Add Values
                        </button>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="table-responsive">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Attribute Name</th>
                                        <th>Value</th>
                                        <th>Status</th>
                                        <th>Created Date</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attributeValues as $val)
                                        <tr id="row-{{ $att->id }}">
                                            <td>{{ $val->id }}</td>
                                            <td>{{ $val->attribute->name ?? '-' }}</td>
                                            <td>{{ $val->value }}</td>
                                            <td>
                                                @if ($val->status == 1)
                                                    <i class="fas fa-check-circle text-success"></i>
                                                @else
                                                    <i class="fas fa-times-circle text-danger"></i>
                                                @endif
                                            </td>
                                            <td>{{ $val->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-info editValue"
                                                    data-id="{{ $val->id }}" data-value="{{ $val->value }}"
                                                    data-status="{{ $val->status }}"
                                                    data-attribute="{{ $val->attribute_id }}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>
                                                <form method="POST" action="" style="display:inline;"
                                                    onsubmit="return confirm('Sure to delete?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Attribute Modal -->
    <div class="modal fade" id="addAttributeModal" tabindex="-1" aria-labelledby="addAttributeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttributeModalLabel">Add New Attribute</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="attributeForm" action="{{ route('admin.attributes.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="attributeName" class="form-label">Attribute Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="attributeName" name="attribute_name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="attributeStatus" class="form-label">Status</label>
                            <select class="form-control" id="attributeStatus" name="status">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveAttribute">Save Attribute</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Attribute Value Modal -->
    <div class="modal fade" id="addAttributeValueModal" tabindex="-1" aria-labelledby="addAttributeValueModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAttributeValueModalLabel">Add Attribute Values</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <!-- Form: Save Multiple Values -->
                <form id="attributeValueForm" action="{{ route('admin.attribute-values.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="valueFormMethod" value="POST">

                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="selectAttribute" class="form-label">Select Attribute <span
                                    class="text-danger">*</span></label>
                            <select class="form-control" id="selectAttribute" name="attribute_id" required>
                                <option value="">Choose Attribute</option>
                                @foreach ($attributes as $attr)
                                    <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Attribute Values <span class="text-danger">*</span></label>
                            <div id="attributeValuesContainer">
                                <div class="attribute-value-item mb-2">
                                    <div class="d-flex align-items-center">
                                        <input type="text" class="form-control me-2" placeholder="Enter value"
                                            name="values[]" required>
                                        <button type="button" class="btn btn-outline-danger btn-sm removeValue"
                                            style="min-width: 40px;" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-outline-success btn-sm mt-2" id="addMoreValue">
                                <i class="fas fa-plus me-1"></i> Add More
                            </button>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveAttributeValues">Save Values</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- ✅ Pass Laravel routes to JavaScript properly --}}
   <script>
    // Define routes in JavaScript variables
    const routes = {
        store: "{{ route('admin.attributes.store') }}",
        update: "{{ url('/admin/attributes/update') }}/" // Base URL for update
    };
</script>

<script>
    $(document).ready(function() {
        console.log('Document ready - Initializing...');

        // Check if DataTable is loaded
        if (typeof $.fn.dataTable === 'undefined') {
            console.error('DataTables is not loaded. Please include DataTables library.');
            return;
        }

        // Initialize DataTables
        try {
            $('#attributesTable').DataTable({
                responsive: true,
                scrollY: 400,
                scrollCollapse: true,
                paging: true,
                pageLength: 10,
                fixedHeader: true,
                columnDefs: [{
                    targets: [4],
                    orderable: false
                }],
                language: {
                    emptyTable: "No attributes found",
                    zeroRecords: "No matching attributes found"
                }
            });

            $('#attributeValuesTable').DataTable({
                responsive: true,
                scrollY: 400,
                scrollCollapse: true,
                paging: true,
                pageLength: 10,
                fixedHeader: true,
                columnDefs: [{
                    targets: [5],
                    orderable: false
                }],
                language: {
                    emptyTable: "No attribute values found",
                    zeroRecords: "No matching attribute values found"
                }
            });
            
            console.log('DataTables initialized successfully');
        } catch (error) {
            console.error('Error initializing DataTables:', error);
        }

        // ============ ATTRIBUTE VALUES FUNCTIONALITY ============

        // Add More Button - Fixed field name consistency
        $(document).on('click', '#addMoreValue', function() {
            console.log('Add More button clicked');
            
            const newValueItem = `
                <div class="attribute-value-item mb-2">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control me-2" placeholder="Enter value" name="values[]" required>
                        <button type="button" class="btn btn-outline-danger btn-sm removeValue" style="min-width: 40px;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            $('#attributeValuesContainer').append(newValueItem);
            updateRemoveButtons();
            
            console.log('New field added. Total fields:', $('.attribute-value-item').length);
        });

        // Remove Value Button
        $(document).on('click', '.removeValue', function() {
            console.log('Remove button clicked');
            $(this).closest('.attribute-value-item').remove();
            updateRemoveButtons();
            console.log('Field removed. Total fields:', $('.attribute-value-item').length);
        });

        // Update Remove Buttons - Fixed logic
        function updateRemoveButtons() {
            const items = $('.attribute-value-item');
            console.log('Updating remove buttons. Total items:', items.length);
            
            if (items.length <= 1) {
                items.find('.removeValue').prop('disabled', true);
            } else {
                items.find('.removeValue').prop('disabled', false);
            }
        }

        // Attribute Values Form Submission
        $('#attributeValueForm').on('submit', function(e) {
            console.log('Attribute Values Form submitted');
            
            // Basic validation
            const attributeId = $('#selectAttribute').val();
            const valueInputs = $('input[name="values[]"]');
            let validValues = [];
            
            // Collect all non-empty values
            valueInputs.each(function() {
                const val = $(this).val().trim();
                if (val !== '') {
                    validValues.push(val);
                }
            });
            
            console.log('Selected attribute ID:', attributeId);
            console.log('Valid values:', validValues);
            
            if (!attributeId) {
                e.preventDefault();
                alert('Please select an attribute');
                $('#selectAttribute').focus();
                return false;
            }
            
            if (validValues.length === 0) {
                e.preventDefault();
                alert('Please enter at least one value');
                $('input[name="values[]"]').first().focus();
                return false;
            }
            
            console.log('Form validation passed. Submitting form...');
            // Let the form submit naturally
            return true;
        });

        // Reset value form to initial state
        function resetValueForm() {
            $('#attributeValuesContainer').html(`
                <div class="attribute-value-item mb-2">
                    <div class="d-flex align-items-center">
                        <input type="text" class="form-control me-2" placeholder="Enter value" name="values[]" required>
                        <button type="button" class="btn btn-outline-danger btn-sm removeValue" style="min-width: 40px;" disabled>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `);
            updateRemoveButtons();
            console.log('Value form reset to initial state');
        }

        // ============ ATTRIBUTE FUNCTIONALITY ============

        // ✅ FIXED: Edit Attribute with proper route handling
        $(document).on('click', '.editAttribute', function(e) {
            e.preventDefault();
            console.log('Edit Attribute button clicked');

            const id = $(this).data('id');
            const name = $(this).data('name');
            const status = $(this).data('status');

            console.log('Attribute Data:', { id, name, status });

            // Reset form first
            $('#attributeForm')[0].reset();

            // Set form action for update using the routes variable
            $('#attributeForm').attr('action', routes.update + id);

            // Set method to PUT
            $('#formMethod').val('PUT');

            // Set values
            $('#attributeName').val(name);
            $('#attributeStatus').val(status);

            // Update modal title and button text
            $('#addAttributeModalLabel').text('Edit Attribute');
            $('#saveAttribute').text('Update Attribute');

            // Show modal
            $('#addAttributeModal').modal('show');

            console.log('Form action set to:', $('#attributeForm').attr('action'));
        });

        // Attribute Form Submission
        $('#attributeForm').on('submit', function(e) {
            console.log('Attribute Form submitted');
            console.log('Form action:', $(this).attr('action'));
            console.log('Form method:', $('#formMethod').val());
            
            const attributeName = $('#attributeName').val().trim();
            if (!attributeName) {
                e.preventDefault();
                alert('Please enter attribute name');
                $('#attributeName').focus();
                return false;
            }
            
            console.log('Attribute form validation passed');
            return true;
        });

        // ============ DELETE FUNCTIONALITY ============
        
        $(document).on('click', '.deleteAttribute', function(e) {
            e.preventDefault();
            console.log('Delete Attribute button clicked');

            let id = $(this).data('id');
            let url = "{{ route('admin.attributes.delete', '__id__') }}".replace('__id__', id);

            console.log('Delete URL:', url);

            // Pehle confirm karo
            if (!confirm('Are you sure you want to delete this attribute?')) {
                console.log('Delete cancelled by user');
                return;
            }

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    console.log('Delete request started...');
                },
                success: function(response) {
                    console.log('Delete response:', response);
                    
                    if (response.status === 'confirm') {
                        // Warning dikhao: values bhi delete honge
                        let message = response.message + "\n\nProceed to delete permanently?";
                        if (confirm(message)) {
                            console.log('Force delete confirmed');
                            // Force delete
                            $.ajax({
                                url: url + '?force=1',
                                type: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}'
                                },
                                success: function(resp) {
                                    console.log('Force delete response:', resp);
                                    if (resp.status === 'success') {
                                        $('#row-' + id).fadeOut(300, function() {
                                            $(this).remove();
                                        });
                                        alert(resp.message);
                                    } else {
                                        alert('Error: ' + resp.message);
                                    }
                                },
                                error: function(xhr) {
                                    console.error('Force delete error:', xhr);
                                    alert('Something went wrong during force delete!');
                                }
                            });
                        } else {
                            console.log('Force delete cancelled');
                        }
                    } else if (response.status === 'success') {
                        // Direct delete (koi value nahi thi)
                        console.log('Direct delete successful');
                        $('#row-' + id).fadeOut(300, function() {
                            $(this).remove();
                        });
                        alert(response.message);
                    } else {
                        console.error('Unexpected response status:', response.status);
                        alert('Error: ' + response.message);
                    }
                },
                error: function(xhr) {
                    console.error('Delete request error:', xhr);
                    let msg = 'Server error occurred!';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                }
            });
        });

        // ============ STATUS TOGGLE FUNCTIONALITY ============
        
        $('.updateStatus, .updateValueStatus').on('click', function(e) {
            e.preventDefault();
            console.log('Status toggle clicked');
            
            const icon = $(this).find('i');
            if (icon.hasClass('fa-check-circle')) {
                icon.removeClass('fa-check-circle text-success').addClass('fa-times-circle text-danger');
                icon.attr('title', 'Inactive');
            } else {
                icon.removeClass('fa-times-circle text-danger').addClass('fa-check-circle text-success');
                icon.attr('title', 'Active');
            }
        });

        // ============ MODAL RESET FUNCTIONALITY ============
        
        // Reset Attribute Modal when closed
        $('#addAttributeModal').on('hidden.bs.modal', function() {
            console.log('Attribute Modal closed - resetting form');

            // Reset form
            $('#attributeForm')[0].reset();

            // Reset form action to store route
            $('#attributeForm').attr('action', routes.store);

            // Reset method to POST
            $('#formMethod').val('POST');

            // Reset UI text
            $('#addAttributeModalLabel').text('Add New Attribute');
            $('#saveAttribute').text('Save Attribute');

            console.log('Attribute form reset - action:', $('#attributeForm').attr('action'));
        });

        // Reset Attribute Value Modal when closed
        $('#addAttributeValueModal').on('hidden.bs.modal', function() {
            console.log('Attribute Value Modal closed - resetting form');
            $('#attributeValueForm')[0].reset();
            resetValueForm();
        });

        // ============ MODAL DEBUG EVENTS ============
        
        $('#addAttributeModal').on('shown.bs.modal', function() {
            console.log('Attribute Modal opened');
            console.log('Current form action:', $('#attributeForm').attr('action'));
            console.log('Current method:', $('#formMethod').val());
        });
        
        $('#addAttributeValueModal').on('shown.bs.modal', function() {
            console.log('Attribute Values Modal opened');
            console.log('Available attributes:', $('#selectAttribute option').length - 1);
        });

        // ============ EDIT VALUE FUNCTIONALITY ============
        
        $(document).on('click', '.editValue', function(e) {
            e.preventDefault();
            console.log('Edit Value button clicked');
            
            const id = $(this).data('id');
            const value = $(this).data('value');
            const status = $(this).data('status');
            const attributeId = $(this).data('attribute');
            
            console.log('Value Data:', { id, value, status, attributeId });
            
            // You can implement edit value modal here
            // For now, just log the data
            alert('Edit Value functionality - ID: ' + id + ', Value: ' + value);
        });

        // ============ INITIALIZATION ============
        
        // Initialize remove buttons on page load
        updateRemoveButtons();
        
        console.log('JavaScript initialization completed successfully');
    });
</script>
@endpush
