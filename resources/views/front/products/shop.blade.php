@extends('front.layout.layout')

@section('content')
    <div class="page-content">
        <!--start breadcrumb-->
        <section class="py-3 border-bottom border-top d-none d-md-flex bg-light">
            <div class="container">
                <div class="page-breadcrumb d-flex align-items-center">
                    <h3 class="breadcrumb-title pr-3">Shop Grid Left Sidebar</h3>
                    <div class="ml-auto">
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
                <div class="btn btn-dark btn-ecomm d-xl-none position-fixed"
                    style="top: 50%; left: 0; transform: translateY(-50%); z-index: 1030;" data-toggle="offcanvas"
                    data-target="#offcanvasNavbarFilter">
                    <span><i class='bx bx-filter-alt mr-1'></i>Filters</span>
                </div>
                <div class="row">
                    @include('front.products.filters')
                    <div class="col-12 col-xl-9">
                        <div class="product-wrapper">
                            <div class="toolbox d-flex align-items-center mb-3 border p-3">
                                <div class="d-flex flex-wrap flex-grow-1">
                                    <div class="d-flex align-items-center flex-nowrap mr-3">
                                        <p class="mb-0 small text-nowrap">Sort By:</p>
                                        <select class="form-control form-control-sm ml-3">
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
                                    <div class="d-flex align-items-center flex-nowrap mr-3">
                                        <p class="mb-0 small text-nowrap">Show:</p>
                                        <select class="form-control form-control-sm ml-3">
                                            <option>9</option>
                                            <option>12</option>
                                            <option>16</option>
                                            <option>20</option>
                                            <option>50</option>
                                            <option>100</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="mr-1">
                                    <a href="shop-grid-left-sidebar.html" class="btn btn-light">
                                        <i class='bx bxs-grid'></i>
                                    </a>
                                </div>
                                <div>
                                    <a href="shop-list-left-sidebar.html" class="btn btn-outline-secondary">
                                        <i class='bx bx-list-ul'></i>
                                    </a>
                                </div>
                            </div>

                            <div class="product-grid">
                                <div class="row">
                                    @foreach ($categoryProducts as $product)
                                        <div class="col-6 col-md-4 col-lg-4 col-xl-4">
                                            <div class="card h-100 shadow-sm border-0 mb-4">
                                                <div class="position-relative overflow-hidden" style="height: 250px;">
                                                    <div class="add-cart position-absolute"
                                                        style="top: 8px; right: 8px; z-index: 10;">
                                                        <a href="javascript:;" class="text-dark">
                                                            <i class='bx bx-cart-add' style="font-size: 1.5rem;"></i>
                                                        </a>
                                                    </div>
                                                    <div class="quick-view position-absolute w-100"
                                                        style="bottom: 0; left: 0;">
                                                        <button type="button"
                                                            class="quickview-btn btn btn-dark btn-sm btn-block"
                                                            data-product-id="{{ $product['id'] }}">
                                                            Quick View
                                                        </button>
                                                    </div>
                                                    <a href="{{ url('product/' . $product->id) }}">
                                                        <img src="{{ asset('front/images/product_images/small/' . ($product->product_image ?? 'no-image.png')) }}"
                                                            alt="{{ $product->product_name }}" class="img-fluid h-100 w-100"
                                                            style="object-fit: cover;">
                                                    </a>
                                                </div>
                                                <div class="card-body px-2">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="flex-grow-1">
                                                            <p class="mb-1 text-muted small">
                                                                {{ $product->brand->name ?? 'Brand' }}</p>
                                                            <h6 class="mb-0 font-weight-bold">{{ $product->product_name }}
                                                            </h6>
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
                                                    <div class="product-price d-flex mt-2">
                                                        @if ($product->product_discount > 0)
                                                            <span class="text-secondary mr-2"
                                                                style="text-decoration: line-through;">
                                                                ${{ $product->product_price }}
                                                            </span>
                                                            <span class="font-weight-bold text-success">
                                                                ${{ number_format($product->product_price - ($product->product_price * $product->product_discount) / 100, 2) }}
                                                            </span>
                                                        @else
                                                            <span
                                                                class="font-weight-bold">${{ $product->product_price }}</span>
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
                                {{ $categoryProducts->appends(request()->query())->links('vendor.pagination.bootstrap-4') }}
                            </nav>
                        </div>
                    </div>
                </div>
                <!--end row-->
            </div>
        </section>

        {{-- Bootstrap 5.2 Modal --}}
        <div class="modal fade" id="QuickViewProduct" tabindex="-1" aria-labelledby="QuickViewProductLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <!-- Loading state will be replaced dynamically -->
                    <div class="modal-body">
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3">Loading product details...</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
        <!--end shop area-->
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('QuickViewProduct');
            const modal = new bootstrap.Modal(modalElement);

            // QuickView Modal Handler
            $(document).on('click', '.quickview-btn', function(e) {
                e.preventDefault();

                const productId = $(this).data('product-id');

                if (!productId) {
                    alert('Product ID not found');
                    return;
                }

                // Show loading state
                $('.modal-body', modalElement).html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading product details...</p>
                </div>
            `);

                // Show modal
                modal.show();

                $.ajax({
                    url: '/product/quickview/' + productId,
                    type: 'GET',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            const product = response.productDetails;
                            const images = product.images || [];
                            const attributes = response.groupedAttributes || {};
                            const category = response.categoryDetails || {};

                            let galleryHtml = '';
                            let thumbsHtml = '';

                            if (images.length > 0) {
                                images.forEach(function(image) {
                                    galleryHtml += `
                                    <div class="item">
                                        <img src="/front/images/product_images/large/${image.image}" 
                                             class="img-fluid" alt="${product.product_name}">
                                    </div>`;
                                    thumbsHtml += `
                                    <button class="owl-thumb-item">
                                        <img src="/front/images/product_images/small/${image.image}" 
                                             alt="${product.product_name}" class="img-fluid">
                                    </button>`;
                                });
                            } else {
                                galleryHtml =
                                    `<div class="item"><img src="/assets/images/no-product-image.png" class="img-fluid" alt="No Image"></div>`;
                                thumbsHtml =
                                    `<button class="owl-thumb-item"><img src="/assets/images/no-product-image.png" class="img-fluid" alt="No Image"></button>`;
                            }

                            // Size options
                            let sizeOptions = '';
                            if (attributes["Size"] && attributes["Size"].values.length > 0) {
                                attributes["Size"].values.forEach(function(val) {
                                    sizeOptions +=
                                        `<option value="${val.value}">${val.value}</option>`;
                                });
                            } else {
                                sizeOptions = '<option>One Size</option>';
                            }

                            // Price
                            const originalPrice = parseFloat(product.product_price);
                            const finalPrice = parseFloat(product.discounted_price);
                            let priceHtml = '';
                            if (!isNaN(finalPrice) && finalPrice < originalPrice) {
                                priceHtml = `
                                <h5 class="mb-0 text-decoration-line-through text-muted">Rs. ${originalPrice.toFixed(2)}</h5>
                                <h4 class="mb-0 text-danger">Rs. ${finalPrice.toFixed(2)}</h4>`;
                            } else {
                                priceHtml =
                                    `<h4 class="mb-0">Rs. ${originalPrice.toFixed(2)}</h4>`;
                            }

                            // Modal Content
                            const modalHtml = `
                            <div class="row g-0">
                                <div class="col-lg-6">
                                    <div class="image-zoom-section p-3">
                                        <div class="product-gallery owl-carousel owl-theme border mb-3">
                                            ${galleryHtml}
                                        </div>
                                        <div class="owl-thumbs d-flex justify-content-center flex-wrap">
                                            ${thumbsHtml}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="product-info-section p-3">
                                        <h3 class="mt-3 mt-lg-0 mb-0">${product.product_name}</h3>
                                        <div class="product-rating d-flex align-items-center mt-2">
                                            <div class="rates text-warning font-13">
                                                <i class="bx bxs-star"></i><i class="bx bxs-star"></i>
                                                <i class="bx bxs-star"></i><i class="bx bxs-star"></i><i class="bx bxs-star"></i>
                                            </div>
                                            <div class="ms-1">
                                                <small>(${response.ratings.length || 0} Reviews)</small>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mt-3" style="gap: 0.5rem;">
                                            ${priceHtml}
                                        </div>
                                        <div class="mt-3">
                                            <h6>Description:</h6>
                                            <p>${product.description || 'No description available.'}</p>
                                        </div>
                                        <dl class="row mt-3">
                                            <dt class="col-sm-3">Brand</dt>
                                            <dd class="col-sm-9">${product.brand?.name || 'N/A'}</dd>
                                            <dt class="col-sm-3">Stock</dt>
                                            <dd class="col-sm-9">${product.stock_status || 'N/A'}</dd>
                                            <dt class="col-sm-3">Status</dt>
                                            <dd class="col-sm-9">
                                                <span class="badge ${product.is_featured ? 'bg-success' : 'bg-secondary'}">
                                                    ${product.is_featured ? 'Featured' : 'Regular'}
                                                </span>
                                            </dd>
                                        </dl>
                                        <div class="row align-items-end mt-3">
                                            <div class="col-auto">
                                                <label class="form-label mb-1">Quantity</label>
                                                <select class="form-select form-select-sm" id="quickview-quantity">
                                                    <option value="1">1</option>
                                                    <option value="2">2</option>
                                                    <option value="3">3</option>
                                                    <option value="4">4</option>
                                                    <option value="5">5</option>
                                                </select>
                                            </div>
                                            <div class="col-auto">
                                                <label class="form-label mb-1">Size</label>
                                                <select class="form-select form-select-sm" id="quickview-size">
                                                    ${sizeOptions}
                                                </select>
                                            </div>
                                        </div>
                                        <div class="d-flex mt-4" style="gap: 0.5rem;">
                                            <button class="btn btn-dark add-to-cart-quickview" 
                                                    data-product-id="${product.id}">
                                                <i class="bx bxs-cart-add me-1"></i> Add to Cart
                                            </button>
                                            <button class="btn btn-outline-dark add-to-wishlist-quickview"
                                                    data-product-id="${product.id}">
                                                <i class="bx bx-heart me-1"></i> Add to Wishlist
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                            // Inject content
                            $('.modal-body', modalElement).html(modalHtml);

                            // Initialize Owl Carousel
                            setTimeout(() => {
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
                            $('.modal-body', modalElement).html(`
                            <div class="text-center py-5">
                                <i class="bx bx-error-circle text-danger" style="font-size: 48px;"></i>
                                <h5 class="mt-3">Error</h5>
                                <p>${response.message || 'Unable to load product details.'}</p>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        `);
                        }
                    },
                    error: function(xhr) {
                        console.error('AJAX Error:', xhr.responseText);
                        $('.modal-body', modalElement).html(`
                        <div class="text-center py-5">
                            <i class="bx bx-wifi-off text-warning" style="font-size: 48px;"></i>
                            <h5 class="mt-3">Network Error</h5>
                            <p>Failed to load product.</p>
                            <button type="button" class="btn btn-primary me-2" onclick="location.reload()">Retry</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    `);
                    }
                });
            });

            // Add to Cart from QuickView
            $(document).on('click', '.add-to-cart-quickview', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                const quantity = $('#quickview-quantity').val() || 1;
                const size = $('#quickview-size').val();
                const button = $(this);

                button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Adding...');

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
                                .html('<i class="bx bx-check me-1"></i> Added!');
                            if (response.cart_count) $('.cart-count').text(response.cart_count);
                            showNotification('Added to cart!', 'success');
                            setTimeout(() => {
                                button.prop('disabled', false).removeClass(
                                        'btn-success').addClass('btn-dark')
                                    .html(
                                        '<i class="bx bxs-cart-add me-1"></i> Add to Cart'
                                        );
                            }, 2000);
                        } else {
                            button.prop('disabled', false).html(
                                '<i class="bx bxs-cart-add me-1"></i> Add to Cart');
                            showNotification(response.message || 'Failed', 'error');
                        }
                    },
                    error: function() {
                        button.prop('disabled', false).html(
                            '<i class="bx bxs-cart-add me-1"></i> Add to Cart');
                        showNotification('Error adding to cart', 'error');
                    }
                });
            });

            // Add to Wishlist from QuickView
            $(document).on('click', '.add-to-wishlist-quickview', function(e) {
                e.preventDefault();
                const productId = $(this).data('product-id');
                const button = $(this);

                button.prop('disabled', true).html(
                    '<span class="spinner-border spinner-border-sm me-1"></span> Adding...');

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
                                .html('<i class="bx bxs-heart me-1"></i> In Wishlist');
                            showNotification('Added to wishlist!', 'success');
                        } else {
                            button.prop('disabled', false).html(
                                '<i class="bx bx-heart me-1"></i> Add to Wishlist');
                            showNotification(response.message || 'Failed', 'error');
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 401) {
                            showNotification('Please login first.', 'error');
                            setTimeout(() => window.location.href = '/login', 1500);
                        } else {
                            button.prop('disabled', false).html(
                                '<i class="bx bx-heart me-1"></i> Add to Wishlist');
                            showNotification('Error', 'error');
                        }
                    }
                });
            });

            // Notification function
            function showNotification(message, type) {
                const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
                const alert = $(`
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed top-0 end-0 m-3" 
                     style="z-index: 9999; min-width: 300px;" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `);
                $('body').append(alert);
                setTimeout(() => alert.alert('close'), 5000);
            }

            // Clean up modal on close
            modalElement.addEventListener('hidden.bs.modal', function() {
                $('.modal-body', this).html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-3">Loading product details...</p>
                </div>
            `);
                if ($('.product-gallery').hasClass('owl-loaded')) {
                    $('.product-gallery').trigger('destroy.owl.carousel').removeClass('owl-loaded');
                }
            });

            // Thumbnail click to change slide
            $(document).on('click', '.owl-thumb-item', function() {
                const index = $(this).index();
                $('.product-gallery').trigger('to.owl.carousel', [index, 300]);
            });
        });
    </script>
@endsection
