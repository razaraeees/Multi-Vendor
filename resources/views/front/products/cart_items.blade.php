<!-- resources/views/front/products/cart_items.blade.php -->
<!-- resources/views/front/products/cart_items.blade.php -->
<style>
    .btn-ecomm {
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 120px; /* Optional: minimum width */
}

/* Ya phir specific class bana kar */
.cart-action-buttons {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

.cart-action-buttons .btn {
    height: 45px;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>

@if ($getCartItems->isEmpty())
    <div class="col-12">
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
    </div>
@else
    <div class="col-12 col-xl-8">
        <div class="shop-cart-list mb-3 p-3">
            <div id="appendCartItems">
                @foreach ($getCartItems as $item)
                    @if (!$item->product)
                        @continue
                    @endif

                    @php
                        $item->product->attributes = $item->product->attributes ?? collect();
                    @endphp

                    <div class="row align-items-center g-3 mb-3 cart-item-row">
                        <div class="col-12 col-lg-6">
                            <div class="d-lg-flex align-items-center gap-3">
                                <!-- Image -->
                                <div class="cart-img text-center text-lg-start">
                                    <img src="{{ asset('front/images/product_images/' . ($item->product->images[0]->image ?? 'no-image.jpg')) }}"
                                        width="130" alt="{{ $item->product->product_name }}"
                                        class="img-fluid rounded">
                                </div>

                                <!-- Details -->
                                <div class="cart-detail text-center text-lg-start">
                                    <h6 class="mb-2 fw-bold">{{ $item->product->product_name }}</h6>

                                    <!-- Attributes -->
                                    @if (!empty($item->attributes_list))
                                        @foreach ($item->attributes_list as $attr)
                                            @php
                                                $value = $item->product->attributes
                                                    ->where('id', $attr['attribute_value_id'])
                                                    ->first();
                                            @endphp
                                            @if ($value)
                                                <p class="mb-1 text-muted">
                                                    <small>{{ $value->attribute->name }}:
                                                        <span class="fw-semibold">{{ $value->value }}</span>
                                                    </small>
                                                </p>
                                            @endif
                                        @endforeach
                                    @endif

                                    <!-- Price -->
                                    @if ($item['discount'] > 0)
                                        <p class="mb-1">
                                            <span class="text-muted text-decoration-line-through small">
                                                Rs {{ number_format($item['original_price'], 2) }}
                                            </span>
                                        </p>
                                        <h5 class="mb-0 text-primary fw-bold">
                                            Rs {{ number_format($item['unit_price'], 2) }}
                                        </h5>
                                        <small class="text-success">
                                            <i class="bx bx-tag"></i>
                                            Save Rs {{ number_format($item['discount'], 2) }}
                                        </small>
                                    @else
                                        <h5 class="mb-0 text-primary fw-bold">
                                            Rs {{ number_format($item['unit_price'], 2) }}
                                        </h5>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div class="col-12 col-lg-3">
                            <div class="cart-action text-center">
                                <div class="input-group justify-content-center">
                                    <input type="number" class="form-control rounded text-center fw-semibold"
                                        value="{{ $item->quantity }}" min="1" max="10"
                                        style="max-width: 80px;" data-cartid="{{ $item->id }}">
                                    {{-- <div class="mt-2">
                                        <small class="text-muted">Total:
                                            <strong class="item-total">Rs
                                                {{ number_format($item['total_price'], 2) }}</strong>
                                        </small>
                                    </div> --}}
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-12 col-lg-3">
                            <div class="text-center">
                                <div class="d-flex gap-2 justify-content-center justify-content-lg-end">
                                    <button type="button" class="btn btn-outline-danger btn-sm deleteCartItem"
                                        data-cartid="{{ $item->id }}" title="Remove from cart">
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
                @endforeach
            </div>

            <!-- Cart Actions -->
            <div class="cart-actions d-flex flex-column flex-lg-row align-items-center gap-2 mt-4 pt-3 border-top">
                <a href="{{ url('/') }}" class="btn btn-dark btn-ecomm">
                    <i class='bx bx-shopping-bag me-2'></i> Continue Shopping
                </a>
                <div class="ms-lg-auto d-flex gap-2 align-items-stretch">
    <a href="{{ url('cart/clear') }}" 
       class="btn btn-danger btn-ecomm px-3"
       onclick="return confirm('Are you sure you want to clear your cart?')">
        <i class='bx bx-x-circle me-2'></i> Clear Cart
    </a>
    
    <form method="POST" action="{{ url('cart/update') }}" id="updateCartForm" class="d-flex">
        @csrf
        @foreach ($getCartItems as $index => $cart)
            <input type="hidden" name="items[{{ $index }}][id]" value="{{ $cart->id }}"
                class="cart-item-id">
            <input type="hidden" name="items[{{ $index }}][quantity]"
                value="{{ $cart->quantity }}" class="cart-item-quantity">
        @endforeach
        <button type="submit" class="btn btn-primary btn-ecomm px-3">
            <i class='bx bx-refresh me-2'></i> Update Cart
        </button>
    </form>
</div>
            </div>
        </div>
    </div>

    <!-- Right Side: Order Summary -->
    <div class="col-12 col-xl-4">
        <div class="checkout-form p-3 bg-light">
            <div class="card rounded-0 border bg-transparent shadow-none">
                <div class="card-body">
                    <p class="fs-5">Apply Discount Code</p>
                    <form action="{{ url('apply-coupon') }}" method="post" class="d-flex">
                        @csrf
                        <input type="text" name="coupon_code" class="form-control rounded-0"
                            placeholder="Enter discount code" required>
                        <button class="btn btn-dark btn-ecomm rounded-0" type="submit">Apply</button>
                    </form>

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
                    <p class="mb-2">Subtotal:
                        <span class="float-end subtotal">Rs {{ number_format($total_price, 2) }}</span>
                    </p>
                    <p class="mb-2">Shipping:
                        <span class="float-end">Free</span>
                    </p>
                    <p class="mb-2">Discount:
                        <span class="float-end">--</span>
                    </p>
                    <div class="my-3 border-top"></div>
                    <h5 class="mb-0">Order Total:
                        <span class="float-end order-total">Rs {{ number_format($total_price, 2) }}</span>
                    </h5>
                    <div class="my-4"></div>
                    <div class="d-grid">
                        <a href="{{ url('/checkout') }}" class="btn btn-dark btn-ecomm">Proceed to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // 🔥 DELETE CART ITEM
        document.addEventListener('click', function(e) {
            const button = e.target.closest('.deleteCartItem');
            if (!button) return;

            e.preventDefault();
            const cartid = button.getAttribute('data-cartid');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            button.disabled = true;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Removing...';

            fetch('/cart/delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        cartid
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        // Replace cart items
                        document.querySelector('#appendCartItems').closest('.col-12.col-xl-8')
                            .outerHTML = data.view;
                        // Update header cart
                        if (data.headerview) document.querySelector('.header-cart-items')
                            .innerHTML = data.headerview;
                        // Update total
                        updateCartTotal(data.cartTotal);
                        updateCartCounters(data.totalCartItems);
                        showMessage('Item removed!', 'success');
                        if (data.totalCartItems === 0) setTimeout(() => location.reload(), 1500);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(() => showMessage('Failed to remove item.', 'error'))
                .finally(() => {
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                });
        });

        // 🔥 QUANTITY CHANGE
        document.addEventListener('change', function(e) {
            const input = e.target;
            if (!input.matches('input[data-cartid]')) return;

            const cartid = input.getAttribute('data-cartid');
            let quantity = parseInt(input.value);

            if (quantity < 1) {
                quantity = 1;
                input.value = 1;
            }
            if (quantity > 10) {
                quantity = 10;
                input.value = 10;
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/cart/update-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        cartid,
                        quantity
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        // Update totals
                        document.querySelector('#appendCartItems').closest('.col-12.col-xl-8')
                            .outerHTML = data.view;
                        updateCartTotal(data.cartTotal);
                        updateCartCounters(data.totalCartItems);
                        showMessage('Quantity updated!', 'success');
                    }
                })
                .catch(() => showMessage('Update failed.', 'error'));
        });

        // Helper Functions
        function updateCartTotal(total) {
            document.querySelectorAll('.subtotal, .order-total').forEach(el => {
                el.textContent = 'Rs ' + parseFloat(total).toFixed(2);
            });
        }

        function updateCartCounters(count) {
            document.querySelectorAll('.totalCartItems, .cart-badge').forEach(el => {
                el.textContent = count;
                el.style.display = count > 0 ? 'inline' : 'none';
            });
        }

        function showMessage(msg, type) {
            const alert = document.createElement('div');
            alert.className =
                `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            alert.innerHTML = `<i class="bx bx-${type === 'success' ? 'check' : 'error'}-circle"></i> ${msg}`;
            alert.style.position = 'fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }

        // Mobile Fix
        window.addEventListener('resize', fixMobileSticky);

        function fixMobileSticky() {
            const form = document.querySelector('.checkout-form');
            if (!form) return;
            if (window.innerWidth < 1200) {
                form.style.position = 'static';
            } else {
                form.style.position = 'sticky';
                form.style.top = '20px';
            }
        }
        fixMobileSticky();

        document.addEventListener('change', function(e) {
            const input = e.target;
            if (!input.matches('input[data-cartid]')) return;

            const cartid = input.getAttribute('data-cartid');
            let quantity = parseInt(input.value);

            if (quantity < 1) {
                quantity = 1;
                input.value = 1;
            }
            if (quantity > 10) {
                quantity = 10;
                input.value = 10;
            }

            // Update hidden form input
            const hiddenQuantityInput = document.querySelector(
                `input.cart-item-quantity[value="${input.dataset.originalQuantity}"]`);
            if (hiddenQuantityInput) {
                hiddenQuantityInput.value = quantity;
            }

            // Real-time AJAX update
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/cart/update-quantity', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        cartid,
                        quantity
                    })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.status) {
                        // Update item total
                        const row = input.closest('.cart-item-row');
                        const itemTotal = row.querySelector('.item-total');
                        if (itemTotal && data.itemTotal) {
                            itemTotal.textContent = 'Rs ' + parseFloat(data.itemTotal).toFixed(2);
                        }

                        // Update cart totals
                        updateCartTotal(data.cartTotal);
                        updateCartCounters(data.totalCartItems);

                        // Update hidden input with original quantity for reference
                        input.dataset.originalQuantity = quantity;

                        showMessage('Quantity updated!', 'success');
                    }
                })
                .catch(() => showMessage('Update failed.', 'error'));
        });

        // Initialize original quantity data
        document.querySelectorAll('input[data-cartid]').forEach(input => {
            input.dataset.originalQuantity = input.value;
        });

        // Rest of your existing functions...
        function updateCartTotal(total) {
            document.querySelectorAll('.subtotal, .order-total').forEach(el => {
                el.textContent = 'Rs ' + parseFloat(total).toFixed(2);
            });
        }

        function updateCartCounters(count) {
            document.querySelectorAll('.totalCartItems, .cart-badge').forEach(el => {
                el.textContent = count;
                el.style.display = count > 0 ? 'inline' : 'none';
            });
        }

        function showMessage(msg, type) {
            const alert = document.createElement('div');
            alert.className =
                `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
            alert.innerHTML = `<i class="bx bx-${type === 'success' ? 'check' : 'error'}-circle"></i> ${msg}`;
            alert.style.position = 'fixed';
            alert.style.top = '20px';
            alert.style.right = '20px';
            alert.style.zIndex = '9999';
            document.body.appendChild(alert);
            setTimeout(() => alert.remove(), 3000);
        }
    });
</script>
