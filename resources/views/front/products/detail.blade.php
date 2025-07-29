@extends('front.layout.layout')


@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">{{ $productDetails['product_name'] }}</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}"><i class="bx bx-home-alt"></i> Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ url('/shop') }}">Shop</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $productDetails['product_name'] }}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!--end breadcrumb-->
        <!--start product detail-->
        <section class="py-4">
            <div class="container">
                <div class="product-detail-card">
                    <div class="product-detail-body">
                        <div class="row g-0">
                            <div class="col-12 col-lg-5">
                                <div class="image-zoom-section">
                                    <div class="product-gallery owl-carousel owl-theme border mb-3 p-3" data-slider-id="1">
                                        {{-- Main product image --}}
                                        <div class="item">
                                            <a href="{{ asset('front/images/product_images/large/' . $productDetails['product_image']) }}"
                                                class="easyzoom"
                                                data-standard="{{ asset('front/images/product_images/small/' . $productDetails['product_image']) }}">
                                                <img src="{{ asset('front/images/product_images/large/' . $productDetails['product_image']) }}"
                                                    class="img-fluid" alt="{{ $productDetails['product_name'] }}">
                                            </a>
                                        </div>

                                        {{-- Alternative images --}}
                                        @foreach ($productDetails['images'] as $image)
                                            <div class="item">
                                                <a href="{{ asset('front/images/product_images/large/' . $image['image']) }}"
                                                    class="easyzoom"
                                                    data-standard="{{ asset('front/images/product_images/small/' . $image['image']) }}">
                                                    <img src="{{ asset('front/images/product_images/large/' . $image['image']) }}"
                                                        class="img-fluid" alt="{{ $productDetails['product_name'] }}">
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="owl-thumbs d-flex justify-content-center mt-2" data-slider-id="1">

                                        {{-- Main thumbnail --}}
                                        <button class="owl-thumb-item border-0 bg-transparent p-0">
                                            <img src="{{ asset('front/images/product_images/small/' . $productDetails['product_image']) }}"
                                                width="60" alt="{{ $productDetails['product_name'] }}">
                                        </button>

                                        {{-- Alternative thumbnails --}}
                                        @foreach ($productDetails['images'] as $image)
                                            <button class="owl-thumb-item border-0 bg-transparent p-0">
                                                <img src="{{ asset('front/images/product_images/small/' . $image['image']) }}"
                                                    width="60" alt="{{ $productDetails['product_name'] }}">
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7">
                                <div class="product-info-section p-3">
                                    <h3 class="mt-3 mt-lg-0 mb-0">{{ $productDetails['product_name'] }}</h3>

                                    <div class="product-rating d-flex align-items-center mt-2">
                                        <div class="rates cursor-pointer font-13">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i
                                                    class="bx bxs-star {{ $i <= $avgStarRating ? 'text-warning' : 'text-light-4' }}"></i>
                                            @endfor
                                        </div>
                                        <div class="ms-1">
                                            <p class="mb-0">({{ count($ratings) }} Ratings)</p>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-center mt-3 gap-2">
                                        @if ($productDetails['product_discount'] > 0)
                                            <h5 class="mb-0 text-decoration-line-through text-light-3">
                                                ${{ $productDetails['product_price'] }}</h5>
                                            <h4 class="mb-0">
                                                ${{ $productDetails['product_price'] - ($productDetails['product_price'] * $productDetails['product_discount']) / 100 }}
                                            </h4>
                                        @else
                                            <h4 class="mb-0">${{ $productDetails['product_price'] }}</h4>
                                        @endif
                                    </div>

                                    <div class="mt-3">
                                        <h6>Description :</h6>
                                        <p class="mb-0">{{ $productDetails['description'] }}</p>
                                    </div>

                                    <dl class="row mt-3">
                                        <dt class="col-sm-3">Product ID</dt>
                                        <dd class="col-sm-9">{{ $productDetails['id'] }}</dd>
                                        <dt class="col-sm-3">Delivery</dt>
                                        <dd class="col-sm-9">{{ $productDetails['delivery_info'] ?? 'Worldwide' }}</dd>
                                    </dl>


                                    <form action="{{ url('cart/add') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $productDetails['id'] }}">
                                        <div class="row row-cols-auto align-items-center mt-3">
                                            <!-- Quantity -->
                                            <div class="col">
                                                <label class="form-label">Quantity</label>
                                                <select class="form-select form-select-sm" name="quantity" required>
                                                    <option value="">Select Qty</option>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                            </div>

                                            <!-- Size -->
                                            <div class="col">
                                                <label class="form-label">Size</label>
                                                <select class="form-select form-select-sm" name="size" required>
                                                    <option value="">Select Size</option>
                                                    @foreach ($productDetails['attributes'] as $attribute)
                                                        <option value="{{ $attribute['size'] }}">{{ $attribute['size'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Colors (readonly thumbnails) -->
                                            <div class="col">
                                                <label class="form-label">Colors</label>
                                                <div class="color-indigators d-flex align-items-center gap-2">
                                                    @foreach ($groupProducts as $gp)
                                                        <a href="{{ url('product/' . $gp['id']) }}">
                                                            <div class="color-indigator-item"
                                                                style="background-image: url('{{ asset('front/images/product_images/small/' . $gp['product_image']) }}'); background-size: cover;">
                                                            </div>
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Buttons -->
                                        <div class="d-flex gap-2 mt-3">
                                            <button type="submit" class="btn btn-dark btn-ecomm">
                                                <i class="bx bxs-cart-add"></i> Add to Cart
                                            </button>
                                            <a href="javascript:;" class="btn btn-light btn-ecomm">
                                                <i class="bx bx-heart"></i> Add to Wishlist
                                            </a>
                                        </div>
                                    </form>


                                    <hr />

                                    <!-- Social Sharing -->
                                    <div class="product-sharing">
                                        <div class="d-flex align-items-center gap-2 flex-wrap">
                                            <button type="button" class="btn-social bg-twitter"><i
                                                    class='bx bxl-twitter'></i></button>
                                            <button type="button" class="btn-social bg-facebook"><i
                                                    class='bx bxl-facebook'></i></button>
                                            <button type="button" class="btn-social bg-linkedin"><i
                                                    class='bx bxl-linkedin'></i></button>
                                            <button type="button" class="btn-social bg-youtube"><i
                                                    class='bx bxl-youtube'></i></button>
                                            <button type="button" class="btn-social bg-pinterest"><i
                                                    class='bx bxl-pinterest'></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--end row-->
                    </div>
                </div>
            </div>
        </section>
        <!--end product detail-->
        <!--start product more info-->
        <section class="py-4">
            <div class="container">
                <div class="product-more-info">
                    <ul class="nav nav-tabs mb-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#discription">
                                <div class="d-flex align-items-center">
                                    <div class="tab-title text-uppercase fw-500">Description</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#more-info">
                                <div class="d-flex align-items-center">
                                    <div class="tab-title text-uppercase fw-500">More Info</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tags">
                                <div class="d-flex align-items-center">
                                    <div class="tab-title text-uppercase fw-500">Tags</div>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#reviews">
                                <div class="d-flex align-items-center">
                                    <div class="tab-title text-uppercase fw-500">(3) Reviews</div>
                                </div>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content pt-3">
                        <div class="tab-pane fade" id="discription">
                            <p>Raw denim you probably haven't heard of them jean shorts Austin. Nesciunt tofu stumptown
                                aliqua, retro synth master cleanse. Mustache cliche tempor, williamsburg carles vegan
                                helvetica. Reprehenderit butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh
                                mi, qui irure terry richardson ex squid. Aliquip placeat salvia cillum iphone. Seitan
                                aliquip quis cardigan american apparel, butcher voluptate nisi.</p>
                            <ul>
                                <li>Not just for commute</li>
                                <li>Branded tongue and cuff</li>
                                <li>Super fast and amazing</li>
                                <li>Lorem sed do eiusmod tempor</li>
                            </ul>
                            <p class="mb-1">Cosby sweater eu banh mi, qui irure terry richardson ex squid. Aliquip
                                placeat salvia cillum iphone.</p>
                            <p class="mb-1">Seitan aliquip quis cardigan american apparel, butcher voluptate nisi.</p>
                        </div>
                        <div class="tab-pane fade" id="more-info">
                            <p>Food truck fixie locavore, accusamus mcsweeney's marfa nulla single-origin coffee squid.
                                Exercitation +1 labore velit, blog sartorial PBR leggings next level wes anderson artisan
                                four loko farm-to-table craft beer twee. Qui photo booth letterpress, commodo enim craft
                                beer mlkshk aliquip jean shorts ullamco ad vinyl cillum PBR. Homo nostrud organic, assumenda
                                labore aesthetic magna delectus mollit. Keytar helvetica VHS salvia yr, vero magna velit
                                sapiente labore stumptown. Vegan fanny pack odio cillum wes anderson 8-bit, sustainable jean
                                shorts beard ut DIY ethical culpa terry richardson biodiesel. Art party scenester stumptown,
                                tumblr butcher vero sint qui sapiente accusamus tattooed echo park.</p>
                        </div>
                        <div class="tab-pane fade" id="tags">
                            <div class="tags-box d-flex flex-wrap gap-2">
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Cloths</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Electronis</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Furniture</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Sports</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Men Wear</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Women Wear</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Laptops</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Formal Shirts</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Topwear</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Headphones</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Bottom Wear</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Bags</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Sofa</a>
                                <a href="javascript:;" class="btn btn-ecomm btn-outline-dark">Shoes</a>
                            </div>
                        </div>
                        <div class="tab-pane fade show active" id="reviews">
                            <div class="row">
                                <div class="col col-lg-8">
                                    <div class="product-review">
                                        <h5 class="mb-4">3 Reviews For The Product</h5>
                                        <div class="review-list">
                                            <div class="d-flex align-items-start">
                                                <div class="review-user">
                                                    <img src="assets/images/avatars/avatar-1.png" width="65"
                                                        height="65" class="rounded-circle" alt="" />
                                                </div>
                                                <div class="review-content ms-3">
                                                    <div class="rates cursor-pointer fs-6">
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0">James Caviness</h6>
                                                        <p class="mb-0 ms-auto">February 16, 2021</p>
                                                    </div>
                                                    <p>Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache
                                                        cliche tempor, williamsburg carles vegan helvetica. Reprehenderit
                                                        butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi,
                                                        qui irure terry richardson ex squid. Aliquip placeat salvia cillum
                                                        iphone. Seitan aliquip quis cardigan</p>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="d-flex align-items-start">
                                                <div class="review-user">
                                                    <img src="assets/images/avatars/avatar-2.png" width="65"
                                                        height="65" class="rounded-circle" alt="" />
                                                </div>
                                                <div class="review-content ms-3">
                                                    <div class="rates cursor-pointer fs-6">
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0">David Buckley</h6>
                                                        <p class="mb-0 ms-auto">February 22, 2021</p>
                                                    </div>
                                                    <p>Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache
                                                        cliche tempor, williamsburg carles vegan helvetica. Reprehenderit
                                                        butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi,
                                                        qui irure terry richardson ex squid. Aliquip placeat salvia cillum
                                                        iphone. Seitan aliquip quis cardigan</p>
                                                </div>
                                            </div>
                                            <hr />
                                            <div class="d-flex align-items-start">
                                                <div class="review-user">
                                                    <img src="assets/images/avatars/avatar-3.png" width="65"
                                                        height="65" class="rounded-circle" alt="" />
                                                </div>
                                                <div class="review-content ms-3">
                                                    <div class="rates cursor-pointer fs-6">
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                        <i class="bx bxs-star text-warning"></i>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0">Peter Costanzo</h6>
                                                        <p class="mb-0 ms-auto">February 26, 2021</p>
                                                    </div>
                                                    <p>Nesciunt tofu stumptown aliqua, retro synth master cleanse. Mustache
                                                        cliche tempor, williamsburg carles vegan helvetica. Reprehenderit
                                                        butcher retro keffiyeh dreamcatcher synth. Cosby sweater eu banh mi,
                                                        qui irure terry richardson ex squid. Aliquip placeat salvia cillum
                                                        iphone. Seitan aliquip quis cardigan</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-lg-4">
                                    <div class="add-review border">
                                        <div class="form-body p-3">
                                            <h4 class="mb-4">Write a Review</h4>
                                            <div class="mb-3">
                                                <label class="form-label">Your Name</label>
                                                <input type="text" class="form-control rounded-0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Your Email</label>
                                                <input type="text" class="form-control rounded-0">
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Rating</label>
                                                <select class="form-select rounded-0">
                                                    <option selected>Choose Rating</option>
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="3">4</option>
                                                    <option value="3">5</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Example textarea</label>
                                                <textarea class="form-control rounded-0" rows="3"></textarea>
                                            </div>
                                            <div class="d-grid">
                                                <button type="button" class="btn btn-dark btn-ecomm">Submit a
                                                    Review</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!--end row-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end product more info-->
        <!--start similar products-->
        <section class="py-4">
            <div class="container">
                <div class="separator pb-4">
                    <div class="line"></div>
                    <h5 class="mb-0 fw-bold separator-title">Similar Products</h5>
                    <div class="line"></div>
                </div>
                <div class="product-grid">
                    <div class="similar-products owl-carousel owl-theme position-relative">
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
                                    <a href="javascript:;">
                                        <img src="assets/images/similar-products/01.png" class="img-fluid"
                                            alt="...">
                                    </a>
                                </div>
                                <div class="card-body px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">
                                            <p class="mb-1 product-short-name">Topwear</p>
                                            <h6 class="mb-0 fw-bold product-short-title">White Polo Shirt</h6>
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
                                    <div class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                        <div class="h6 fw-light fw-bold text-secondary text-decoration-line-through">$59.00
                                        </div>
                                        <div class="h6 fw-bold">$48.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <a href="javascript:;">
                                        <img src="assets/images/similar-products/02.png" class="img-fluid"
                                            alt="...">
                                    </a>
                                </div>
                                <div class="card-body px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">
                                            <p class="mb-1 product-short-name">Topwear</p>
                                            <h6 class="mb-0 fw-bold product-short-title">White Polo Shirt</h6>
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
                                    <div class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                        <div class="h6 fw-light fw-bold text-secondary text-decoration-line-through">$59.00
                                        </div>
                                        <div class="h6 fw-bold">$48.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <a href="javascript:;">
                                        <img src="assets/images/similar-products/03.png" class="img-fluid"
                                            alt="...">
                                    </a>
                                </div>
                                <div class="card-body px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">
                                            <p class="mb-1 product-short-name">Topwear</p>
                                            <h6 class="mb-0 fw-bold product-short-title">White Polo Shirt</h6>
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
                                    <div class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                        <div class="h6 fw-light fw-bold text-secondary text-decoration-line-through">$59.00
                                        </div>
                                        <div class="h6 fw-bold">$48.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <a href="javascript:;">
                                        <img src="assets/images/similar-products/04.png" class="img-fluid"
                                            alt="...">
                                    </a>
                                </div>
                                <div class="card-body px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">
                                            <p class="mb-1 product-short-name">Topwear</p>
                                            <h6 class="mb-0 fw-bold product-short-title">White Polo Shirt</h6>
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
                                    <div class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                        <div class="h6 fw-light fw-bold text-secondary text-decoration-line-through">$59.00
                                        </div>
                                        <div class="h6 fw-bold">$48.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <a href="javascript:;">
                                        <img src="assets/images/similar-products/05.png" class="img-fluid"
                                            alt="...">
                                    </a>
                                </div>
                                <div class="card-body px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">
                                            <p class="mb-1 product-short-name">Topwear</p>
                                            <h6 class="mb-0 fw-bold product-short-title">White Polo Shirt</h6>
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
                                    <div class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                        <div class="h6 fw-light fw-bold text-secondary text-decoration-line-through">$59.00
                                        </div>
                                        <div class="h6 fw-bold">$48.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <a href="javascript:;">
                                        <img src="assets/images/similar-products/06.png" class="img-fluid"
                                            alt="...">
                                    </a>
                                </div>
                                <div class="card-body px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">
                                            <p class="mb-1 product-short-name">Topwear</p>
                                            <h6 class="mb-0 fw-bold product-short-title">White Polo Shirt</h6>
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
                                    <div class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                        <div class="h6 fw-light fw-bold text-secondary text-decoration-line-through">$59.00
                                        </div>
                                        <div class="h6 fw-bold">$48.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                    <a href="javascript:;">
                                        <img src="assets/images/similar-products/07.png" class="img-fluid"
                                            alt="...">
                                    </a>
                                </div>
                                <div class="card-body px-0">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="">
                                            <p class="mb-1 product-short-name">Topwear</p>
                                            <h6 class="mb-0 fw-bold product-short-title">White Polo Shirt</h6>
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
                                    <div class="product-price d-flex align-items-center justify-content-start gap-2 mt-2">
                                        <div class="h6 fw-light fw-bold text-secondary text-decoration-line-through">$59.00
                                        </div>
                                        <div class="h6 fw-bold">$48.00</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!--end similar products-->
    </div>
@endsection
