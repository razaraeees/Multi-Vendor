@extends('front.layout.layout')

@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">Shop Cart</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i>
                                        Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Shop Cart</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!--end breadcrumb-->

        <!--start shop cart-->

        <section class="py-4">  
            <div class="container">
                <div class="shop-cart">
                    <div class="row">
                            @include('front.products.cart_items')
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </section>
        
    </div>
@endsection


<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Cart page loaded - Event delegation active');

        // 🔥 EVENT DELEGATION - Works even after AJAX updates
        document.addEventListener('click', function(e) {
            // Check if clicked element is delete button
            if (e.target.classList.contains('deleteCartItem') || e.target.closest('.deleteCartItem')) {
                e.preventDefault();
                e.stopPropagation();

                let button = e.target.classList.contains('deleteCartItem') ? e.target : e.target
                    .closest('.deleteCartItem');
                let cartid = button.getAttribute('data-cartid');

                if (!cartid) {
                    showMessage('Cart ID not found!', 'error');
                    return;
                }

                console.log('Deleting cart item:', cartid);

                // Disable button and show loading
                button.disabled = true;
                let originalHTML = button.innerHTML;
                button.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Removing...';

                // Get CSRF token
                let csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found');
                    button.disabled = false;
                    button.innerHTML = originalHTML;
                    showMessage('Security token not found!', 'error');
                    return;
                }

                // AJAX request using fetch
                fetch('/cart/delete', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            cartid: cartid
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Delete response:', data);

                        if (data.status === false) {
                            button.disabled = false;
                            button.innerHTML = originalHTML;
                            showMessage(data.message || 'Failed to delete item', 'error');
                            return;
                        }

                        // 🔥 UPDATE CART ITEMS SECTION WITH PROPER REPLACEMENT
                        if (data.view) {
                            let cartContainer = document.querySelector('.col-12.col-xl-8');
                            if (cartContainer) {
                                // Add loading state
                                cartContainer.style.opacity = '0.6';
                                cartContainer.style.transition = 'opacity 0.3s ease';

                                setTimeout(() => {
                                    // Replace entire cart items section
                                    cartContainer.innerHTML = data.view;
                                    cartContainer.style.opacity = '1';
                                }, 200);
                            }
                        } else {
                            // Fallback: Remove item manually
                            let cartRow = button.closest('.cart-item-row');
                            if (cartRow) {
                                cartRow.style.transition = 'all 0.3s ease';
                                cartRow.style.opacity = '0';
                                cartRow.style.transform = 'translateX(-100%)';

                                setTimeout(() => {
                                    cartRow.remove();
                                    // Also remove the HR if it exists
                                    let nextHr = cartRow.nextElementSibling;
                                    if (nextHr && nextHr.tagName === 'HR') {
                                        nextHr.remove();
                                    }
                                }, 300);
                            }
                        }

                        // Update header cart
                        if (data.headerview) {
                            let headerCartItems = document.querySelector('.header-cart-items');
                            if (headerCartItems) {
                                headerCartItems.innerHTML = data.headerview;
                            }
                        }

                        // Update cart counter
                        if (data.totalCartItems !== undefined) {
                            updateCartCounters(data.totalCartItems);
                        }

                        // Update cart total in sidebar
                        if (data.cartTotal !== undefined) {
                            updateCartTotal(data.cartTotal);
                        }

                        // Show success message
                        showMessage('Item removed successfully!', 'success');

                        // Reload page if cart is empty
                        if (data.totalCartItems === 0) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        }

                    })
                    .catch(error => {
                        console.error('Error deleting cart item:', error);

                        // Re-enable button
                        button.disabled = false;
                        button.innerHTML = originalHTML;

                        showMessage('Something went wrong while deleting the item.', 'error');
                    });
            }
        });

        // Function to update cart counters
        function updateCartCounters(count) {
            const totalCartItems = document.querySelectorAll('.totalCartItems');
            totalCartItems.forEach(element => {
                element.textContent = count;
                element.style.display = count > 0 ? 'inline' : 'none';
            });

            const cartBadges = document.querySelectorAll('.cart-badge, .cart-count');
            cartBadges.forEach(badge => {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'inline-block' : 'none';
            });
        }

        // Function to update cart total
        function updateCartTotal(total) {
            const cartTotalElements = document.querySelectorAll('.cart-total, .order-total');
            cartTotalElements.forEach(element => {
                element.textContent = '$' + parseFloat(total).toFixed(2);
            });
        }

        // Helper function to show messages
        function showMessage(message, type = 'success') {
            // Remove existing alerts first
            const existingAlerts = document.querySelectorAll('.cart-alert');
            existingAlerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });

            let alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            let iconClass = type === 'success' ? 'bx-check-circle' : 'bx-error-circle';

            let alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show cart-alert" role="alert" style="margin-bottom: 1rem; border-radius: 8px;">
                <i class="bx ${iconClass} me-2"></i>
                <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;

            let cartList = document.querySelector('.shop-cart-list');
            if (cartList) {
                cartList.insertAdjacentHTML('afterbegin', alertHtml);
            }

            // Auto dismiss after 4 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.cart-alert');
                alerts.forEach(alert => {
                    if (alert && alert.parentNode) {
                        alert.style.opacity = '0';
                        alert.style.transition = 'opacity 0.3s ease';
                        setTimeout(() => {
                            if (alert.parentNode) {
                                alert.remove();
                            }
                        }, 300);
                    }
                });
            }, 4000);
        }

        // Discount code functionality
        const applyDiscountBtn = document.getElementById('applyDiscount');
        const discountCodeInput = document.getElementById('discountCode');

        if (applyDiscountBtn) {
            applyDiscountBtn.addEventListener('click', function() {
                const code = discountCodeInput.value.trim();
                if (code) {
                    console.log('Applying discount code:', code);
                    // Add discount code AJAX logic here
                } else {
                    showMessage('Please enter a discount code', 'error');
                }
            });
        }

        // Quantity change handler with event delegation
        document.addEventListener('change', function(e) {
            if (e.target.matches('.cart-action input[type="number"]')) {
                let quantity = parseInt(e.target.value);
                if (quantity < 1) {
                    e.target.value = 1;
                    quantity = 1;
                }
                if (quantity > 10) {
                    e.target.value = 10;
                    quantity = 10;
                }

                console.log('Quantity updated to:', quantity);
                // Add quantity update AJAX here if needed
            }
        });

    });

</script>
