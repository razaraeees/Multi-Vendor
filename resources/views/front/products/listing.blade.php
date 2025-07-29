@extends('front.layout.layout')



@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <<section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pe-3">
                        {{-- Heading ke liye sirf last breadcrumb ka title dikhayenge --}}
                        {{ $breadcrumbs[count($breadcrumbs) - 1]['title'] ?? 'Shop' }}
                    </h3>
                    <div class="ms-auto">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-0 p-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ url('/') }}"><i class="bx bx-home-alt"></i> Home</a>
                                </li>
                                @foreach ($breadcrumbs as $breadcrumb)
                                    <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                        @if (!$loop->last)
                                            <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                        @else
                                            {{ $breadcrumb['title'] }}
                                        @endif
                                    </li>
                                @endforeach
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
                                    <div id="product-listing">
                                        @include('front.products.ajax_products_listing')
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
