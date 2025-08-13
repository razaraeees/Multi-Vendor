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
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i>
                                        Home</a></li>
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
                                            {{-- Address Card with Proper Data Attributes --}}
                                            @foreach ($deliveryAddresses as $address)
                                                <div class="card border mb-3 address-card"
                                                    data-address-id="{{ $address['id'] }}">
                                                    <div class="card-body">
                                                        <div class="form-check">
                                                            <input class="form-check-input address-selector" type="radio"
                                                                name="selected_address_id" value="{{ $address['id'] }}"
                                                                id="address{{ $address['id'] }}"
                                                                data-shipping-charges="{{ $address['shipping_charges'] ?? 0 }}"
                                                                data-total-price="{{ $total_price ?? 0 }}"
                                                                data-coupon-amount="{{ \Illuminate\Support\Facades\Session::get('couponAmount', 0) }}"
                                                                data-cod-pincode="{{ $address['codpincodeCount'] ?? 1 }}"
                                                                data-prepaid-pincode="{{ $address['prepaidpincodeCount'] ?? 1 }}"
                                                                data-address-data="{{ json_encode($address) }}">

                                                            <label class="form-check-label w-100"
                                                                for="address{{ $address['id'] }}">
                                                                <div
                                                                    class="d-flex justify-content-between align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <h6 class="card-title mb-2">{{ $address['name'] }}
                                                                        </h6>
                                                                        <p class="card-text mb-1">
                                                                            <i class='bx bx-map'></i>
                                                                            {{ $address['address'] }}
                                                                        </p>
                                                                        <p class="card-text mb-1">
                                                                            <i class='bx bx-building'></i>
                                                                            {{ $address['city'] }},
                                                                            {{ $address['state'] }},
                                                                            {{ $address['country'] }}
                                                                        </p>
                                                                        <p class="card-text mb-1">
                                                                            <i class='bx bx-map-pin'></i>
                                                                            {{ $address['pincode'] }}
                                                                        </p>
                                                                        <p class="card-text mb-2">
                                                                            <i class='bx bx-phone'></i>
                                                                            {{ $address['mobile'] }}
                                                                        </p>

                                                                        {{-- Shipping Charges Display --}}
                                                                        <div class="shipping-info">
                                                                            @if (isset($address['shipping_charges']) && $address['shipping_charges'] == 0)
                                                                                <span class="badge bg-success">
                                                                                    <i class='bx bx-truck'></i> FREE
                                                                                    Shipping
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
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-primary editAddress mb-1"
                                                                            data-addressid="{{ $address['id'] }}">
                                                                            <i class='bx bx-edit'></i> Edit
                                                                        </button>
                                                                        <button type="button"
                                                                            class="btn btn-sm btn-outline-danger removeAddress"
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
                                                <input class="form-check-input" type="radio" name="payment_gateway"
                                                    value="COD" id="cod" checked>
                                                <label class="form-check-label" for="cod">
                                                    <i class='bx bx-money'></i> <strong>Cash on Delivery</strong>
                                                    <br><small class="text-muted">Pay when your order is delivered</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="border p-3 mb-3 prepaidMethod">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_gateway"
                                                    value="Paypal" id="paypal">
                                                <label class="form-check-label" for="paypal">
                                                    <i class='bx bxl-paypal'></i> <strong>PayPal</strong>
                                                    <br><small class="text-muted">Pay securely with PayPal</small>
                                                </label>
                                            </div>
                                        </div>

                                        {{-- iyzico Payment Gateway integration --}}
                                        <div class="border p-3 mb-3 prepaidMethod">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="payment_gateway"
                                                    value="iyzipay" id="iyzipay">
                                                <label class="form-check-label" for="iyzipay">
                                                    <i class='bx bx-credit-card'></i> <strong>iyzipay</strong>
                                                    <br><small class="text-muted">Pay with credit/debit card</small>
                                                </label>
                                            </div>
                                        </div>

                                        <div class="border p-3 mb-3">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input" id="accept"
                                                    name="accept" value="Yes" required>
                                                <label class="form-check-label" for="accept">
                                                    I've read and accept the <a href="terms-and-conditions.html"
                                                        class="text-primary">terms & conditions</a>
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
                                                    @php
                                                        $finalPrice = $getDiscountAttributePrice['final_price'] ?? 0;
                                                        $qty = $item['quantity'] ?? 0;
                                                    @endphp

                                                    {{-- We'll place this $total_price inside the foreach loop to calculate the total price of all products in Cart --}}
                                                    @php $total_price = 0 @endphp

                                                    @foreach ($getCartItems as $item)
                                                        @php
                                                            // Ensure attributes is always an array
                                                            $attributes = is_array($item['attributes'] ?? null)
                                                                ? $item['attributes']
                                                                : [];

                                                            $getDiscountAttributePrice = \App\Models\Product::getDiscountAttributePrice(
                                                                $item['product_id'],
                                                                $attributes,
                                                            );
                                                        @endphp

                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div>
                                                                        <img src="{{ asset('front/images/product_images/' . ($item->product->images[0]->image ?? 'no-image.jpg')) }}"
                                                                        width="50" alt="Product">
                                                                    </div>
                                                                    <div>
                                                                        <h6 class="mb-0">
                                                                            {{ $item['product']['product_name'] ?? 'Unknown Product' }}
                                                                        </h6>
                                                                    </div>
                                                                </div>
                                                            </td>

                                                            <td>
                                                                @if (!empty($attributes))
                                                                    @foreach ($attributes as $attr)
                                                                        {{ $attr['name'] ?? '' }}:
                                                                        {{ $attr['value'] ?? '' }}<br>
                                                                    @endforeach
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>

                                                            {{-- <pre>{{ print_r($getDiscountAttributePrice, true) }}</pre> --}}


                                                            <td>
                                                                ${{ number_format($getDiscountAttributePrice['final_price'] ?? 0, 2) }}
                                                            </td>
                                                            <td>{{ $item['quantity'] ?? 0 }}</td>
                                                            <td>
                                                                ${{ number_format(($getDiscountAttributePrice['final_price'] ?? 0) * ($item['quantity'] ?? 0), 2) }}
                                                            </td>
                                                            {{-- <script>
                                                                console.log('Debug getDiscountAttributePrice:', @json($getDiscountAttributePrice));
                                                            </script> --}}
                                                        </tr>

                                                        @php
                                                            $total_price +=
                                                                ($getDiscountAttributePrice['final_price'] ?? 0) *
                                                                ($item['quantity'] ?? 0);
                                                        @endphp
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
                                        @php
                                            $deliveryAddresses = collect($deliveryAddresses); // array ko collection me convert
                                            $cartTotal =
                                                $deliveryAddresses->first()['cart_total'] ?? ($total_price ?? 0);
                                            $freeShippingThreshold =
                                                $deliveryAddresses->first()['free_shipping_threshold'] ??
                                                ($freeShippingThreshold ?? 1000);
                                            $progressPercentage = min(100, ($cartTotal / $freeShippingThreshold) * 100);
                                            $amountNeeded = max(0, $freeShippingThreshold - $cartTotal);
                                        @endphp
                                        <div class="border p-3 mb-3">
                                            <p class="mb-2">Subtotal: <span
                                                    class="float-end">${{ number_format($cartTotal) }}</span></p>
                                            <p class="mb-2">Shipping Charges: <span
                                                    class="float-end shipping_charges">$0</span></p>
                                            <p class="mb-2">Coupon Discount: <span class="float-end couponAmount">
                                                    @if (\Illuminate\Support\Facades\Session::has('couponAmount'))
                                                        ${{ \Illuminate\Support\Facades\Session::get('couponAmount') }}
                                                    @else
                                                        $0
                                                    @endif
                                                </span></p>
                                            <div class="my-3 border-top"></div>
                                            @php
                                                $grandTotal =
                                                    $total_price -
                                                    \Illuminate\Support\Facades\Session::get('couponAmount', 0);
                                            @endphp

                                            <h5 class="mb-0">Grand Total:
                                                <span
                                                    class="float-end grand_total">${{ number_format($grandTotal, 2) }}</span>
                                            </h5>
                                        </div>

                                        <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                            <button type="button" class="btn btn-light btn-ecomm" onclick="prevStep(4)">
                                                <i class='bx bx-chevron-left'></i> Back
                                            </button>
                                            <button type="button" class="btn btn-success btn-ecomm" id="placeOrder"
                                                onclick="placeOrder()">
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
                                                            $getDiscountAttributePrice = \App\Models\Product::getDiscountAttributePrice(
                                                                $item['product_id'],
                                                                $item['size'],
                                                            );
                                                        @endphp
                                                        <div class="d-flex align-items-center mb-3">
                                                            <a class="d-block flex-shrink-0"
                                                                href="{{ url('product/' . $item['product_id']) }}">
                                                                <img src="{{ asset('front/images/product_images/' . ($item->product->images[0]->image ?? 'no-image.jpg')) }}"
                                                                    width="75" alt="Product">
                                                            </a>
                                                            <div class="ps-2">
                                                                <h6 class="mb-1"><a
                                                                        href="{{ url('product/' . $item['product_id']) }}"
                                                                        class="text-dark">{{ $item['product']['product_name'] }}</a>
                                                                </h6>
                                                                <div class="widget-product-meta">
                                                                    <span
                                                                        class="me-2">{{ $item['size'] }}/{{ $item['product']['product_color'] }}</span>
                                                                    <span
                                                                        class="me-2">${{ $getDiscountAttributePrice['final_price'] }}</span>
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
                                                    <input type="text" name="coupon_code"
                                                        class="form-control rounded-0" placeholder="Enter discount code"
                                                        required>
                                                    <button class="btn btn-dark btn-ecomm rounded-0" type="submit">
                                                        Apply
                                                    </button>
                                                </form>

                                                {{-- Show success or error messages --}}
                                                @if (session('success'))
                                                    <p class="text-success mt-2">{{ session('success') }}</p>
                                                @endif
                                                @if (session('error'))
                                                    <p class="text-danger mt-2">{{ session('error') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card rounded-0 border bg-transparent mb-0 shadow-none">
                                            <div class="card-body">
                                                <p class="mb-2">Subtotal: <span class="float-end"
                                                        id="summary-subtotal">${{ number_format($cartTotal) }}</span>
                                                </p>
                                                <p class="mb-2">Shipping: <span class="float-end"
                                                        id="summary-shipping">$</span></p>
                                                <p class="mb-0">Discount: <span class="float-end"
                                                        id="summary-discount">
                                                        @if (\Illuminate\Support\Facades\Session::has('couponAmount'))
                                                            ${{ \Illuminate\Support\Facades\Session::get('couponAmount') }}
                                                        @else
                                                            $0
                                                        @endif
                                                    </span></p>
                                                <div class="my-3 border-top"></div>
                                                @php
                                                    $grandTotal =
                                                        $total_price -
                                                        \Illuminate\Support\Facades\Session::get('couponAmount', 0);
                                                @endphp

                                                <h5 class="mb-0">Grand Total:
                                                    <span
                                                        class="float-end grand_total">${{ number_format($grandTotal, 2) }}</span>
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
                    // Validate if address is selected or form is filled
                    if ($('#ship-to-different-address').is(':checked') && $('#showdifferent').hasClass(
                            'show')) {
                        // If form is open, save the address first
                        $('#deliveryForm').submit();
                        return; // Let the form submission handle the next step
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
                    updateShippingCharges(); // Update one more time before review
                    showStep(4);
                }
            };

            // Previous Step Function
            window.prevStep = function(step) {
                showStep(step - 1);
            };

            // ✅ FIXED: Update Shipping Charges Function
            function updateShippingCharges() {
                var selectedAddress = $('input[name="selected_address_id"]:checked');
                if (selectedAddress.length === 0) {
                    // Try to get from address-selector class (from checkout_delivery.blade.php)
                    selectedAddress = $('.address-selector:checked');
                }

                if (selectedAddress.length > 0) {
                    var shippingCharges = parseFloat(selectedAddress.attr('data-shipping-charges')) || 0;
                    var totalPrice = parseFloat(selectedAddress.attr('data-total-price')) || parseFloat($(
                        '#summary-subtotal').text().replace('$', '').replace(',', '')) || 0;
                    var couponAmount = parseFloat(selectedAddress.attr('data-coupon-amount')) || parseFloat($(
                        '.couponAmount').text().replace('$', '').replace(',', '')) || 0;
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

                    // Update sidebar summary if exists
                    if ($('#summary-total').length) {
                        $('#summary-total').text('$' + grandTotal.toFixed(2));
                    }

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
                    addressLabel.find('button, .btn-group-vertical').remove(); // Remove edit/delete buttons
                    $('#selected-address-display').html(addressLabel);

                    // Ensure shipping is updated in review
                    updateShippingCharges();
                }

                // Update selected payment method
                var paymentMethod = $('input[name="payment_gateway"]:checked').next('label').find('strong')
                    .text() ||
                    $('input[name="payment_gateway"]:checked').next('label').text();
                $('#selected-payment-display').html('<span class="badge bg-primary">' + paymentMethod + '</span>');
            }

            // ✅ FIXED: Address selection change event (handles both checkout steps)
            $(document).on('change', '.address-radio, .address-selector', function() {
                selectedAddressId = $(this).val();

                // Remove selected styling from all cards
                $('.address-card, .border').removeClass('border-primary');

                // Add selected styling to current card
                $(this).closest('.address-card, .border').addClass('border-primary');

                // Update shipping immediately
                updateShippingCharges();

                console.log('Address selected:', selectedAddressId);
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
                                alert('Address removed successfully!');

                                // Reset selected address if it was the removed one
                                if (selectedAddressId == addressId) {
                                    selectedAddressId = null;
                                    updateShippingCharges();
                                }
                            } else {
                                alert('Error removing address');
                                button.html('<i class="bx bx-trash"></i> Remove').prop(
                                    'disabled', false);
                            }
                        },
                        error: function() {
                            alert('Error removing address');
                            button.html('<i class="bx bx-trash"></i> Remove').prop('disabled',
                                false);
                        }
                    });
                }
            });

            // ✅ FIXED: Handle address form submission from checkout_delivery.blade.php
            $(document).on('submit', '#deliveryForm', function(e) {
                e.preventDefault();

                $('#saveAddressBtn').html(
                    '<span class="spinner-border spinner-border-sm me-2"></span>Saving...').prop(
                    'disabled', true);
                $('p[id^="delivery-"]').text('').removeClass('text-danger');

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

                            alert('Address saved successfully!');
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
                            alert('An error occurred while saving the address');
                        }
                    },
                    complete: function() {
                        $('#saveAddressBtn').html(
                            '<i class="bx bx-save"></i> <span id="btn-text">Save Address</span>'
                        ).prop('disabled', false);
                    }
                });
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
                                alert('Order placed successfully!');
                                window.location.href = '/orders';
                            }
                        } else {
                            alert('Error: ' + resp.message);
                            $('#placeOrder').html('Place Order <i class="bx bx-lock-alt"></i>')
                                .prop('disabled', false);
                        }
                    },
                    error: function() {
                        alert('Something went wrong. Please try again.');
                        $('#placeOrder').html('Place Order <i class="bx bx-lock-alt"></i>').prop(
                            'disabled', false);
                    }
                });
            };

            // ✅ ADDED: Initialize shipping calculation on page load
            $(document).ready(function() {
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
            });

            // Initialize
            showStep(1);
        });
    </script>
@endsection
