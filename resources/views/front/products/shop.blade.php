@extends('front.layout.layout')

@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">Shop Grid Left Sidebar</h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i> Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="javascript:;">Shop</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Shop Left Sidebar</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </section>
        <!--end breadcrumb-->
        <!--start shop area-->
        <section class="py-4">
            <div class="container">
                <div class="btn btn-dark btn-ecomm d-xl-none position-fixed top-50 start-0 translate-middle-y z-index-1"
                    data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbarFilter"><span><i
                            class='bx bx-filter-alt me-1'></i>Filters</span></div>
                <div class="row">
                    @include('front.products.filters')
                    <div class="col-12 col-xl-9">
                        <div class="product-wrapper">
                            <div class="toolbox d-flex align-items-center mb-3 gap-2 border p-3">
                                <div class="d-flex flex-wrap flex-grow-1 gap-1">
                                    <div class="d-flex align-items-center flex-nowrap">
                                        <p class="mb-0 font-13 text-nowrap">Sort By:</p>
                                        <select class="form-select ms-3 rounded-0">
                                            <option value="menu_order" selected="selected">Default sorting</option>
                                            <option value="popularity">Sort by popularity</option>
                                            <option value="rating">Sort by average rating</option>
                                            <option value="date">Sort by newness</option>
                                            <option value="price">Sort by price: low to high</option>
                                            <option value="price-desc">Sort by price: high to low</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <div class="d-flex align-items-center flex-nowrap">
                                        <p class="mb-0 font-13 text-nowrap">Show:</p>
                                        <select class="form-select ms-3 rounded-0">
                                            <option>9</option>
                                            <option>12</option>
                                            <option>16</option>
                                            <option>20</option>
                                            <option>50</option>
                                            <option>100</option>
                                        </select>
                                    </div>
                                </div>
                                <div> <a href="shop-grid-left-sidebar.html" class="btn btn-white rounded-0"><i
                                            class='bx bxs-grid me-0'></i></a>
                                </div>
                                <div> <a href="shop-list-left-sidebar.html" class="btn btn-light rounded-0"><i
                                            class='bx bx-list-ul me-0'></i></a>
                                </div>
                            </div>

                            <div class="product-grid">
                                <div
                                    class="row row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-3 g-3 g-sm-4">
                                    @foreach ($categoryProducts as $product)
                                        <div class="col">
                                            <div class="card h-100 shadow-sm border-0">
                                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                                    <div class="add-cart position-absolute top-0 end-0 mt-2 me-2 z-10">
                                                        <a href="javascript:;"><i
                                                                class='bx bx-cart-add fs-4 text-dark'></i></a>
                                                    </div>
                                                    <div
                                                        class="quick-view position-absolute start-0 bottom-0 w-100 text-center bg-dark bg-opacity-50 py-2">
                                                        <a href="javascript:;" class="text-white text-decoration-none"
                                                            data-bs-toggle="modal" data-bs-target="#QuickViewProduct">Quick
                                                            View</a>
                                                    </div>
                                                    <a href="{{ url('product/' . $product->id) }}">
                                                        <img src="{{ asset('front/images/product_images/small/' . ($product->product_image ?? 'no-image.png')) }}"
                                                            alt="{{ $product->product_name }}"
                                                            class="img-fluid h-100 w-100 object-fit-cover">
                                                    </a>
                                                </div>
                                                <div class="card-body px-2">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div>
                                                            <p class="mb-1 text-muted small">
                                                                {{ $product->brand->name ?? 'Brand' }}</p>
                                                            <h6 class="mb-0 fw-bold">{{ $product->product_name }}</h6>
                                                        </div>
                                                        <div class="icon-wishlist">
                                                            <a href="javascript:;"><i class="bx bx-heart"></i></a>
                                                        </div>
                                                    </div>

                                                    {{-- Rating --}}
                                                    @php
                                                        $averageRating = round($product->ratings->avg('rating'));
                                                    @endphp
                                                    <div class="rating mt-2 text-warning small">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            @if ($i <= $averageRating)
                                                                <i class="bx bxs-star"></i>
                                                            @else
                                                                <i class="bx bx-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>

                                                    {{-- Price --}}
                                                    <div class="product-price d-flex gap-2 mt-2">
                                                        @if ($product->product_discount > 0)
                                                            <span
                                                                class="text-secondary text-decoration-line-through">${{ $product->product_price }}</span>
                                                            <span class="fw-bold text-success">
                                                                ${{ number_format($product->product_price - ($product->product_price * $product->product_discount) / 100, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="fw-bold">${{ $product->product_price }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <hr>
                            <nav class="d-flex justify-content-between" aria-label="Page navigation">
                                {{ $categoryProducts->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </section>
        <!--end shop area-->
    </div>
@endsection
