{{-- This page is 'include'-ed in front/products/checkout.blade.php --}}

<h5 class="mb-4 deliveryText">
    @if (count($deliveryAddresses ?? []) > 0)
        Your Delivery Addresses
    @else
        Add Delivery Address
    @endif
</h5>

{{-- Free Shipping Progress Bar --}}
@if (isset($deliveryAddresses) && count($deliveryAddresses) > 0)
    @php
        $deliveryAddresses = collect($deliveryAddresses); // array ko collection me convert
        $cartTotal = $deliveryAddresses->first()['cart_total'] ?? ($total_price ?? 0);
        $freeShippingThreshold =
            $deliveryAddresses->first()['free_shipping_threshold'] ?? ($freeShippingThreshold ?? 1000);
        $progressPercentage = min(100, ($cartTotal / $freeShippingThreshold) * 100);
        $amountNeeded = max(0, $freeShippingThreshold - $cartTotal);
    @endphp

    <div class="card border-info mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-2">
                <i class='bx bx-truck text-info me-2'></i>
                @if ($cartTotal >= $freeShippingThreshold)
                    <h6 class="text-success mb-0">🎉 Congratulations! You get FREE Shipping!</h6>
                @else
                    <h6 class="text-info mb-0">Add ${{ number_format($amountNeeded, 2) }} more for FREE Shipping!</h6>
                @endif
            </div>

            <div class="progress mb-2" style="height: 8px;">
                <div class="progress-bar {{ $cartTotal >= $freeShippingThreshold ? 'bg-success' : 'bg-info' }}"
                    role="progressbar" style="width: {{ $progressPercentage }}%;"
                    aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">
                </div>
            </div>

            <div class="d-flex justify-content-between small text-muted">
                <span>Cart Total: ${{ number_format($cartTotal, 2) }}</span>
                <span>Free Shipping: ${{ number_format($freeShippingThreshold, 2) }}</span>
            </div>
        </div>
    </div>
@endif

{{-- Display existing delivery addresses --}}
@if (count($deliveryAddresses ?? []) > 0)
    <div class="mb-4">
        <h6 class="mb-3">Saved Addresses:</h6>
        @foreach ($deliveryAddresses as $address)
            <div class="card border mb-3 address-card" data-address-id="{{ $address['id'] }}">
                <div class="card-body">
                    <div class="form-check">
                        <input class="form-check-input address-selector" type="radio" name="selected_address_id"
                            value="{{ $address['id'] }}" id="address{{ $address['id'] }}"
                            data-shipping-charges="{{ $address['shipping_charges'] ?? 0 }}"
                            data-total-price="{{ $total_price ?? 0 }}"
                            data-coupon-amount="{{ \Illuminate\Support\Facades\Session::get('couponAmount', 0) }}"
                            data-cod-pincode="{{ $address['codpincodeCount'] ?? 1 }}"
                            data-prepaid-pincode="{{ $address['prepaidpincodeCount'] ?? 1 }}"
                            data-address-data="{{ json_encode($address) }}">
                        <label class="form-check-label w-100" for="address{{ $address['id'] }}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="card-title mb-2">{{ $address['name'] }}</h6>
                                    <p class="card-text mb-1">
                                        <i class='bx bx-map'></i> {{ $address['address'] }}
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class='bx bx-building'></i> {{ $address['city'] }},
                                        {{ $address['state'] }}, {{ $address['country'] }}
                                    </p>
                                    <p class="card-text mb-1">
                                        <i class='bx bx-map-pin'></i> {{ $address['pincode'] }}
                                    </p>
                                    <p class="card-text mb-2">
                                        <i class='bx bx-phone'></i> {{ $address['mobile'] }}
                                    </p>

                                    {{-- Shipping Charges Display --}}
                                    <div class="shipping-info">
                                        @if (isset($address['shipping_charges']) && $address['shipping_charges'] == 0)
                                            <span class="badge bg-success">
                                                <i class='bx bx-truck'></i> FREE Shipping
                                            </span>
                                        @else
                                            <span class="badge bg-primary">
                                                <i class='bx bx-package'></i> Shipping:
                                                ${{ number_format($address['shipping_charges'] ?? 0, 2) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="btn-group-vertical ms-3" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary editAddress mb-1"
                                        data-addressid="{{ $address['id'] }}">
                                        <i class='bx bx-edit'></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger removeAddress"
                                        data-addressid="{{ $address['id'] }}">
                                        <i class='bx bx-trash'></i> Remove
                                    </button>
                                </div>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

{{-- Add/Edit Address Form Toggle --}}
<div class="mb-4">
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="ship-to-different-address" data-bs-toggle="collapse"
            data-bs-target="#showdifferent">
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
                <i class='bx bx-map-pin'></i> <span id="form-title">Add New Delivery Address</span>
            </h6>
        </div>
        <div class="card-body">
            <form id="deliveryForm" action="{{ url('/save-delivery-address') }}" method="POST">
                @csrf
                <input type="hidden" name="delivery_id" id="delivery_id">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="delivery_name" class="form-label">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_name" name="delivery_name"
                            placeholder="Enter full name">
                        <p id="delivery-delivery_name" class="text-danger small mt-1"></p>
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_mobile" class="form-label">
                            Mobile Number <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_mobile" name="delivery_mobile"
                            placeholder="Enter mobile number">
                        <p id="delivery-delivery_mobile" class="text-danger small mt-1"></p>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="delivery_address" class="form-label">
                        Street Address <span class="text-danger">*</span>
                    </label>
                    <textarea class="form-control" id="delivery_address" name="delivery_address" rows="2"
                        placeholder="House/Flat No., Building Name, Street Name, Area"></textarea>
                    <p id="delivery-delivery_address" class="text-danger small mt-1"></p>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="delivery_city" class="form-label">
                            City <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_city" name="delivery_city"
                            placeholder="Enter city">
                        <p id="delivery-delivery_city" class="text-danger small mt-1"></p>
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_state" class="form-label">
                            State <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_state" name="delivery_state"
                            placeholder="Enter state">
                        <p id="delivery-delivery_state" class="text-danger small mt-1"></p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="delivery_country" class="form-label">
                            Country <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_country" name="delivery_country"
                            value="Pakistan" readonly>
                        <p id="delivery-delivery_country" class="text-danger small mt-1"></p>
                    </div>
                    <div class="col-md-6">
                        <label for="delivery_pincode" class="form-label">
                            Pincode/ZIP <span class="text-danger">*</span>
                        </label>
                        <input class="form-control" type="text" id="delivery_pincode" name="delivery_pincode"
                            placeholder="Enter pincode">
                        <p id="delivery-delivery_pincode" class="text-danger small mt-1"></p>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary" id="saveAddressBtn">
                        <i class='bx bx-save'></i> <span id="btn-text">Save Address</span>
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
            <textarea class="form-control" id="order-notes" name="order_notes" rows="3"
                placeholder="Any special notes for delivery? e.g., preferred delivery time, gate code, etc."></textarea>
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
    <button type="button" class="btn btn-dark btn-ecomm" onclick="nextStep(1)" id="continueBtn">
        Continue to Shipping <i class='bx bx-chevron-right'></i>
    </button>
</div>

<script>
    $(document).ready(function() {
        let selectedAddressId = null;
        let selectedPaymentMethod = 'COD';
        let currentStep = 1;

        // Show Step Function
        function showStep(stepNum) {
            $('.checkout-step').hide();
            $('.step-item').removeClass('active current');

            if (stepNum === 1) {
                $('#step-details').show();
                $('.step-details').addClass('active current');
            } else if (stepNum === 2) {
                $('#step-shipping').show();
                $('.step-shipping').addClass('active current');
            } else if (stepNum === 3) {
                $('#step-payment').show();
                $('.step-payment').addClass('active current');
            } else if (stepNum === 4) {
                $('#step-review').show();
                $('.step-review').addClass('active current');
                updateReviewSection();
            }
            currentStep = stepNum;
        }

        // Next Step Function
        window.nextStep = function(step) {
            if (step === 1) {
                // Check if form is being submitted
                if ($('#ship-to-different-address').is(':checked') && $('#showdifferent').hasClass(
                    'show')) {
                    // Validate form fields
                    let isFormValid = validateAddressForm();
                    if (!isFormValid) {
                        return;
                    }
                    // Submit form and let it handle the next step
                    $('#deliveryForm').submit();
                    return;
                } else {
                    // Check if an address is selected
                    selectedAddressId = $('.address-selector:checked').val();
                    if (!selectedAddressId) {
                        alert('Please select a delivery address or add a new one.');
                        return;
                    }
                    updateShippingCharges();
                    showStep(2);
                }
            } else if (step === 2) {
                // Validate address selection
                selectedAddressId = $('input[name="selected_address_id"]:checked').val();
                if (!selectedAddressId) {
                    alert('Please select a delivery address.');
                    return;
                }
                updateShippingCharges();
                showStep(3);
            } else if (step === 3) {
                // Validate payment method and terms
                selectedPaymentMethod = $('input[name="payment_gateway"]:checked').val();
                if (!$('#accept').is(':checked')) {
                    alert('Please accept the terms and conditions.');
                    return;
                }
                updateShippingCharges();
                showStep(4);
            }
        };

        // Previous Step Function
        window.prevStep = function(step) {
            showStep(step - 1);
        };

        // Validate Address Form
        function validateAddressForm() {
            let isValid = true;
            const requiredFields = ['delivery_name', 'delivery_mobile', 'delivery_address', 'delivery_city',
                'delivery_state', 'delivery_pincode'
            ];

            // Clear previous errors
            $('p[id^="delivery-"]').text('').removeClass('text-danger');

            requiredFields.forEach(function(field) {
                const value = $('#' + field).val().trim();
                if (!value) {
                    $('#delivery-' + field).text('This field is required.').addClass('text-danger');
                    isValid = false;
                }
            });

            return isValid;
        }

        // Update Shipping Charges Function
        function updateShippingCharges() {
            var selectedAddress = $('input[name="selected_address_id"]:checked, .address-selector:checked');

            if (selectedAddress.length > 0) {
                var shippingCharges = parseFloat(selectedAddress.attr('data-shipping-charges')) || 0;
                var totalPrice = parseFloat(selectedAddress.attr('data-total-price')) || parseFloat($(
                    '#summary-subtotal').text().replace(/[$,]/g, '')) || 0;
                var couponAmount = parseFloat(selectedAddress.attr('data-coupon-amount')) || parseFloat($(
                    '.couponAmount').text().replace(/[$,]/g, '')) || 0;
                var codPincodeCount = selectedAddress.attr('data-cod-pincode');
                var prepaidPincodeCount = selectedAddress.attr('data-prepaid-pincode');

                console.log('Shipping Calculation:', {
                    shippingCharges: shippingCharges,
                    totalPrice: totalPrice,
                    couponAmount: couponAmount
                });

                // Update shipping charges display
                $('.shipping_charges').text('$' + shippingCharges.toFixed(2));
                $('#summary-shipping').text('$' + shippingCharges.toFixed(2));

                // Calculate grand total
                var grandTotal = totalPrice + shippingCharges - couponAmount;
                $('.grand_total').text('$' + grandTotal.toFixed(2));

                // Enable/disable payment methods based on pincode availability
                if (codPincodeCount == 0) {
                    $('.codMethod').hide();
                    $('#cod').prop('checked', false);
                } else {
                    $('.codMethod').show();
                }

                if (prepaidPincodeCount == 0) {
                    $('.prepaidMethod').hide();
                    if ($('input[name="payment_gateway"]:checked').val() !== 'COD') {
                        $('#paypal').prop('checked', true);
                    }
                } else {
                    $('.prepaidMethod').show();
                }

                console.log('Updated Grand Total:', grandTotal);
            } else {
                console.log('No address selected for shipping calculation');
            }
        }

        // Update Review Section
        function updateReviewSection() {
            // Update selected address display
            var selectedAddress = $('input[name="selected_address_id"]:checked, .address-selector:checked');
            if (selectedAddress.length > 0) {
                var addressLabel = selectedAddress.next('label').clone();
                addressLabel.find('button, .btn-group-vertical').remove();
                $('#selected-address-display').html(addressLabel);
                updateShippingCharges();
            }

            // Update selected payment method
            var paymentMethod = $('input[name="payment_gateway"]:checked').next('label').find('strong')
            .text() ||
                $('input[name="payment_gateway"]:checked').next('label').text();
            $('#selected-payment-display').html('<span class="badge bg-primary">' + paymentMethod + '</span>');
        }

        // Address selection change event
        $(document).on('change', '.address-radio, .address-selector', function() {
            selectedAddressId = $(this).val();

            // Remove selected styling from all cards
            $('.address-card, .border').removeClass('border-primary');

            // Add selected styling to current card
            $(this).closest('.address-card, .border').addClass('border-primary');

            // Update continue button state
            $('#continueBtn').removeClass('btn-secondary').addClass('btn-dark').prop('disabled', false);

            // Update shipping immediately
            updateShippingCharges();

            console.log('Address selected:', selectedAddressId);
        });

        // Handle address form submission
        $(document).on('submit', '#deliveryForm', function(e) {
            e.preventDefault();

            if (!validateAddressForm()) {
                return false;
            }

            $('#saveAddressBtn').html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Saving...').prop(
                'disabled', true);

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.type === 'success') {
                        // Update the entire delivery addresses section
                        $('#deliveryAddresses').html(response.view);

                        // Auto-select the newly added/updated address
                        setTimeout(function() {
                            var newAddress = $('.address-selector:last');
                            if (newAddress.length) {
                                newAddress.prop('checked', true).trigger('change');
                                selectedAddressId = newAddress.val();
                            }

                            // Move to next step automatically
                            showStep(2);
                        }, 500);

                        showNotification('Address saved successfully!', 'success');
                    } else if (response.type === 'error' && response.errors) {
                        $.each(response.errors, function(key, value) {
                            $('#delivery-' + key).text(value[0]).addClass(
                                'text-danger');
                        });
                    }
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            $('#delivery-' + key).text(value[0]).addClass(
                                'text-danger');
                        });
                    } else {
                        showNotification('An error occurred while saving the address',
                            'error');
                    }
                },
                complete: function() {
                    $('#saveAddressBtn').html(
                        '<i class="bx bx-save"></i> <span id="btn-text">Save Address</span>'
                        ).prop('disabled', false);
                }
            });
        });

        // Edit Address
        $(document).on('click', '.editAddress', function() {
            var addressId = $(this).attr('data-addressid');
            var addressCard = $(this).closest('.address-card');
            var addressData = JSON.parse(addressCard.find('.address-selector').attr(
                'data-address-data'));

            // Fill form with address data
            $('#delivery_id').val(addressData.id);
            $('#delivery_name').val(addressData.name);
            $('#delivery_mobile').val(addressData.mobile);
            $('#delivery_address').val(addressData.address);
            $('#delivery_city').val(addressData.city);
            $('#delivery_state').val(addressData.state);
            $('#delivery_country').val(addressData.country);
            $('#delivery_pincode').val(addressData.pincode);

            // Update form labels
            $('#form-title').text('Edit Delivery Address');
            $('#btn-text').text('Update Address');

            // Show form
            $('#ship-to-different-address').prop('checked', true);
            $('#showdifferent').collapse('show');

            // Scroll to form
            $('html, body').animate({
                scrollTop: $("#showdifferent").offset().top - 100
            }, 500);
        });

        // Remove Address
        $(document).on('click', '.removeAddress', function() {
            if (confirm('Are you sure you want to remove this address?')) {
                var addressId = $(this).attr('data-addressid');
                var button = $(this);

                button.html('<span class="spinner-border spinner-border-sm"></span>').prop('disabled',
                    true);

                $.ajax({
                    url: '/remove-delivery-address',
                    type: 'POST',
                    data: {
                        '_token': $('meta[name="csrf-token"]').attr('content'),
                        'addressid': addressId
                    },
                    success: function(resp) {
                        if (resp.type === 'success') {
                            $('#deliveryAddresses').html(resp.view);
                            showNotification('Address removed successfully!', 'success');

                            // Reset selected address if it was the removed one
                            if (selectedAddressId == addressId) {
                                selectedAddressId = null;
                                updateShippingCharges();
                            }
                        } else {
                            showNotification('Error removing address', 'error');
                            button.html('<i class="bx bx-trash"></i> Remove').prop(
                                'disabled', false);
                        }
                    },
                    error: function() {
                        showNotification('Error removing address', 'error');
                        button.html('<i class="bx bx-trash"></i> Remove').prop('disabled',
                            false);
                    }
                });
            }
        });

        // Apply Coupon
        $('form[action*="apply-coupon"]').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: function(resp) {
                    if (resp.status == 'success') {
                        $('.couponAmount, #summary-discount').text('$' + resp.couponAmount);

                        // Update all address data attributes
                        $('.address-selector').each(function() {
                            $(this).attr('data-coupon-amount', resp.couponAmount);
                        });

                        // Recalculate grand total
                        updateShippingCharges();
                        showNotification('Coupon applied successfully!', 'success');
                    } else {
                        showNotification('Error: ' + resp.message, 'error');
                    }
                }
            });
        });

        // Place Order
        window.placeOrder = function() {
            if (!selectedAddressId) {
                alert('Please select a delivery address.');
                return;
            }

            if (!selectedPaymentMethod) {
                alert('Please select a payment method.');
                return;
            }

            if (!$('#accept').is(':checked')) {
                alert('Please accept the terms and conditions.');
                return;
            }

            $('#placeOrder').html(
                '<span class="spinner-border spinner-border-sm me-2"></span>Processing...').prop(
                'disabled', true);

            $.ajax({
                url: '/checkout',
                type: 'POST',
                data: {
                    '_token': $('meta[name="csrf-token"]').attr('content'),
                    'address_id': selectedAddressId,
                    'payment_gateway': selectedPaymentMethod,
                    'accept': 'Yes'
                },
                success: function(resp) {
                    if (resp.status == 'success') {
                        if (resp.redirect_url) {
                            window.location.href = resp.redirect_url;
                        } else {
                            showNotification('Order placed successfully!', 'success');
                            window.location.href = '/orders';
                        }
                    } else {
                        showNotification('Error: ' + resp.message, 'error');
                        $('#placeOrder').html('Place Order <i class="bx bx-lock-alt"></i>')
                            .prop('disabled', false);
                    }
                },
                error: function() {
                    showNotification('Something went wrong. Please try again.', 'error');
                    $('#placeOrder').html('Place Order <i class="bx bx-lock-alt"></i>').prop(
                        'disabled', false);
                }
            });
        };

        // Show notification function
        function showNotification(message, type) {
            var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            var iconClass = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';

            var alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show mt-3" role="alert">
                <i class='bx ${iconClass}'></i> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

            $('.alert').remove();
            $('body').prepend(alertHtml);

            setTimeout(function() {
                $('.alert').fadeOut();
            }, 5000);
        }

        // Initialize on page load
        function initialize() {
            // Check if there's already a selected address
            var preSelectedAddress = $('.address-selector:checked, .address-radio:checked');
            if (preSelectedAddress.length > 0) {
                selectedAddressId = preSelectedAddress.val();
                updateShippingCharges();
            }

            // Auto-select first address if none selected and addresses exist
            if (!selectedAddressId && $('.address-selector').length > 0) {
                $('.address-selector:first').prop('checked', true).trigger('change');
            }
        }

        // Initialize everything
        initialize();
        showStep(1);
    });
</script>
