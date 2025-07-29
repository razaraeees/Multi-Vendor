{{-- Note: This page (view) is rendered by the checkout() method in the Front/ProductsController.php --}}
@extends('front.layout.layout')

@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">Checkout</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i> Home</a></li>
                                <li class="breadcrumb-item"><a href="javascript:;">Shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!--end breadcrumb-->

        {{-- Showing the following HTML Form Validation Errors: (check checkout() method in Front/ProductsController.php) --}}
        {{-- Determining If An Item Exists In The Session (using has() method): https://laravel.com/docs/9.x/session#determining-if-an-item-exists-in-the-session --}}
        @if (Session::has('error_message'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error:</strong> {{ Session::get('error_message') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <!--start shop cart-->
        <section class="py-4">
            <div class="container">
                <div class="shop-cart">
                    <div class="row">
                        <div class="col-12 col-xl-8">

                            <!-- Checkout Steps -->
                            <div class="card bg-transparent rounded-0 shadow-none">
                                <div class="card-body">
                                    <div class="steps steps-light">
                                        <a class="step-item step-details active current" href="javascript:;">
                                            <div class="step-progress"><span class="step-count">1</span></div>
                                            <div class="step-label"><i class='bx bx-user-circle'></i>Details</div>
                                        </a>
                                        <a class="step-item step-shipping" href="javascript:;">
                                            <div class="step-progress"><span class="step-count">2</span></div>
                                            <div class="step-label"><i class='bx bx-cube'></i>Shipping</div>
                                        </a>
                                        <a class="step-item step-payment" href="javascript:;">
                                            <div class="step-progress"><span class="step-count">3</span></div>
                                            <div class="step-label"><i class='bx bx-credit-card'></i>Payment</div>
                                        </a>
                                        <a class="step-item step-review" href="javascript:;">
                                            <div class="step-progress"><span class="step-count">4</span></div>
                                            <div class="step-label"><i class='bx bx-check-circle'></i>Review</div>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 1: DELIVERY ADDRESS DETAILS -->
                            <div id="step-details" class="checkout-step">
                                <div class="card rounded-0">
                                    <div class="card-body">
                                        <div id="deliveryAddresses">
                                            @include('front.products.checkout_delivery')
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 2: SHIPPING METHOD -->
                            <div id="step-shipping" class="checkout-step" style="display: none;">
                                <div class="card rounded-0">
                                    <div class="card-body">
                                        <h5 class="mb-4">Select Shipping Method</h5>
                                        
                                        {{-- Display existing delivery addresses --}}
                                        @if (count($deliveryAddresses) > 0)
                                            <h6 class="mb-3">Choose Delivery Address:</h6>
                                            @foreach ($deliveryAddresses as $address)
                                                <div class="border p-3 mb-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input address-radio" type="radio" 
                                                               name="selected_address_id" 
                                                               value="{{ $address['id'] }}" 
                                                               id="address{{ $address['id'] }}"
                                                               data-shipping-charges="{{ $address['shipping_charges'] }}"
                                                               data-total-price="{{ $total_price }}"
                                                               data-coupon-amount="{{ \Illuminate\Support\Facades\Session::get('couponAmount', 0) }}"
                                                               data-cod-pincode="{{ $address['codpincodeCount'] }}"
                                                               data-prepaid-pincode="{{ $address['prepaidpincodeCount'] }}">
                                                        <label class="form-check-label" for="address{{ $address['id'] }}">
                                                            <strong>{{ $address['name'] }}</strong><br>
                                                            {{ $address['address'] }}, {{ $address['city'] }}, {{ $address['state'] }}, {{ $address['country'] }}<br>
                                                            <small class="text-muted">Mobile: {{ $address['mobile'] }}</small><br>
                                                            <small class="text-info">Shipping Charges: ${{ $address['shipping_charges'] }}</small>
                                                        </label>
                                                        <div class="mt-2">
                                                            <button type="button" class="btn btn-sm btn-outline-primary editAddress me-2" data-addressid="{{ $address['id'] }}">
                                                                <i class='bx bx-edit'></i> Edit
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-outline-danger removeAddress" data-addressid="{{ $address['id'] }}">
                                                                <i class='bx bx-trash'></i> Remove
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                            <button type="button" class="btn btn-light btn-ecomm" onclick="prevStep(2)">
                                                <i class='bx bx-chevron-left'></i> Back
                                            </button>
                                            <button type="button" class="btn btn-dark btn-ecomm" onclick="nextStep(2)">
                                                Continue to Payment <i class='bx bx-chevron-right'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 3: PAYMENT METHOD -->
                            <div id="step-payment" class="checkout-step" style="display: none;">
                                <div class="card rounded-0">
                                    <div class="card-body">
                                        <h5 class="mb-4">Payment Method</h5>
                                        
                                        <div class="border p-3 mb-3 codMethod">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_gateway" value="COD" id="cod" checked>
                                                <label class="form-check-label" for="cod">
                                                    <i class='bx bx-money'></i> <strong>Cash on Delivery</strong>
                                                    <br><small class="text-muted">Pay when your order is delivered</small>
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="border p-3 mb-3 prepaidMethod">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_gateway" value="Paypal" id="paypal">
                                                <label class="form-check-label" for="paypal">
                                                    <i class='bx bxl-paypal'></i> <strong>PayPal</strong>
                                                    <br><small class="text-muted">Pay securely with PayPal</small>
                                                </label>
                                            </div>
                                        </div>

                                        {{-- iyzico Payment Gateway integration --}}
                                        <div class="border p-3 mb-3 prepaidMethod">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_gateway" value="iyzipay" id="iyzipay">
                                                <label class="form-check-label" for="iyzipay">
                                                    <i class='bx bx-credit-card'></i> <strong>iyzipay</strong>
                                                    <br><small class="text-muted">Pay with credit/debit card</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="border p-3 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="accept" name="accept" value="Yes" required>
                                                <label class="form-check-label" for="accept">
                                                    I've read and accept the <a href="terms-and-conditions.html" class="text-primary">terms & conditions</a>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                            <button type="button" class="btn btn-light btn-ecomm" onclick="prevStep(3)">
                                                <i class='bx bx-chevron-left'></i> Back
                                            </button>
                                            <button type="button" class="btn btn-dark btn-ecomm" onclick="nextStep(3)">
                                                Continue to Review <i class='bx bx-chevron-right'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 4: REVIEW ORDER -->
                            <div id="step-review" class="checkout-step" style="display: none;">
                                <div class="card rounded-0">
                                    <div class="card-body">
                                        <h5 class="mb-4">Review Your Order</h5>
                                        
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Product</th>
                                                        <th>Size/Color</th>
                                                        <th>Price</th>
                                                        <th>Qty</th>
                                                        <th>Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="review-items">
                                                    {{-- We'll place this $total_price inside the foreach loop to calculate the total price of all products in Cart --}}
                                                    @php $total_price = 0 @endphp

                                                    @foreach ($getCartItems as $item)
                                                        @php
                                                            $getDiscountAttributePrice = \App\Models\Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img width="50" src="{{ asset('front/images/product_images/small/' . $item['product']['product_image']) }}" alt="Product" class="me-3">
                                                                    <div>
                                                                        <h6 class="mb-0">{{ $item['product']['product_name'] }}</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>{{ $item['size'] }}/{{ $item['product']['product_color'] }}</td>
                                                            <td>${{  $getDiscountAttributePrice['final_price'] }}</td>
                                                            <td>{{ $item['quantity'] }}</td>
                                                            <td>${{ $getDiscountAttributePrice['final_price'] * $item['quantity'] }}</td>
                                                        </tr>
                                                        @php $total_price = $total_price + ($getDiscountAttributePrice['final_price'] * $item['quantity']) @endphp
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="border p-3 mb-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Selected Address:</strong></p>
                                                    <div id="selected-address-display">
                                                        <!-- Will be populated by JavaScript -->
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Payment Method:</strong></p>
                                                    <div id="selected-payment-display">
                                                        <!-- Will be populated by JavaScript -->
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="border p-3 mb-3">
                                            <p class="mb-2">Subtotal: <span class="float-end">${{ number_format($total_price)  }}</span></p>
                                            <p class="mb-2">Shipping Charges: <span class="float-end shipping_charges">$0</span></p>
                                            <p class="mb-2">Coupon Discount: <span class="float-end couponAmount">
                                                @if (\Illuminate\Support\Facades\Session::has('couponAmount'))
                                                    ${{ \Illuminate\Support\Facades\Session::get('couponAmount') }}
                                                @else
                                                    $0
                                                @endif
                                            </span></p>
                                            <div class="my-3 border-top"></div>
                                            @php
                                                $grandTotal = $total_price - \Illuminate\Support\Facades\Session::get('couponAmount', 0);
                                            @endphp

                                            <h5 class="mb-0">Grand Total: 
                                                <span class="float-end grand_total">${{ number_format($grandTotal, 2) }}</span>
                                            </h5>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                            <button type="button" class="btn btn-light btn-ecomm" onclick="prevStep(4)">
                                                <i class='bx bx-chevron-left'></i> Back
                                            </button>
                                            <button type="button" class="btn btn-success btn-ecomm" id="placeOrder" onclick="placeOrder()">
                                                Place Order <i class='bx bx-lock-alt'></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- ORDER SUMMARY (Sidebar) -->
                        <div class="col-12 col-xl-4">
                            <div class="order-summary">
                                <div class="card rounded-0">
                                    <div class="card-body">
                                        <div class="card rounded-0 border bg-transparent shadow-none">
                                            <div class="card-body">
                                                <p class="fs-5">Order Summary</p>
                                                <div class="my-3 border-top"></div>
                                                <div id="summary-items">
                                                    @foreach ($getCartItems as $item)
                                                        @php
                                                            $getDiscountAttributePrice = \App\Models\Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                                                        @endphp
                                                        <div class="d-flex align-items-center mb-3">
                                                            <a class="d-block flex-shrink-0" href="{{ url('product/' . $item['product_id']) }}">
                                                                <img src="{{ asset('front/images/product_images/small/' . $item['product']['product_image']) }}" width="75" alt="Product">
                                                            </a>
                                                            <div class="ps-2">
                                                                <h6 class="mb-1"><a href="{{ url('product/' . $item['product_id']) }}" class="text-dark">{{ $item['product']['product_name'] }}</a></h6>
                                                                <div class="widget-product-meta">
                                                                    <span class="me-2">{{ $item['size'] }}/{{ $item['product']['product_color'] }}</span>
                                                                    <span class="me-2">${{ $getDiscountAttributePrice['final_price'] }}</span>
                                                                    <span class="">x {{ $item['quantity'] }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="my-3 border-top"></div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Coupon Application Section --}}
                                        <div class="card rounded-0 border bg-transparent shadow-none">
                                            <div class="card-body">
                                                <form action="{{ url('apply-coupon') }}" method="post" class="d-flex">
                                                    @csrf
                                                    <input type="text" name="coupon_code" class="form-control rounded-0" 
                                                        placeholder="Enter discount code" required>
                                                    <button class="btn btn-dark btn-ecomm rounded-0" type="submit">
                                                        Apply
                                                    </button>
                                                </form>

                                                {{-- Show success or error messages --}}
                                                @if(session('success'))
                                                    <p class="text-success mt-2">{{ session('success') }}</p>
                                                @endif
                                                @if(session('error'))
                                                    <p class="text-danger mt-2">{{ session('error') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card rounded-0 border bg-transparent mb-0 shadow-none">
                                            <div class="card-body">
                                                <p class="mb-2">Subtotal: <span class="float-end" id="summary-subtotal">${{ number_format($total_price)  }}</span></p>
                                                <p class="mb-2">Shipping: <span class="float-end" id="summary-shipping">$0</span></p>
                                                <p class="mb-0">Discount: <span class="float-end" id="summary-discount">
                                                    @if (\Illuminate\Support\Facades\Session::has('couponAmount'))
                                                        ${{ \Illuminate\Support\Facades\Session::get('couponAmount') }}
                                                    @else
                                                        $0
                                                    @endif
                                                </span></p>
                                                <div class="my-3 border-top"></div>
                                                @php
                                                    $grandTotal = $total_price - \Illuminate\Support\Facades\Session::get('couponAmount', 0);
                                                @endphp

                                                <h5 class="mb-0">Grand Total: 
                                                    <span class="float-end grand_total">${{ number_format($grandTotal, 2) }}</span>
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- JavaScript -->
   
@endsection


@section('scripts')

 {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <script>
        $(document).ready(function () {
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
            window.nextStep = function (step) {
                if (step === 1) {
                    // Validate if address is selected or added
                    if ($('#ship-to-different-address').is(':checked') && $('#addressAddEditForm').is(':visible')) {
                        // Save new address first
                        saveDeliveryAddress();
                    } else {
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
                    showStep(4);
                }
            };

            // Previous Step Function
            window.prevStep = function (step) {
                showStep(step - 1);
            };

            // Update Shipping Charges
            function updateShippingCharges() {
                var selectedAddress = $('input[name="selected_address_id"]:checked');
                if (selectedAddress.length > 0) {
                    var shippingCharges = selectedAddress.attr('data-shipping-charges');
                    var totalPrice = selectedAddress.attr('data-total-price');
                    var couponAmount = selectedAddress.attr('data-coupon-amount') || 0;
                    var codPincodeCount = selectedAddress.attr('data-cod-pincode');
                    var prepaidPincodeCount = selectedAddress.attr('data-prepaid-pincode');

                    // Update shipping charges display
                    $('.shipping_charges').text('$' + shippingCharges);
                    $('#summary-shipping').text('$' + shippingCharges);

                    // Calculate grand total
                    var grandTotal = parseFloat(totalPrice) + parseFloat(shippingCharges) - parseFloat(couponAmount);
                    $('.grand_total').text('$' + grandTotal);
                    $('#summary-total').text('$' + grandTotal);

                    // Enable/disable payment methods based on pincode availability
                    if (codPincodeCount == 0) {
                        $('.codMethod').hide();
                    } else {
                        $('.codMethod').show();
                    }

                    if (prepaidPincodeCount == 0) {
                        $('.prepaidMethod').hide();
                    } else {
                        $('.prepaidMethod').show();
                    }
                }
            }

            // Update Review Section
            function updateReviewSection() {
                // Update selected address display
                var selectedAddress = $('input[name="selected_address_id"]:checked');
                if (selectedAddress.length > 0) {
                    var addressLabel = selectedAddress.next('label').clone();
                    addressLabel.find('button').remove(); // Remove edit/delete buttons
                    $('#selected-address-display').html(addressLabel);
                }

                // Update selected payment method
                var paymentMethod = $('input[name="payment_gateway"]:checked').next('label').text();
                $('#selected-payment-display').html('<span class="badge bg-primary">' + paymentMethod + '</span>');
            }

            // Save Delivery Address via AJAX
            function saveDeliveryAddress() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var formData = $('#addressAddEditForm').serialize();
                
                $.ajax({
                    url: '{{ url("/save-delivery-address") }}',
                    type: 'POST',
                    data: formData,
                    success: function(resp) {
                        if (resp.status == 'success') {
                            // Refresh the delivery addresses section
                            $('#deliveryAddresses').html(resp.view);
                            alert('Address saved successfully!');
                            showStep(2);
                        } else {
                            alert('Error: ' + resp.message);
                        }
                    },
                    error: function(resp) {
                        if (resp.responseJSON && resp.responseJSON.errors) {
                            // Clear previous errors
                            $('p[id^="delivery-"]').text('').removeClass('text-danger');
                            
                            // Show validation errors
                            $.each(resp.responseJSON.errors, function(key, value) {
                                $('#delivery-' + key).text(value[0]).addClass('text-danger');
                            });
                        }
                    }
                });
            }

            // Address selection change event
            $(document).on('change', '.address-radio', function() {
                updateShippingCharges();
            });

            // Remove Address
            $(document).on('click', '.removeAddress', function() {
                if (confirm('Are you sure you want to remove this address?')) {
                    var addressId = $(this).attr('data-addressid');
                    
                    $.ajax({
                        url: '{{ url("/remove-delivery-address") }}/' + addressId,
                        type: 'POST',
                        data: {
                            '_token': '{{ csrf_token() }}'
                        },
                        success: function(resp) {
                            if (resp.status == 'success') {
                                $('#deliveryAddresses').html(resp.view);
                                alert('Address removed successfully!');
                            }
                        }
                    });
                }
            });

            // Apply Coupon
            $('#applyCoupon').submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(resp) {
                        if (resp.status == 'success') {
                            $('.couponAmount').text('$' + resp.couponAmount);
                            $('#summary-discount').text('$' + resp.couponAmount);
                            
                            // Recalculate grand total
                            updateShippingCharges();
                            alert('Coupon applied successfully!');
                        } else {
                            alert('Error: ' + resp.message);
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

                // Show loading
                $('#placeOrder').html('<span class="spinner-border spinner-border-sm me-2"></span>Processing...').prop('disabled', true);

                $.ajax({
                    url: '{{ url("/checkout") }}',
                    type: 'POST',
                    data: {
                        '_token': '{{ csrf_token() }}',
                        'address_id': selectedAddressId,
                        'payment_gateway': selectedPaymentMethod,
                        'accept': 'Yes'
                    },
                    success: function(resp) {
                        if (resp.status == 'success') {
                            if (resp.redirect_url) {
                                window.location.href = resp.redirect_url;
                            } else {
                                alert('Order placed successfully!');
                                window.location.href = '{{ url("/orders") }}';
                            }
                        } else {
                            alert('Error: ' + resp.message);
                            $('#placeOrder').html('Place Order <i class="bx bx-lock-alt"></i>').prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                        $('#placeOrder').html('Place Order <i class="bx bx-lock-alt"></i>').prop('disabled', false);
                    }
                });
            };

            // Initialize
            showStep(1);
        });
    </script>
@endsection    