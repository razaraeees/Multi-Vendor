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
                            @endphp
                            <div class="col">
                                <div class="card">
                                    <div class="position-relative overflow-hidden">
                                        <div class="add-cart position-absolute top-0 end-0 mt-3 me-3">
                                            <a href="{{ $product_url }}"><i class='bx bx-cart-add'></i></a>
                                        </div>
                                        <div class="quick-view position-absolute start-0 bottom-0 end-0">
                                            <a href="javascript:;" data-bs-toggle="modal"
                                                data-bs-target="#QuickViewProduct">Quick View</a>
                                        </div>
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
                                            <a href="javascript:;" data-bs-toggle="modal"
                                                data-bs-target="#QuickViewProduct">Quick View</a>
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
                                                    Rs. {{ $product['product_price'] - ($product['product_price'] * $product['product_discount']) / 100 }}
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
                                                $image = $item['images'][0]['image'] ?? $item['product_image'] ?? null;
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
                                                    Rs. {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
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
                                                $image = $item['images'][0]['image'] ?? $item['product_image'] ?? null;
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
                                                    Rs. {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
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
                                                $image = $item['images'][0]['image'] ?? $item['product_image'] ?? null;
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
                                                    Rs. {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
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
                                                $image = $item['images'][0]['image'] ?? $item['product_image'] ?? null;
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
                                                    Rs. {{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
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
    <!-- Site-Priorities /- -->
@endsection