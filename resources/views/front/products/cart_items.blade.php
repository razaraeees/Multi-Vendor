<div class="col-12 col-xl-8">
    <div class="shop-cart-list mb-3 p-3">
        <form id="cartUpdateForm" action="{{ url('cart/update') }}" method="POST">
            @csrf
            <div id="appendCartItems">
                @forelse ($getCartItems as $item)
                    <div class="row align-items-center g-3 mb-3 cart-item-row">
                        <div class="col-12 col-lg-6">
                            <div class="d-lg-flex align-items-center gap-3">
                                <div class="cart-img text-center text-lg-start">
                                    <img src="{{ !empty($item['product']['product_image']) ? asset('front/images/product_images/small/' . $item['product']['product_image']) : asset('front/images/no-image.png') }}"
                                        width="130" alt="{{ $item['product']['product_name'] }}"
                                        class="img-fluid rounded">
                                </div>
                                <div class="cart-detail text-center text-lg-start">
                                    <h6 class="mb-2 fw-bold">{{ $item['product']['product_name'] }}</h6>
                                    <p class="mb-1 text-muted"><small>Size: <span
                                                class="fw-semibold">{{ $item['size'] ?? 'N/A' }}</span></small></p>
                                    <p class="mb-2 text-muted"><small>Color: <span
                                                class="fw-semibold">{{ $item['product']['product_color'] }}</span></small>
                                    </p>

                                    {{-- Price with discount --}}
                                    @if (isset($item['discount']) && $item['discount'] > 0)
                                        <p class="mb-1">
                                            <span class="text-muted text-decoration-line-through small">
                                                ${{ number_format($item['original_price'], 2) }}
                                            </span>
                                        </p>
                                        <h5 class="mb-0 text-primary fw-bold">
                                            ${{ number_format($item['unit_price'], 2) }}
                                        </h5>
                                        <small class="text-success">
                                            <i class="bx bx-tag"></i>
                                            Save ${{ number_format($item['discount'], 2) }}
                                        </small>
                                    @else
                                        <h5 class="mb-0 text-primary fw-bold">
                                            ${{ number_format($item['unit_price'], 2) }}
                                        </h5>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3">
                            <div class="cart-action text-center">
                                <div class="input-group justify-content-center">
                                    <input type="number" class="form-control rounded text-center fw-semibold"
                                        value="{{ $item['quantity'] }}" min="1" max="10"
                                        style="max-width: 80px;" name="items[{{ $item['id'] }}][quantity]"
                                        data-cartid="{{ $item['id'] }}">
                                    <input type="hidden" name="items[{{ $item['id'] }}][id]"
                                        value="{{ $item['id'] }}">
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Total:
                                        <strong
                                            class="item-total">${{ number_format($item['total_price'], 2) }}</strong>
                                    </small>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-lg-3">
                            <div class="text-center">
                                <div class="d-flex gap-2 justify-content-center justify-content-lg-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm deleteCartItem"
                                        data-cartid="{{ $item['id'] }}" title="Remove from cart">
                                        <i class='bx bx-trash'></i> Remove
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm"
                                        title="Add to wishlist">
                                        <i class='bx bx-heart'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr class="cart-divider opacity-25">
                @empty
                    <div class="empty-cart-state text-center py-5">
                        <div class="mb-4">
                            <i class="bx bx-cart display-1 text-muted"></i>
                        </div>
                        <h5 class="text-muted mb-3">Your cart is empty</h5>
                        <p class="text-muted mb-4">Add some items to get started shopping!</p>
                        <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                            <i class="bx bx-shopping-bag me-2"></i>Start Shopping
                        </a>
                    </div>
                @endforelse
            </div>

            @if (count($getCartItems) > 0)
                <div class="cart-actions d-flex flex-column flex-lg-row align-items-center gap-2 mt-4 pt-3 border-top">
                    <a href="{{ url('/') }}" class="btn btn-dark btn-ecomm">
                        <i class='bx bx-shopping-bag me-2'></i> Continue Shopping
                    </a>
                    <div class="ms-lg-auto d-flex gap-2">
                        <a href="{{ url('cart/clear') }}" class="btn btn-outline-danger btn-ecomm"
                            onclick="return confirm('Are you sure you want to clear your cart?')">
                            <i class='bx bx-x-circle me-2'></i> Clear Cart
                        </a>
                        <button type="submit" class="btn btn-outline-primary btn-ecomm" id="updateCartBtn">
                            <i class='bx bx-refresh me-2'></i> Update Cart
                        </button>
                    </div>
                </div>
            @endif
        </form>
    </div>
</div>

<div class="col-12 col-xl-4">
    <div class="checkout-form p-3 bg-light">
        <div class="card rounded-0 border bg-transparent shadow-none">
            <div class="card-body">
            <p class="fs-5">Apply Discount Code</p>
            
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
                <p class="mb-2">Subtotal:
                    <span class="float-end subtotal">${{ number_format($total_price, 2) }}</span>
                </p>
                <p class="mb-2">Shipping:
                    <span class="float-end">Free</span>
                </p>
                <p class="mb-2">Discount:
                    <span class="float-end">--</span>
                </p>
                <div class="my-3 border-top"></div>
                <h5 class="mb-0">Order Total:
                    <span class="float-end order-total">${{ number_format($total_price, 2) }}</span>
                </h5>
                <div class="my-4"></div>
                <div class="d-grid">
                    <a href="{{ url('/checkout') }}" class="btn btn-dark btn-ecomm">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        updateCartBtn.addEventListener('click', function() {
            let cartItems = [];

            document.querySelectorAll('input[data-cartid]').forEach(input => {
                cartItems.push({
                    id: input.getAttribute('data-cartid'),
                    quantity: input.value
                });
            });

            const url = "{{ url('cart/update') }}";

            fetch(url, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                            .getAttribute('content')
                    },
                    body: JSON.stringify({
                        items: cartItems
                    })
                })
                .then(response => response.text()) // 👈 abhi text lenge
                .then(result => {
                    console.log("🟢 Raw Response from server:", result);
                    try {
                        let json = JSON.parse(result);
                        console.log("🟢 Parsed JSON:", json);
                    } catch (e) {
                        console.error("❌ JSON parse error:", e);
                    }
                })
                .catch(error => console.error("Fetch error:", error));
        });


    });

    document.addEventListener('DOMContentLoaded', function() {
        fixMobileSticky();
    });

    window.addEventListener('resize', function() {
        fixMobileSticky();
    });

    function fixMobileSticky() {
        const checkoutForm = document.querySelector('.checkout-form');
        if (!checkoutForm) return;

        // Force remove sticky on mobile
        if (window.innerWidth < 1200) {
            checkoutForm.style.position = 'static';
            checkoutForm.style.top = 'auto';
            checkoutForm.style.marginBottom = '8rem';
            checkoutForm.style.marginTop = '2rem';

            // Add mobile class to body
            document.body.classList.add('mobile-cart-view');
        } else {
            checkoutForm.style.position = 'sticky';
            checkoutForm.style.top = '20px';
            checkoutForm.style.marginBottom = '0';
            checkoutForm.style.marginTop = '0';

            // Remove mobile class
            document.body.classList.remove('mobile-cart-view');
        }
    }

    // Add additional CSS via JavaScript
    const additionalCSS = `
<style>
@media (max-width: 1199px) {
    body.mobile-cart-view .checkout-form {
        position: static !important;
        margin-bottom: 10rem !important;
    }
    
    body.mobile-cart-view .page-content {
        padding-bottom: 5rem !important;
    }
}
</style>
`;

    document.head.insertAdjacentHTML('beforeend', additionalCSS);
</script>
