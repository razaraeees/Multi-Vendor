{{-- This page is rendered by index() method in Front/IndexController.php --}}
@extends('front.layout.layout')

<?php
// Getting the 'enabled' sections ONLY and their child categories (using the 'categories' relationship method) which, in turn, include their 'subcategories`
$categorys = \App\Models\Category::categories();
// dd($sections);
?>

@section('sidebar')
    <section class="slider-section mb-4">
        <div class="first-slider p-0">
            <div class="banner-slider owl-carousel owl-theme">
                {{-- Loop through dynamic slider banners --}}
                @foreach ($sliderBanners as $banner)
                    <div class="item">
                        <div class="position-relative">
                            <div class="position-absolute top-50 slider-content translate-middle">
                                <h3 class="h3 fw-bold d-none d-md-block">New Trending</h3>
                                <h1 class="h1 fw-bold">{{ $banner['title'] }}</h1>
                                <p class="fw-bold text-dark d-none d-md-block"><i>Last call for upto 15%</i></p>
                                <div>
                                    <a class="btn btn-dark btn-ecomm px-4"
                                        @if (!empty($banner['link'])) href="{{ url($banner['link']) }}"
                                      @else
                                       href="javascript:;" @endif>
                                        Shop Now
                                    </a>
                                </div>
                            </div>
                            <a
                                @if (!empty($banner['link'])) href="{{ url($banner['link']) }}"
                            @else
                                href="javascript:;" @endif>
                                <img src="{{ asset('front/images/banner_images/' . $banner['image']) }}" class="img-fluid"
                                    alt="{{ $banner['title'] }}" title="{{ $banner['title'] }}">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('content')
    <!-- Main-Slider -->
    <div class="page-content">

        <section class="py-4">
            <div class="container">
                <div class="separator pb-4">
                    <div class="line"></div>
                    <h5 class="mb-0 fw-bold separator-title">Browse Category</h5>
                    <div class="line"></div>
                </div>

                <div class="product-grid">
                    <div class="browse-category owl-carousel owl-theme">
                        @foreach ($categories as $main)
                            <div class="item">
                                <div class="card rounded-0">
                                    <div class="card-body p-0">
                                        @if (!empty($main->category_image))
                                            <img src="{{ asset('front/images/category_images/' . $main->category_image) }}"
                                                alt="{{ $main->category_name }}" class="img-fluid rounded"
                                                style="width: 100%; max-width: 200px; height: 200px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                style="width: 100%; max-width: 200px; height: 200px;">
                                                <i class="fas fa-image text-muted fa-2x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer text-center bg-transparent">
                                        <h6 class="mb-0 text-uppercase fw-bold">{{ $main->category_name }}</h6>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <!--start Featured product-->
        <section class="py-4">
            <div class="container">
                <div class="separator pb-4">
                    <div class="line"></div>
                    <h5 class="mb-0 fw-bold separator-title">FEATURED PRODUCTS</h5>
                    <div class="line"></div>
                </div>
                <div class="product-grid">
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-4 row-cols-xxl-5 g-3 g-sm-4">
                        @foreach ($featuredProducts as $product)
                            @php
                                $getDiscountPrice = \App\Models\Product::getDiscountPrice($product['id']);
                                $product_url = url('product/' . $product['id']);
                                $image = $product['images'][0]['image'] ?? null;

                                //  dd($product['images'][0]['image']);
                            @endphp
                            <div class="col">
                                <div class="card">
                                    <div class="position-relative overflow-hidden">
                                        <div class="add-cart position-absolute top-0 end-0 mt-3 me-3">
                                            <a href="{{ $product_url }}"><i class='bx bx-cart-add'></i></a>
                                        </div>
                                        <div class="quick-view position-absolute start-0 bottom-0 end-0">
                                            <a href="javascript:;" class="quickview-btn"
                                                data-product-id="{{ $product['id'] }}" data-toggle="modal"
                                                data-target="#QuickViewProduct">
                                                Quick View
                                            </a>
                                        </div>
                                        @if ($image)
                                            <a href="{{ $product_url }}">
                                                <img src="{{ asset('front/images/product_images/small/' . $image) }}"
                                                    alt="{{ $product['product_name'] }}" class="img-fluid">
                                            </a>
                                        @else
                                            <a href="{{ $product_url }}">
                                                <img src="{{ asset('assets/images/no-product-image.png') }}" alt="No Image"
                                                    class="img-fluid">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body px-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1 product-short-name">{{ $product['product_code'] }}</p>
                                                <h6 class="mb-0 fw-bold product-short-title">
                                                    {{ $product['product_name'] }}</h6>
                                            </div>
                                            <div class="icon-wishlist">
                                                <a href="javascript:;"><i class="bx bx-heart"></i></a>
                                            </div>
                                        </div>
                                        <div class="cursor-pointer rating mt-2">
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                        </div>
                                        <div
                                            class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                            @if ($getDiscountPrice > 0)
                                                <div
                                                    class="h6 fw-light fw-bold text-secondary text-decoration-line-through">
                                                    Rs. {{ $product['product_price'] }}
                                                </div>
                                                <div class="h6 fw-bold">
                                                    Rs. {{ $getDiscountPrice }}
                                                </div>
                                            @else
                                                <div class="h6 fw-bold">
                                                    Rs. {{ $product['product_price'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div><!--end row-->
                </div>
            </div>
        </section>
        <!--end Featured product-->
        <!--start New Arrivals-->
        <section class="py-4">
            <div class="container">
                <div class="separator pb-4">
                    <div class="line"></div>
                    <h5 class="mb-0 fw-bold separator-title">New Arrivals</h5>
                    <div class="line"></div>
                </div>
                <div class="product-grid">
                    <div class="new-arrivals owl-carousel owl-theme position-relative">
                        @foreach ($newProducts as $product)
                            <div class="item">
                                <div class="card">
                                    <div class="position-relative overflow-hidden">
                                        <div class="add-cart position-absolute top-0 end-0 mt-3 me-3">
                                            <a href="javascript:;"><i class='bx bx-cart-add'></i></a>
                                        </div>
                                        <div class="quick-view position-absolute start-0 bottom-0 end-0">
                                            <a href="javascript:;" class="quickview-btn"
                                                data-product-id="{{ $product['id'] }}" data-toggle="modal"
                                                data-target="#QuickViewProduct">
                                                Quick View
                                            </a>
                                        </div>
                                        @php
                                            $image = $product['images'][0]['image'] ?? null;
                                            $product_url = url('product/' . $product['id']);
                                        @endphp
                                        @if ($image)
                                            <a href="{{ $product_url }}">
                                                <img src="{{ asset('front/images/product_images/small/' . $image) }}"
                                                    alt="{{ $product['product_name'] }}" class="img-fluid">
                                            </a>
                                        @else
                                            <a href="{{ $product_url }}">
                                                <img src="{{ asset('assets/images/no-product-image.png') }}"
                                                    alt="No Image" class="img-fluid">
                                            </a>
                                        @endif
                                    </div>
                                    <div class="card-body px-0">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <p class="mb-1 product-short-name">
                                                    {{ $product['category']['category_name'] ?? 'Product' }}</p>
                                                <h6 class="mb-0 fw-bold product-short-title">
                                                    {{ $product['product_name'] }}</h6>
                                            </div>
                                            <div class="icon-wishlist">
                                                <a href="javascript:;"><i class="bx bx-heart"></i></a>
                                            </div>
                                        </div>
                                        <div class="cursor-pointer rating mt-2">
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                        </div>
                                        <div
                                            class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                            @if ($product['product_discount'] > 0)
                                                <div
                                                    class="h6 fw-light fw-bold text-secondary text-decoration-line-through">
                                                    Rs. {{ $product['product_price'] }}
                                                </div>
                                                <div class="h6 fw-bold">
                                                    Rs.
                                                    {{ $product['product_price'] - ($product['product_price'] * $product['product_discount']) / 100 }}
                                                </div>
                                            @else
                                                <div class="h6 fw-bold">Rs. {{ $product['product_price'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        <!--end New Arrivals-->
        <!--start Advertise banners-->
        <section class="py-4">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 g-4">
                    <div class="col">
                        <div class="card rounded-0 shadow-none bg-info bg-opacity-25">
                            <div class="row g-0 align-items-center">
                                <div class="col">
                                    <img src="{{ asset('assets/images/promo/01.png') }}" class="img-fluid"
                                        alt="" />
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <h5 class="card-title text-uppercase fw-bold">Men Wear</h5>
                                        <p class="card-text text-uppercase">Starting at $9</p>
                                        <a href="javascript:;" class="btn btn-outline-dark btn-ecomm">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card rounded-0 shadow-none bg-danger bg-opacity-25">
                            <div class="row g-0 align-items-center">
                                <div class="col">
                                    <img src="{{ asset('assets/images/promo/02.png') }}" class="img-fluid"
                                        alt="" />
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <h5 class="card-title text-uppercase fw-bold">Women Wear</h5>
                                        <p class="card-text text-uppercase">Starting at $9</p>
                                        <a href="javascript:;" class="btn btn-outline-dark btn-ecomm">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card rounded-0 shadow-none bg-warning bg-opacity-25">
                            <div class="row g-0 align-items-center">
                                <div class="col">
                                    <img src="{{ asset('assets/images/promo/03.png') }}" class="img-fluid"
                                        alt="" />
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <h5 class="card-title text-uppercase fw-bold">Kids Wear</h5>
                                        <p class="card-text text-uppercase">Starting at $9</p>
                                        <a href="javascript:;" class="btn btn-outline-dark btn-ecomm">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </section>
        <!--end Advertise banners-->

        <!--start bottom products section-->
        <section class="py-5 border-top">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
                    <div class="col">
                        <div class="bestseller-list mb-3">
                            <h6 class="mb-3 text-uppercase fw-bold">Best Selling Products</h6>

                            @foreach (array_slice($bestSellers, 0, 4) as $item)
                                <hr>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bottom-product-img">
                                        <a href="{{ url('product/' . $item['id']) }}">
                                            @php
                                                $image =
                                                    $item['images'][0]['image'] ?? ($item['product_image'] ?? null);
                                            @endphp
                                            @if ($image)
                                                <img src="{{ asset('front/images/product_images/small/' . $image) }}"
                                                    width="80" alt="{{ $item['product_name'] }}">
                                            @else
                                                <img src="{{ asset('assets/images/no-product-image.png') }}"
                                                    width="80" alt="No Image">
                                            @endif
                                        </a>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold mb-1">{{ $item['product_name'] }}</h6>
                                        <div class="rating">
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                        </div>
                                        <p class="mb-0 pro-price">
                                            <strong>
                                                @if ($item['product_discount'] > 0)
                                                    Rs.
                                                    {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
                                                @else
                                                    Rs. {{ $item['product_price'] }}
                                                @endif
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col">
                        <div class="featured-list mb-3">
                            <h6 class="mb-3 text-uppercase fw-bold">Featured Products</h6>
                            @foreach (array_slice($featuredProducts, 0, 4) as $item)
                                <hr>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bottom-product-img">
                                        <a href="{{ url('product/' . $item['id']) }}">
                                            @php
                                                $image =
                                                    $item['images'][0]['image'] ?? ($item['product_image'] ?? null);
                                            @endphp
                                            @if ($image)
                                                <img src="{{ asset('front/images/product_images/small/' . $image) }}"
                                                    width="80" alt="{{ $item['product_name'] }}">
                                            @else
                                                <img src="{{ asset('assets/images/no-product-image.png') }}"
                                                    width="80" alt="No Image">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="ms-0">
                                        <h6 class="mb-0 fw-bold mb-1">{{ $item['product_name'] }}</h6>
                                        <div class="rating">
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                        </div>
                                        <p class="mb-0 pro-price">
                                            <strong>
                                                @if ($item['product_discount'] > 0)
                                                    Rs.
                                                    {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
                                                @else
                                                    Rs. {{ $item['product_price'] }}
                                                @endif
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col">
                        <div class="new-arrivals-list mb-3">
                            <h6 class="mb-3 text-uppercase fw-bold">New Arrivals</h6>
                            @foreach (array_slice($newProducts, 0, 4) as $item)
                                <hr>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bottom-product-img">
                                        <a href="{{ url('product/' . $item['id']) }}">
                                            @php
                                                $image =
                                                    $item['images'][0]['image'] ?? ($item['product_image'] ?? null);
                                            @endphp
                                            @if ($image)
                                                <img src="{{ asset('front/images/product_images/small/' . $image) }}"
                                                    width="80" alt="{{ $item['product_name'] }}">
                                            @else
                                                <img src="{{ asset('assets/images/no-product-image.png') }}"
                                                    width="80" alt="No Image">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="ms-0">
                                        <h6 class="mb-0 fw-bold mb-1">{{ $item['product_name'] }}</h6>
                                        <div class="rating">
                                            @for ($i = 0; $i < 5; $i++)
                                                <i class="bx bxs-star text-warning"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0 pro-price">
                                            <strong>
                                                @if ($item['product_discount'] > 0)
                                                    Rs.
                                                    {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
                                                @else
                                                    Rs. {{ $item['product_price'] }}
                                                @endif
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col">
                        <div class="top-rated-products-list mb-3">
                            <h6 class="mb-3 text-uppercase fw-bold">Top Rated Products</h6>

                            @foreach (array_slice($discountedProducts, 0, 4) as $item)
                                <hr>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bottom-product-img">
                                        <a href="{{ url('product/' . $item['id']) }}">
                                            @php
                                                $image =
                                                    $item['images'][0]['image'] ?? ($item['product_image'] ?? null);
                                            @endphp
                                            @if ($image)
                                                <img src="{{ asset('front/images/product_images/small/' . $image) }}"
                                                    width="80" alt="{{ $item['product_name'] }}">
                                            @else
                                                <img src="{{ asset('assets/images/no-product-image.png') }}"
                                                    width="80" alt="No Image">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="ms-0">
                                        <h6 class="mb-0 fw-bold mb-1">{{ $item['product_name'] }}</h6>
                                        <div class="rating">
                                            @for ($i = 0; $i < 5; $i++)
                                                <i class="bx bxs-star text-warning"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0 pro-price">
                                            <strong>
                                                @if ($item['product_discount'] > 0)
                                                    Rs.
                                                    {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
                                                @else
                                                    Rs. {{ $item['product_price'] }}
                                                @endif
                                            </strong>
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </section>
        <!--end bottom products section-->

        <section class="py-4">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-3 g-4">
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center p-3 border">
                            <div class="fs-1 text-content"><i class='bx bx-taxi'></i>
                            </div>
                            <div class="info-box-content ps-3">
                                <h6 class="mb-0 fw-bold">FREE SHIPPING &amp; RETURN</h6>
                                <p class="mb-0">Free shipping on all orders over $49</p>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center p-3 border">
                            <div class="fs-1 text-content"><i class='bx bx-dollar-circle'></i>
                            </div>
                            <div class="info-box-content ps-3">
                                <h6 class="mb-0 fw-bold">MONEY BACK GUARANTEE</h6>
                                <p class="mb-0">100% money back guarantee</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="d-flex align-items-center justify-content-center p-3 border">
                            <div class="fs-1 text-content"><i class='bx bx-support'></i>
                            </div>
                            <div class="info-box-content ps-3">
                                <h6 class="mb-0 fw-bold">ONLINE SUPPORT 24/7</h6>
                                <p class="mb-0">Awesome Support for 24/7 Days</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </section>
        <!--end information-->

        <!--start brands-->
        <section class="py-4">
            <div class="container">
                <h3 class="d-none">Brands</h3>
                <div class="brand-grid">
                    <div class="brands-shops owl-carousel owl-theme border">
                        <div class="item border-end">
                            <div class="p-4">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/brands/01.png') }}" class="img-fluid"
                                        alt="...">
                                </a>
                            </div>
                        </div>
                        <div class="item border-end">
                            <div class="p-4">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/brands/02.png') }}" class="img-fluid"
                                        alt="...">
                                </a>
                            </div>
                        </div>
                        <div class="item border-end">
                            <div class="p-4">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/brands/03.png') }}" class="img-fluid"
                                        alt="...">
                                </a>
                            </div>
                        </div>
                        <div class="item border-end">
                            <div class="p-4">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/brands/04.png') }}" class="img-fluid"
                                        alt="...">
                                </a>
                            </div>
                        </div>
                        <div class="item border-end">
                            <div class="p-4">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/brands/05.png') }}" class="img-fluid"
                                        alt="...">
                                </a>
                            </div>
                        </div>
                        <div class="item border-end">
                            <div class="p-4">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/brands/06.png') }}" class="img-fluid"
                                        alt="...">
                                </a>
                            </div>
                        </div>
                        <div class="item border-end">
                            <div class="p-4">
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/brands/07.png') }}" class="img-fluid"
                                        alt="...">
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end brands-->
    </div>

    <div class="modal fade" id="QuickViewProduct" tabindex="-1" role="dialog" 
     aria-labelledby="QuickViewProductLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content rounded-0 border-0">
            <div class="modal-body">
                <!-- Initial loading content -->
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="mt-3">Loading product details...</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Site-Priorities /- -->
@endsection

@section('scripts')
    <script>
        // Fixed QuickView AJAX for Bootstrap 4.6
        $(document).ready(function() {

            // CORRECT: Bootstrap 4.6 ke liye data-toggle aur data-target use karo
            $('[data-toggle="modal"][data-target="#QuickViewProduct"]').click(function(e) {
                e.preventDefault();

                var productId = $(this).data('product-id');
                var modal = $('#QuickViewProduct');

                if (!productId) {
                    alert('Product ID not found');
                    return;
                }

                // Show loading state
                modal.find('.modal-body').html(`
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="mt-3">Loading product details...</p>
            </div>
        `);

                // Show modal immediately with loading state
                modal.modal('show');

                // AJAX request to get product details
                $.ajax({
                    url: '/product/quickview/' + productId,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            var product = response.product;
                            var images = response.images || [];
                            var attributes = response.attributes || [];

                            // Build image gallery HTML
                            var galleryHtml = '';
                            var thumbsHtml = '';

                            if (images.length > 0) {
                                images.forEach(function(image, index) {
                                    galleryHtml += `
                                <div class="item">
                                    <img src="/front/images/product_images/large/${image.image}" 
                                         class="img-fluid" alt="${product.product_name}">
                                </div>
                            `;
                                    thumbsHtml += `
                                <button class="owl-thumb-item">
                                    <img src="/front/images/product_images/small/${image.image}" 
                                         class="" alt="${product.product_name}">
                                </button>
                            `;
                                });
                            } else {
                                galleryHtml = `
                            <div class="item">
                                <img src="/assets/images/no-product-image.png" 
                                     class="img-fluid" alt="No Image">
                            </div>
                        `;
                                thumbsHtml = `
                            <button class="owl-thumb-item">
                                <img src="/assets/images/no-product-image.png" 
                                     class="" alt="No Image">
                            </button>
                        `;
                            }

                            // Build size options
                            var sizeOptions = '';
                            if (attributes.length > 0) {
                                attributes.forEach(function(attr) {
                                    sizeOptions +=
                                        `<option value="${attr.size}">${attr.size}</option>`;
                                });
                            } else {
                                sizeOptions = '<option>One Size</option>';
                            }

                            // Calculate discounted price
                            var originalPrice = parseFloat(product.product_price);
                            var discount = parseFloat(product.product_discount) || 0;
                            var finalPrice = discount > 0 ? originalPrice - (originalPrice *
                                discount / 100) : originalPrice;

                            // Build price HTML
                            var priceHtml = '';
                            if (discount > 0) {
                                priceHtml = `
                            <h5 class="mb-0 text-decoration-line-through text-muted">Rs. ${originalPrice}</h5>
                            <h4 class="mb-0">Rs. ${finalPrice.toFixed(2)}</h4>
                        `;
                            } else {
                                priceHtml = `<h4 class="mb-0">Rs. ${originalPrice}</h4>`;
                            }

                            // Build complete modal HTML with Bootstrap 4.6 classes
                            var modalHtml = `
                        <button type="button" class="close float-right" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <div class="row no-gutters">
                            <div class="col-12 col-lg-6">
                                <div class="image-zoom-section">
                                    <div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
                                        ${galleryHtml}
                                    </div>
                                    <div class="owl-thumbs d-flex justify-content-center" data-slider-id="1">
                                        ${thumbsHtml}
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-lg-6">
                                <div class="product-info-section p-3">
                                    <h3 class="mt-3 mt-lg-0 mb-0">${product.product_name}</h3>
                                    <div class="product-rating d-flex align-items-center mt-2">
                                        <div class="rates cursor-pointer font-13">
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                        </div>
                                        <div class="ml-1">
                                            <p class="mb-0">(${product.reviews_count || 0} Reviews)</p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center mt-3" style="gap: 0.5rem;">
                                        ${priceHtml}
                                    </div>
                                    <div class="mt-3">
                                        <h6>Description:</h6>
                                        <p class="mb-0">${product.description || 'No description available.'}</p>
                                    </div>
                                    <dl class="row mt-3">
                                        <dt class="col-sm-3">Product Code</dt>
                                        <dd class="col-sm-9">${product.product_code}</dd>
                                        <dt class="col-sm-3">Category</dt>
                                        <dd class="col-sm-9">${product.category_name || 'N/A'}</dd>
                                        <dt class="col-sm-3">Status</dt>
                                        <dd class="col-sm-9">
                                            <span class="badge ${product.is_featured ? 'badge-success' : 'badge-secondary'}">
                                                ${product.is_featured ? 'Featured' : 'Regular'}
                                            </span>
                                        </dd>
                                    </dl>
                                    <div class="row align-items-end mt-3">
                                        <div class="col-auto">
                                            <label class="form-label mb-1">Quantity</label>
                                            <select class="form-control form-control-sm" id="quickview-quantity">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                                <option value="5">5</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <label class="form-label mb-1">Size</label>
                                            <select class="form-control form-control-sm" id="quickview-size">
                                                ${sizeOptions}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex mt-4" style="gap: 0.5rem;">
                                        <button class="btn btn-dark btn-ecomm add-to-cart-quickview" 
                                                data-product-id="${product.id}">
                                            <i class="bx bxs-cart-add"></i> Add to Cart
                                        </button>
                                        <button class="btn btn-outline-dark btn-ecomm add-to-wishlist-quickview" 
                                                data-product-id="${product.id}">
                                            <i class="bx bx-heart"></i> Add to Wishlist
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                            modal.find('.modal-body').html(modalHtml);

                            // Initialize Owl Carousel after content is loaded
                            setTimeout(function() {
                                if ($('.product-gallery').length && !$(
                                        '.product-gallery').hasClass('owl-loaded')) {
                                    $('.product-gallery').owlCarousel({
                                        items: 1,
                                        loop: true,
                                        dots: false,
                                        nav: true,
                                        navText: [
                                            '<i class="bx bx-chevron-left"></i>',
                                            '<i class="bx bx-chevron-right"></i>'
                                        ],
                                        autoplay: false,
                                        mouseDrag: true,
                                        touchDrag: true
                                    });
                                }
                            }, 300);

                        } else {
                            modal.find('.modal-body').html(`
                        <div class="text-center py-5">
                            <i class="bx bx-error-circle text-danger" style="font-size: 48px;"></i>
                            <h5 class="mt-3">Error Loading Product</h5>
                            <p>${response.message || 'Unable to load product details'}</p>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    `);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr.responseText);
                        modal.find('.modal-body').html(`
                    <div class="text-center py-5">
                        <i class="bx bx-wifi-off text-warning" style="font-size: 48px;"></i>
                        <h5 class="mt-3">Connection Error</h5>
                        <p>Unable to load product details. Please check console for errors.</p>
                        <button type="button" class="btn btn-primary" onclick="location.reload()">Retry</button>
                        <button type="button" class="btn btn-secondary ml-2" data-dismiss="modal">Close</button>
                    </div>
                `);
                    }
                });
            });

            // Add to Cart from QuickView
            $(document).on('click', '.add-to-cart-quickview', function(e) {
                e.preventDefault();

                var productId = $(this).data('product-id');
                var quantity = $('#quickview-quantity').val() || 1;
                var size = $('#quickview-size').val();
                var button = $(this);

                // Show loading state
                button.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm mr-1"></i> Adding...');

                $.ajax({
                    url: '/cart/add',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        quantity: quantity,
                        size: size,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            button.removeClass('btn-dark').addClass('btn-success')
                                .html('<i class="bx bx-check mr-1"></i> Added to Cart');

                            if (response.cart_count) {
                                $('.cart-count').text(response.cart_count);
                            }

                            showNotification('Product added to cart successfully!', 'success');

                            setTimeout(function() {
                                button.prop('disabled', false)
                                    .removeClass('btn-success').addClass('btn-dark')
                                    .html(
                                        '<i class="bx bxs-cart-add mr-1"></i> Add to Cart'
                                    );
                            }, 2000);

                        } else {
                            button.prop('disabled', false)
                                .html('<i class="bx bxs-cart-add mr-1"></i> Add to Cart');
                            showNotification(response.message ||
                                'Failed to add product to cart', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Cart Error:', xhr.responseText);
                        button.prop('disabled', false)
                            .html('<i class="bx bxs-cart-add mr-1"></i> Add to Cart');
                        showNotification('Error adding product to cart', 'error');
                    }
                });
            });

            // Add to Wishlist from QuickView
            $(document).on('click', '.add-to-wishlist-quickview', function(e) {
                e.preventDefault();

                var productId = $(this).data('product-id');
                var button = $(this);

                button.prop('disabled', true).html(
                    '<i class="spinner-border spinner-border-sm mr-1"></i> Adding...');

                $.ajax({
                    url: '/wishlist/add',
                    type: 'POST',
                    data: {
                        product_id: productId,
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            button.removeClass('btn-outline-dark').addClass('btn-danger')
                                .html('<i class="bx bxs-heart mr-1"></i> In Wishlist');
                            showNotification('Product added to wishlist!', 'success');
                        } else {
                            button.prop('disabled', false)
                                .html('<i class="bx bx-heart mr-1"></i> Add to Wishlist');
                            showNotification(response.message || 'Failed to add to wishlist',
                                'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Wishlist Error:', xhr.responseText);
                        button.prop('disabled', false)
                            .html('<i class="bx bx-heart mr-1"></i> Add to Wishlist');
                        showNotification('Error adding to wishlist', 'error');
                    }
                });
            });

            // Notification function
            function showNotification(message, type) {
                var alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                var notification = `
                <div class="alert ${alertClass} alert-dismissible fade show notification-alert" role="alert" 
                    style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
        `;

                $('body').append(notification);

                setTimeout(function() {
                    $('.notification-alert').fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        });

        // Bootstrap 4.6 Modal Event Handlers
        $('#QuickViewProduct').on('hidden.bs.modal', function() {
            $(this).find('.modal-body').html(`
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-3">Loading product details...</p>
        </div>
    `);

            // Destroy owl carousel if exists
            if ($('.product-gallery').hasClass('owl-loaded')) {
                $('.product-gallery').trigger('destroy.owl.carousel')
                    .removeClass('owl-loaded owl-drag owl-grab');
            }
        });
    </script>
@endsection
