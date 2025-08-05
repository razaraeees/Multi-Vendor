{{-- This page is rendered by index() method in Front/IndexController.php --}}
@extends('front.layout.layout')

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
        <!--start information-->
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
        <!--start pramotion-->
        <section class="py-4">
            <div class="container">
                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 g-4">
                    <div class="col">
                        <div class="card rounded-0 shadow-none bg-info bg-opacity-25">
                            <div class="row g-0 align-items-center">
                                <div class="col">
                                    <img src="{{ asset('assets/images/promo/01.png') }}" class="img-fluid" alt="" />
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
                                    <img src="{{ asset('assets/images/promo/02.png') }}" class="img-fluid" alt="" />
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <h5 class="card-title text-uppercase fw-bold">Women Wear</h5>
                                        <p class="card-text text-uppercase">Starting at $9</p> <a href="javascript:;"
                                            class="btn btn-outline-dark btn-ecomm">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card rounded-0 shadow-none bg-warning bg-opacity-25">
                            <div class="row g-0 align-items-center">
                                <div class="col">
                                    <img src="{{ asset('assets/images/promo/03.png') }}" class="img-fluid" alt="" />
                                </div>
                                <div class="col">
                                    <div class="card-body">
                                        <h5 class="card-title text-uppercase fw-bold">Kids Wear</h5>
                                        <p class="card-text text-uppercase">Starting at $9</p><a href="javascript:;"
                                            class="btn btn-outline-dark btn-ecomm">SHOP NOW</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </section>
        <!--end pramotion-->
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
                                $product_image_path = 'front/images/product_images/small/' . $product['product_image'];
                                $getDiscountPrice = \App\Models\Product::getDiscountPrice($product['id']);
                                $product_url = url('product/' . $product['id']);
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
                                        <a href="{{ $product_url }}">
                                            @if (!empty($product['product_image']) && file_exists(public_path($product_image_path)))
                                                <img src="{{ asset($product_image_path) }}" class="img-fluid"
                                                    alt="{{ $product['product_name'] }}">
                                            @else
                                                <img src="{{ asset('front/images/product_images/small/no-image.png') }}"
                                                    class="img-fluid" alt="No Image">
                                            @endif
                                        </a>
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
                                        <a href="{{ url('product/' . $product['id']) }}">
                                            <img src="{{ asset('front/images/product_images/large/' . $product['product_image']) }}"
                                                class="img-fluid" alt="{{ $product['product_name'] }}">
                                        </a>
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
                                                    ${{ $product['product_price'] }}
                                                </div>
                                                <div class="h6 fw-bold">
                                                    ${{ $product['product_price'] - ($product['product_price'] * $product['product_discount']) / 100 }}
                                                </div>
                                            @else
                                                <div class="h6 fw-bold">${{ $product['product_price'] }}</div>
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
        <section class="py-4 bg-dark">
            <div class="container">
                <div class="add-banner">
                    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-2 row-cols-xl-4 g-4">
                        <div class="col d-flex">
                            <div class="card rounded-0 w-100 border-0 shadow-none">
                                <img src="{{ asset('assets/images/promo/04.png') }}" class="img-fluid" alt="...">
                                <div class="position-absolute top-0 end-0 m-3 product-discount"><span
                                        class="">-10%</span>
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">Sunglasses Sale</h5>
                                    <p class="card-text">See all Sunglasses and get 10% off at all Sunglasses</p> <a
                                        href="javascript:;" class="btn btn-dark btn-ecomm">SHOP BY GLASSES</a>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex">
                            <div class="card rounded-0 w-100 border-0 shadow-none">
                                <img src="{{ asset('assets/images/promo/08.png') }}" class="img-fluid" alt="...">
                                <div class="position-absolute top-0 end-0 m-3 product-discount"><span
                                        class="">-80%</span>
                                </div>
                                <div class="card-body text-center">
                                    <h5 class="card-title">Cosmetics Sales</h5>
                                    <p class="card-text">Buy Cosmetics products and get 30% off at all Cosmetics</p> <a
                                        href="javascript:;" class="btn btn-dark btn-ecomm">SHOP BY COSMETICS</a>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex">
                            <div class="card rounded-0 w-100 border-0 shadow-none">
                                <img src="{{ asset('assets/images/promo/06.png') }}" class="img-fluid h-100"
                                    alt="...">
                                <div class="card-img-overlay text-center top-20">
                                    <div class="border border-white border-2 py-3 bg-dark-3">
                                        <h5 class="card-title text-white">Fashion Summer Sale</h5>
                                        <p class="card-text text-uppercase fs-1 lh-1 mt-3 mb-2 text-white">Up to 80% off
                                        </p>
                                        <p class="card-text fs-5 text-white">On Top Fashion Brands</p> <a
                                            href="javascript:;" class="btn btn-white btn-ecomm">SHOP BY FASHION</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col d-flex">
                            <div class="card rounded-0 w-100 border-0 shadow-none">
                                <div class="position-absolute top-0 end-0 m-3 product-discount"><span
                                        class="">-50%</span>
                                </div>
                                <img src="{{ asset('assets/images/promo/07.png') }}" class="img-fluid" alt="...">
                                <div class="card-body text-center">
                                    <h5 class="card-title fs-2 fw-bold text-uppercase">Super Sale</h5>
                                    <p class="card-text text-uppercase fs-5 lh-1 mb-2">Up to 50% off</p>
                                    <p class="card-text">On All Electronic</p> <a href="javascript:;"
                                        class="btn btn-dark btn-ecomm">HURRY UP!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </section>
        <!--end Advertise banners-->
        <!--start categories-->
        <section class="py-4">
            <div class="container">
                <div class="separator pb-4">
                    <div class="line"></div>
                    <h5 class="mb-0 fw-bold separator-title">Browse Catergory</h5>
                    <div class="line"></div>
                </div>

                <div class="product-grid">
                    <div class="browse-category owl-carousel owl-theme">
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/01.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-0 text-uppercase fw-bold">Fashion</h6>
                                    <p class="mb-0 font-12 text-uppercase">10 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/02.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Watches</h6>
                                    <p class="mb-0 font-12 text-uppercase">8 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/03.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Shoes</h6>
                                    <p class="mb-0 font-12 text-uppercase">14 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/04.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Bags</h6>
                                    <p class="mb-0 font-12 text-uppercase">6 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/05.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Electronis</h6>
                                    <p class="mb-0 font-12 text-uppercase">6 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/06.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Headphones</h6>
                                    <p class="mb-0 font-12 text-uppercase">5 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/07.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Furniture</h6>
                                    <p class="mb-0 font-12 text-uppercase">20 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/08.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Jewelry</h6>
                                    <p class="mb-0 font-12 text-uppercase">16 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/09.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Sports</h6>
                                    <p class="mb-0 font-12 text-uppercase">28 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/10.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Vegetable</h6>
                                    <p class="mb-0 font-12 text-uppercase">15 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/11.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Medical</h6>
                                    <p class="mb-0 font-12 text-uppercase">24 Products</p>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0">
                                <div class="card-body p-0">
                                    <img src="{{ asset('assets/images/categories/12.png') }}" class="img-fluid"
                                        alt="...">
                                </div>
                                <div class="card-footer text-center bg-transparent border">
                                    <h6 class="mb-1 text-uppercase fw-bold">Sunglasses</h6>
                                    <p class="mb-0 font-12 text-uppercase">18 Products</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end categories-->
        <!--start support info-->
        <section class="py-5 bg-light">
            <div class="container">
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4">
                    <div class="col">
                        <div class="text-center border p-3 bg-white">
                            <div class="font-50 text-dark"><i class='bx bx-cart-add'></i>
                            </div>
                            <h5 class="fs-5 text-uppercase mb-0 fw-bold">Free delivery</h5>
                            <p class="text-capitalize">Free delivery over $199</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapib.
                            </p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center border p-3 bg-white">
                            <div class="font-50 text-dark"><i class='bx bx-credit-card'></i>
                            </div>
                            <h5 class="fs-5 text-uppercase mb-0 fw-bold">Secure payment</h5>
                            <p class="text-capitalize">We possess SSL / Secure сertificate</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapib.
                            </p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center border p-3 bg-white">
                            <div class="font-50 text-dark"> <i class='bx bx-dollar-circle'></i>
                            </div>
                            <h5 class="fs-5 text-uppercase mb-0 fw-bold">Free returns</h5>
                            <p class="text-capitalize">We return money within 30 days</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapib.
                            </p>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center border p-3 bg-white">
                            <div class="font-50 text-dark"> <i class='bx bx-support'></i>
                            </div>
                            <h5 class="fs-5 text-uppercase mb-0 fw-bold">Customer Support</h5>
                            <p class="text-capitalize">Friendly 24/7 customer support</p>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Duis nec vestibulum magna, et dapib.
                            </p>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </section>
        <!--end support info-->
        <!--start News-->
        <section class="py-4">
            <div class="container">
                <div class="pb-4 text-center">
                    <h5 class="mb-0 fw-bold text-uppercase">Latest News</h5>
                </div>
                <div class="product-grid">
                    <div class="latest-news owl-carousel owl-theme">
                        <div class="item">
                            <div class="card rounded-0 product-card border">
                                <div class="news-date">
                                    <div class="date-number">24</div>
                                    <div class="date-month">FEB</div>
                                </div>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/blogs/01.png') }}"
                                        class="card-img-top border-bottom" alt="...">
                                </a>
                                <div class="card-body">
                                    <div class="news-title">
                                        <a href="javascript:;">
                                            <h5 class="mb-3 text-capitalize">Blog Short Title</h5>
                                        </a>
                                    </div>
                                    <p class="news-content mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Cras non placerat mi. Etiam non tellus sem. Aenean...</p>
                                </div>
                                <div class="card-footer border-top bg-transparent">
                                    <a href="javascript:;" class="link-dark">0 Comments</a>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0 product-card border">
                                <div class="news-date">
                                    <div class="date-number">24</div>
                                    <div class="date-month">FEB</div>
                                </div>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/blogs/02.png') }}"
                                        class="card-img-top border-bottom" alt="...">
                                </a>
                                <div class="card-body">
                                    <div class="news-title">
                                        <a href="javascript:;">
                                            <h5 class="mb-3 text-capitalize">Blog Short Title</h5>
                                        </a>
                                    </div>
                                    <p class="news-content mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Cras non placerat mi. Etiam non tellus sem. Aenean...</p>
                                </div>
                                <div class="card-footer border-top bg-transparent">
                                    <a href="javascript:;" class="link-dark">0 Comments</a>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0 product-card border">
                                <div class="news-date">
                                    <div class="date-number">24</div>
                                    <div class="date-month">FEB</div>
                                </div>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/blogs/03.png') }}"
                                        class="card-img-top border-bottom" alt="...">
                                </a>
                                <div class="card-body">
                                    <div class="news-title">
                                        <a href="javascript:;">
                                            <h5 class="mb-3 text-capitalize">Blog Short Title</h5>
                                        </a>
                                    </div>
                                    <p class="news-content mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Cras non placerat mi. Etiam non tellus sem. Aenean...</p>
                                </div>
                                <div class="card-footer border-top bg-transparent">
                                    <a href="javascript:;" class="link-dark">0 Comments</a>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0 product-card border">
                                <div class="news-date">
                                    <div class="date-number">24</div>
                                    <div class="date-month">FEB</div>
                                </div>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/blogs/04.png') }}"
                                        class="card-img-top border-bottom" alt="...">
                                </a>
                                <div class="card-body">
                                    <div class="news-title">
                                        <a href="javascript:;">
                                            <h5 class="mb-3 text-capitalize">Blog Short Title</h5>
                                        </a>
                                    </div>
                                    <p class="news-content mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Cras non placerat mi. Etiam non tellus sem. Aenean...</p>
                                </div>
                                <div class="card-footer border-top bg-transparent">
                                    <a href="javascript:;" class="link-dark">0 Comments</a>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0 product-card border">
                                <div class="news-date">
                                    <div class="date-number">24</div>
                                    <div class="date-month">FEB</div>
                                </div>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/blogs/05.png') }}"
                                        class="card-img-top border-bottom" alt="...">
                                </a>
                                <div class="card-body">
                                    <div class="news-title">
                                        <a href="javascript:;">
                                            <h5 class="mb-3 text-capitalize">Blog Short Title</h5>
                                        </a>
                                    </div>
                                    <p class="news-content mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Cras non placerat mi. Etiam non tellus sem. Aenean...</p>
                                </div>
                                <div class="card-footer border-top bg-transparent">
                                    <a href="javascript:;" class="link-dark">0 Comments</a>
                                </div>
                            </div>
                        </div>
                        <div class="item">
                            <div class="card rounded-0 product-card border">
                                <div class="news-date">
                                    <div class="date-number">24</div>
                                    <div class="date-month">FEB</div>
                                </div>
                                <a href="javascript:;">
                                    <img src="{{ asset('assets/images/blogs/06.png') }}"
                                        class="card-img-top border-bottom" alt="...">
                                </a>
                                <div class="card-body">
                                    <div class="news-title">
                                        <a href="javascript:;">
                                            <h5 class="mb-3 text-capitalize">Blog Short Title</h5>
                                        </a>
                                    </div>
                                    <p class="news-content mb-0">Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                                        Cras non placerat mi. Etiam non tellus sem. Aenean...</p>
                                </div>
                                <div class="card-footer border-top bg-transparent">
                                    <a href="javascript:;" class="link-dark">0 Comments</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end News-->
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

        <!--start bottom products section-->
        <section class="py-4 border-top">
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
                                            <img src="{{ asset('front/images/product_images/small/' . $item['product_image']) }}"
                                                width="80" alt="{{ $item['product_name'] }}">
                                        </a>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-light mb-1 fw-bold">{{ $item['product_name'] }}</h6>
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
                                                    ${{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
                                                @else
                                                    ${{ $item['product_price'] }}
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
                                            <img src="{{ asset('front/images/product_images/small/' . $item['product_image']) }}"
                                                width="80" alt="">
                                        </a>
                                    </div>
                                    <div class="ms-0">
                                        <h6 class="mb-0 fw-light mb-1 fw-bold">{{ $item['product_name'] }}</h6>
                                        <div class="rating"> <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                            <i class="bx bxs-star text-warning"></i>
                                        </div>
                                        <p class="mb-0 pro-price">
                                            <strong>
                                                @if ($item['product_discount'] > 0)
                                                    ${{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
                                                @else
                                                    ${{ $item['product_price'] }}
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
                                            <img src="{{ asset('front/images/product_images/small/' . $item['product_image']) }}"
                                                width="80" alt="{{ $item['product_name'] }}">
                                        </a>
                                    </div>
                                    <div class="ms-0">
                                        <h6 class="mb-0 fw-light mb-1 fw-bold">{{ $item['product_name'] }}</h6>
                                        <div class="rating">
                                            @for ($i = 0; $i < 5; $i++)
                                                <i class="bx bxs-star text-warning"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-0 pro-price">
                                            <strong>
                                                @if ($item['product_discount'] > 0)
                                                    ${{ $item['product_price'] - ($item['product_price'] * $item['product_discount']) / 100 }}
                                                @else
                                                    ${{ $item['product_price'] }}
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
											<img src="{{ asset('front/images/product_images/small/' . $item['product_image']) }}" width="80" alt="{{ $item['product_name'] }}">
										</a>
									</div>
									<div class="ms-0">
										<h6 class="mb-0 fw-light mb-1 fw-bold">{{ $item['product_name'] }}</h6>
										<div class="rating">
											@for ($i = 0; $i < 5; $i++)
												<i class="bx bxs-star text-warning"></i>
											@endfor
										</div>
										<p class="mb-0 pro-price">
											<strong>
												@if ($item['product_discount'] > 0)
													${{ $item['product_price'] - ($item['product_price'] * $item['product_discount'] / 100) }}
												@else
													${{ $item['product_price'] }}
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
    </div>
    <!-- Site-Priorities /- -->
@endsection
