<?php
// Getting the 'enabled' sections ONLY and their child categories (using the 'categories' relationship method) which, in turn, include their 'subcategories`
$categorys = \App\Models\Category::categories();
// dd($sections);
?>

<div class="header-wrapper">
    {{-- <div class="top-menu">
        <div class="container">
            <nav class="navbar navbar-expand">
                <div class="shiping-title d-none d-sm-flex">Welcome to our Shopingo store!</div>
                <ul class="navbar-nav ms-auto d-none d-lg-flex">
                    <li class="nav-item"><a class="nav-link" href="about-us.html">About</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="contact-us.html">Contact</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown"> <a class="nav-link dropdown-toggle" href="#"
                            data-bs-toggle="dropdown">USD</a>
                        <ul class="dropdown-menu dropdown-menu-lg-end">
                            <li><a class="dropdown-item" href="#">USD</a>
                            </li>
                            <li><a class="dropdown-item" href="#">EUR</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="#"
                            data-bs-toggle="dropdown">
                            <div class="lang d-flex gap-1">
                                <div><i class="flag-icon flag-icon-um"></i>
                                </div>
                                <div><span>ENG</span>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg-end">
                            <a class="dropdown-item d-flex allign-items-center" href="javascript:;"> <i
                                    class="flag-icon flag-icon-de me-2"></i><span>German</span>
                            </a> <a class="dropdown-item d-flex allign-items-center" href="javascript:;"><i
                                    class="flag-icon flag-icon-fr me-2"></i><span>French</span></a>
                            <a class="dropdown-item d-flex allign-items-center" href="javascript:;"><i
                                    class="flag-icon flag-icon-um me-2"></i><span>English</span></a>
                            <a class="dropdown-item d-flex allign-items-center" href="javascript:;"><i
                                    class="flag-icon flag-icon-in me-2"></i><span>Urdu</span></a>
                            <a class="dropdown-item d-flex allign-items-center" href="javascript:;"><i
                                    class="flag-icon flag-icon-cn me-2"></i><span>Chinese</span></a>
                            <a class="dropdown-item d-flex allign-items-center" href="javascript:;"><i
                                    class="flag-icon flag-icon-ae me-2"></i><span>Arabic</span></a>
                        </div>
                    </li>
                </ul>
                <ul class="navbar-nav social-link ms-lg-2 ms-auto">
                    <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class='bx bxl-facebook'></i></a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class='bx bxl-twitter'></i></a>
                    </li>
                    <li class="nav-item"> <a class="nav-link" href="javascript:;"><i class='bx bxl-linkedin'></i></a>
                    </li>
                </ul>
            </nav>
        </div>
    </div> --}}
    <div class="header-content">
        <div class="container">
            <div class="row align-items-center gx-4">
                <div class="col-auto">
                    <div class="d-flex align-items-center gap-3">
                        <div class="mobile-toggle-menu d-inline d-xl-none" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasNavbar">
                            <i class="bx bx-menu"></i>
                        </div>
                        <div class="logo">
                            <a href="index.html">
                                <img src="{{ asset('assets/images/logo/logo.jpg') }}" class="logo-icon"
                                    alt="" />
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-xl order-4 order-xl-0">
                    <div class="input-group flex-nowrap pb-3 pb-xl-0">
                        <form class="input-group flex-nowrap pb-3 pb-xl-0" action="{{ url('/search-products') }}"
                            method="get">
                            <input style=" border: 1px solid red !important;" type="text" class="form-control w-100 border-color border border-3"
                                placeholder="Search for Products" name="search"
                                @if (isset($_REQUEST['search']) && !empty($_REQUEST['search'])) value="{{ $_REQUEST['search'] }}" @endif>
                            <button class="btn btn-danger btn-ecomm border-3" type="submit" style="background-color: red">Search</button>
                        </form>
                    </div>
                </div>
                {{-- <div class="col-auto d-none d-xl-flex">
                    <div class="d-flex align-items-center gap-3">

                        <div class="">
                            <p class="mb-0 text-content">CALL US NOW</p>
                            <h5 class="mb-0">+011 5827918</h5>
                        </div>
                    </div>
                </div> --}}
                <div class="col-auto ms-auto">
                    <div class="top-cart-icons">
                        <nav class="navbar navbar-expand">
                            <ul class="navbar-nav">
                                <li class="nav-item dropdown dropdown-large">
                                    <a href="#"
                                        class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative cart-link"
                                        data-bs-toggle="dropdown">
                                        <i class='bx bx-user'></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end" style="width: 200px">
                                        @if (Auth::check())
                                            <a class="dropdown-item" href="{{ url('user/account') }}">
                                                <i class="fas fa-user u-s-m-r-9"></i> My Account
                                            </a>
                                            <a class="dropdown-item" href="{{ url('user/orders') }}">
                                                <i class="fas fa-box u-s-m-r-9"></i> My Orders
                                            </a>
                                            <a class="dropdown-item" href="{{ url('cart') }}">
                                                <i class="fas fa-shopping-cart u-s-m-r-9"></i> My Cart
                                            </a>
                                            <a class="dropdown-item" href="{{ url('checkout') }}">
                                                <i class="fas fa-credit-card u-s-m-r-9"></i> Checkout
                                            </a>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="{{ url('user/logout') }}">
                                                <i class="fas fa-sign-out-alt u-s-m-r-9"></i> Logout
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{ url('login') }}">
                                                <i class="fas fa-sign-in-alt u-s-m-r-9"></i> Customer Login
                                            </a>
                                            <a class="dropdown-item" href="{{ url('vendor/login-register') }}">
                                                <i class="fas fa-store u-s-m-r-9"></i> Vendor Login
                                            </a>
                                        @endif
                                    </div>
                                </li>

                                <li class="nav-item"><a href="{{ url('/wishlist') }}" class="nav-link cart-link"><i
                                            class='bx bx-heart'></i></a>
                                </li>
                                <li class="nav-item dropdown dropdown-large">
                                    <a href="#"
                                        class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative cart-link"
                                        data-bs-toggle="dropdown">

                                        {{-- 🔢 Dynamic Cart Item Count --}}
                                        <span class="alert-count">{{ totalCartItems() }}</span>

                                        <i class='bx bx-shopping-bag'></i>
                                    </a>

                                    @php

                                        use App\Models\Cart;

                                        $cartItems = Cart::getCartItems();
                                        $cartAmouts = 0;

                                    @endphp

                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a href="{{ url('cart') }}">
                                            <div class="cart-header">
                                                <p class="cart-header-title mb-0">
                                                    {{ count($cartItems) }} ITEM{{ count($cartItems) > 1 ? 'S' : '' }}
                                                </p>
                                                <p class="cart-header-clear ms-auto mb-0">VIEW CART</p>
                                            </div>
                                        </a>
                                        @forelse ($cartItems as $item)
                                            @php
                                                $product = $item->product;
                                                $price = $product->product_price;
                                                $subtotal = $price * $item->quantity;
                                                $cartAmouts += $subtotal;
                                            @endphp

                                            <div class="dropdown-cart-item d-flex p-2 border-bottom">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('front/images/product_images/small/' . $product->product_image) }}"
                                                        width="50" height="50"
                                                        alt="{{ $product->product_name }}">
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <h6 class="mb-0">{{ $product->product_name }}</h6>
                                                    <small>Qty: {{ $item->quantity }}</small><br>
                                                    <small>Price: Rs {{ $price }}</small>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center p-3">
                                                <p class="mb-0">Your cart is empty.</p>
                                            </div>
                                        @endforelse

                                        <div class="d-grid p-3 border-top">
                                            <a href="{{ url('checkout') }}"
                                                class="btn btn-dark btn-ecomm">CHECKOUT</a>
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
    <div class="primary-menu">
        <nav class="navbar navbar-expand-xl w-100 navbar-dark container mb-0 p-0">
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
                <div class="offcanvas-header">
                    <div class="offcanvas-logo"><img src="{{ asset('assets/images/logo-icon.png') }}" width="100"
                            alt="">
                    </div>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                        aria-label="Close"></button>
                </div>
                <div class="offcanvas-body primary-menu">
                    <ul class="navbar-nav justify-content-center flex-grow-1 gap-1">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/') }}">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#"
                                data-bs-toggle="dropdown">Categories</a>

                            <ul class="dropdown-menu">
                                @foreach ($categories as $main)
                                    <li class="dropdown-submenu">
                                        {{-- Check if this category has any products --}}
                                        @if ($main->products->count() > 0)
                                            <a class="dropdown-item dropdown-toggle" href="{{ url($main->url) }}">
                                                {{ $main->category_name }}
                                            </a>
                                        @else
                                            <span class="dropdown-item disabled">{{ $main->category_name }}</span>
                                        @endif

                                        {{-- Subcategories --}}
                                        @if ($main->subcategories->count())
                                            <ul class="dropdown-menu">
                                                @foreach ($main->subcategories as $sub)
                                                    <li class="dropdown-submenu">
                                                        @if ($sub->products->count() > 0)
                                                            <a class="dropdown-item dropdown-toggle"
                                                                href="{{ url($sub->url) }}">
                                                                {{ $sub->category_name }}
                                                            </a>
                                                        @else
                                                            <span
                                                                class="dropdown-item disabled">{{ $sub->category_name }}</span>
                                                        @endif

                                                        {{-- Sub-subcategories --}}
                                                        @if ($sub->subcategories->count())
                                                            <ul class="dropdown-menu">
                                                                @foreach ($sub->subcategories as $child)
                                                                    <li>
                                                                        @if ($child->products->count() > 0)
                                                                            <a class="dropdown-item"
                                                                                href="{{ url($child->url) }}">
                                                                                {{ $child->category_name }}
                                                                            </a>
                                                                        @else
                                                                            <span
                                                                                class="dropdown-item disabled">{{ $child->category_name }}</span>
                                                                        @endif
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link" href="{{ url('/shop') }}"> Shop
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('contact') }}">Contact</a>
                        </li>

                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".toggle-submenu").forEach(function(btn) {
            btn.addEventListener("click", function(e) {
                e.preventDefault();
                let target = document.getElementById(this.dataset.target);
                if (target.style.display === "none" || target.style.display === "") {
                    target.style.display = "block";
                    this.innerHTML = '<i class="bi bi-chevron-up"></i>';
                } else {
                    target.style.display = "none";
                    this.innerHTML = '<i class="bi bi-chevron-down"></i>';
                }
            });
        });
    });
</script>
<style>
    /* Dropdown hover */
    .dropdown:hover>.dropdown-menu {
        display: block;
    }

    .dropdown-menu {
        min-width: 220px;
    }

    .dropdown-submenu {
        position: relative;
    }

    .dropdown-submenu:hover .dropdown-menu {
        display: block;
        top: 0;
        left: 100%;
    }
</style>
