{{-- This page is 'include'-ed in front/products/checkout.blade.php, and will be used by jQuery AJAX to reload it, check front/js/custom.js --}}

<h5 class="mb-4 deliveryText">
    @if (count($deliveryAddresses ?? []) > 0)
        Your Delivery Addresses
    @else
        Add Delivery Address
    @endif
</h5>

{{-- Display existing delivery addresses --}}
@if (count($deliveryAddresses ?? []) > 0)
    <div class="mb-4">
        <h6 class="mb-3">Saved Addresses:</h6>
        @foreach ($deliveryAddresses as $address)
            <div class="card border mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="card-title mb-2">{{ $address['name'] }}</h6>
                            <p class="card-text mb-1">
                                <i class='bx bx-map'></i> {{ $address['address'] }}
                            </p>
                            <p class="card-text mb-1">
                                <i class='bx bx-building'></i> {{ $address['city'] }}, {{ $address['state'] }}, {{ $address['country'] }}
                            </p>
                            <p class="card-text mb-1">
                                <i class='bx bx-map-pin'></i> {{ $address['pincode'] }}
                            </p>
                            <p class="card-text mb-2">
                                <i class='bx bx-phone'></i> {{ $address['mobile'] }}
                            </p>
                            <small class="text-info">
                                <i class='bx bx-package'></i> Shipping: EGP{{ $address['shipping_charges'] }}
                            </small>
                        </div>
                        <div class="btn-group-vertical" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary editAddress mb-1" data-addressid="{{ $address['id'] }}">
                                <i class='bx bx-edit'></i> Edit
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger removeAddress" data-addressid="{{ $address['id'] }}">
                                <i class='bx bx-trash'></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Add/Edit Address Form Toggle --}}
<div class="mb-4">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="ship-to-different-address" data-bs-toggle="collapse" data-bs-target="#showdifferent">
        @if (count($deliveryAddresses ?? []) > 0)
            <label class="form-check-label newAddress" for="ship-to-different-address">
                <i class='bx bx-plus'></i> Add New Delivery Address
            </label>
        @else
            <label class="form-check-label newAddress" for="ship-to-different-address">
                <i class='bx bx-map-pin'></i> Add Delivery Address
            </label>
        @endif
    </div>
</div>

{{-- Address Form --}}
<div class="collapse" id="showdifferent">
    <div class="card border">
        <div class="card-header">
            <h6 class="mb-0 deliveryText">
                <i class='bx bx-map-pin'></i> Add New Delivery Address
            </h6>
        </div>
        <div class="card-body">
            {{-- Note: To show the form's Validation Error Messages (Laravel's Validation Error Messages) from the AJAX call response from the server (backend), we create a <p> tag after every <input> field --}}
            <form id="deliveryForm" action="/save-delivery-address" method="POST">
                {{-- <form id="deliveryForm" action="javascript:;" method="post"> --}}
                @csrf
                <input type="hidden" name="delivery_id">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="delivery_name" class="form-label">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_name" name="delivery_name" placeholder="Enter full name">
                        <p id="delivery-delivery_name" class="text-danger small mt-1"></p>
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_mobile" class="form-label">
                            Mobile Number <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_mobile" name="delivery_mobile" placeholder="Enter mobile number">
                        <p id="delivery-delivery_mobile" class="text-danger small mt-1"></p>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="delivery_address" class="form-label">
                        Street Address <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" id="delivery_address" name="delivery_address" rows="2" placeholder="House/Flat No., Building Name, Street Name, Area"></textarea>
                    <p id="delivery-delivery_address" class="text-danger small mt-1"></p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="delivery_city" class="form-label">
                            City <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_city" name="delivery_city" placeholder="Enter city">
                        <p id="delivery-delivery_city" class="text-danger small mt-1"></p>
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_state" class="form-label">
                            State <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_state" name="delivery_state" placeholder="Enter state">
                        <p id="delivery-delivery_state" class="text-danger small mt-1"></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="delivery_country" class="form-label">
                            Country <span class="text-danger">*</span>
                        </label>
                        <select class="form-select" id="delivery_country" name="delivery_country">
                            <option value="">Select Country</option>
                            @foreach ($countries ?? [] as $country)
                                <option value="{{ $country['country_name'] }}" 
                                    @if (isset($country['country_name']) && \Illuminate\Support\Facades\Auth::check() && $country['country_name'] == \Illuminate\Support\Facades\Auth::user()->country) selected @endif>
                                    {{ $country['country_name'] }}
                                </option>
                            @endforeach
                        </select>
                        <p id="delivery-delivery_country" class="text-danger small mt-1"></p>
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_pincode" class="form-label">
                            Pincode/ZIP <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_pincode" name="delivery_pincode" placeholder="Enter pincode">
                        <p id="delivery-delivery_pincode" class="text-danger small mt-1"></p>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class='bx bx-save'></i> Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Order Notes Section --}}
<div class="mt-4">
    <div class="card border">
        <div class="card-header">
            <h6 class="mb-0">
                <i class='bx bx-note'></i> Order Notes (Optional)
            </h6>
        </div>
        <div class="card-body">
            <label for="order-notes" class="form-label">Special Instructions</label>
            <textarea class="form-control" id="order-notes" name="order_notes" rows="3" placeholder="Any special notes for delivery? e.g., preferred delivery time, gate code, etc."></textarea>
            <small class="form-text text-muted">
                <i class='bx bx-info-circle'></i> These notes will be shared with the delivery partner
            </small>
        </div>
    </div>
</div>

{{-- Navigation Buttons --}}
<div class="d-grid gap-2 d-md-flex justify-content-md-between mt-4">
    <a href="{{ url('/cart') }}" class="btn btn-light btn-ecomm">
        <i class='bx bx-chevron-left'></i> Back to Cart
    </a>
    <button type="button" class="btn btn-dark btn-ecomm" onclick="nextStep(1)">
        Continue to Shipping <i class='bx bx-chevron-right'></i>
    </button>
</div>

<script>
$(document).ready(function() {
    // Handle form submission for adding/editing address
      $('#deliveryForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                // Handle success response
                console.log(response);
                alert('Delivery address added successfully!');
            },
            error: function (xhr, status, error) {
                // Handle error response
                var errors = xhr.responseJSON.errors;
                if (errors) {
                    $.each(errors, function (key, value) {
                        $('#' + key + '-error').text(value[0]);
                    });
                } else {
                    // Display a generic error message
                    $('#generic-error').text('An unexpected error occurred.');
                }
            }
        });
    });

    // Reset form when checkbox is unchecked
    $('#ship-to-different-address').on('change', function() {
        if (!$(this).is(':checked')) {
            $('#addressAddEditForm')[0].reset();
            $('input[name="delivery_id"]').val('');
            $('.deliveryText').text('Add New Delivery Address');
            $('p[id^="delivery-"]').text('').removeClass('text-danger');
        }
    });

    // Show notification function
    function showNotification(message, type) {
        var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        var alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show mt-3" role="alert">
                <i class='bx ${type === 'success' ? 'bx-check-circle' : 'bx-error-circle'}'></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        
        // Remove existing alerts
        $('.alert').remove();
        
        // Add new alert
        $('#deliveryAddresses').prepend(alertHtml);
        
        // Auto remove after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>