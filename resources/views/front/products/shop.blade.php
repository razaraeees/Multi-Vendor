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
                    style="top: 50%; left: 0; transform: translateY(-50%); z-index: 1030;"
                    data-toggle="offcanvas" data-target="#offcanvasNavbarFilter">
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
                                                    <div class="add-cart position-absolute" style="top: 8px; right: 8px; z-index: 10;">
                                                        <a href="javascript:;" class="text-dark">
                                                            <i class='bx bx-cart-add' style="font-size: 1.5rem;"></i>
                                                        </a>
                                                    </div>
                                                    <div class="quick-view position-absolute w-100" style="bottom: 0; left: 0;">
                                                        <button type="button" class="quickview-btn btn btn-dark btn-sm btn-block"
                                                            data-product-id="{{ $product['id'] }}">
                                                            Quick View
                                                        </button>
                                                    </div>
                                                    <a href="{{ url('product/' . $product->id) }}">
                                                        <img src="{{ asset('front/images/product_images/small/' . ($product->product_image ?? 'no-image.png')) }}"
                                                            alt="{{ $product->product_name }}"
                                                            class="img-fluid h-100 w-100" style="object-fit: cover;">
                                                    </a>
                                                </div>
                                                <div class="card-body px-2">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="flex-grow-1">
                                                            <p class="mb-1 text-muted small">
                                                                {{ $product->brand->name ?? 'Brand' }}</p>
                                                            <h6 class="mb-0 font-weight-bold">{{ $product->product_name }}</h6>
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
                                                            <span class="text-secondary mr-2" style="text-decoration: line-through;">
                                                                ${{ $product->product_price }}
                                                            </span>
                                                            <span class="font-weight-bold text-success">
                                                                ${{ number_format($product->product_price - ($product->product_price * $product->product_discount) / 100, 2) }}
                                                            </span>
                                                        @else
                                                            <span class="font-weight-bold">${{ $product->product_price }}</span>
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
        
        {{-- Bootstrap 4.6 Modal --}}
        <div class="modal fade" id="QuickViewProduct" tabindex="-1" role="dialog" aria-labelledby="QuickViewProductLabel" aria-hidden="true" data-backdrop="true" data-keyboard="true">
            <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
                <div class="modal-content">
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
        <!--end shop area-->
    </div>
@endsection

@section('scripts')
    <script>
        // Bootstrap 4.6 Compatible QuickView AJAX
        $(document).ready(function() {

            // Bootstrap 4.6 modal trigger - Manual handling to avoid conflicts
            $(document).on('click', '.quickview-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var productId = $(this).data('product-id');
                var modal = $('#QuickViewProduct');

                if (!productId) {
                    alert('Product ID not found');
                    return;
                }

                // Reset and show loading state
                modal.find('.modal-body').html(`
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-3">Loading product details...</p>
                    </div>
                `);

                // Manually show modal to avoid Bootstrap conflicts
                modal.modal({
                    backdrop: true,
                    keyboard: true,
                    focus: true,
                    show: true
                });

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
                                    sizeOptions += `<option value="${attr.size}">${attr.size}</option>`;
                                });
                            } else {
                                sizeOptions = '<option>One Size</option>';
                            }

                            // Calculate discounted price
                            var originalPrice = parseFloat(product.product_price);
                            var discount = parseFloat(product.product_discount) || 0;
                            var finalPrice = discount > 0 ? originalPrice - (originalPrice * discount / 100) : originalPrice;

                            // Build price HTML
                            var priceHtml = '';
                            if (discount > 0) {
                                priceHtml = `
                                    <h5 class="mb-0 text-muted" style="text-decoration: line-through;">Rs. ${originalPrice}</h5>
                                    <h4 class="mb-0">Rs. ${finalPrice.toFixed(2)}</h4>
                                `;
                            } else {
                                priceHtml = `<h4 class="mb-0">Rs. ${originalPrice}</h4>`;
                            }

                            // Build complete modal HTML with Bootstrap 4.6 classes
                            var modalHtml = `
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute; right: 15px; top: 15px; z-index: 1050; background: none; border: none; font-size: 1.5rem; color: #000; opacity: 0.5;">
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
                                                <div class="rates cursor-pointer small">
                                                    <i class="bx bxs-star text-warning"></i>
                                                    <i class="bx bxs-star text-warning"></i>
                                                    <i class="bx bxs-star text-warning"></i>
                                                    <i class="bx bxs-star text-warning"></i>
                                                    <i class="bx bxs-star text-warning"></i>
                                                </div>
                                                <div class="ml-2">
                                                    <p class="mb-0">(${product.reviews_count || 0} Reviews)</p>
                                                </div>
                                            </div>
                                            <div class="d-flex align-items-center mt-3">
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
                                                    <label class="mb-1">Quantity</label>
                                                    <select class="form-control form-control-sm" id="quickview-quantity">
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                        <option value="5">5</option>
                                                    </select>
                                                </div>
                                                <div class="col-auto">
                                                    <label class="mb-1">Size</label>
                                                    <select class="form-control form-control-sm" id="quickview-size">
                                                        ${sizeOptions}
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-flex mt-4">
                                                <button class="btn btn-dark btn-ecomm add-to-cart-quickview mr-2" 
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
                                if ($('.product-gallery').length && !$('.product-gallery').hasClass('owl-loaded')) {
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
                    '<div class="spinner-border spinner-border-sm mr-2" role="status"></div> Adding...'
                );

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
                                .html('<i class="bx bx-check mr-2"></i> Added to Cart');

                            if (response.cart_count) {
                                $('.cart-count').text(response.cart_count);
                            }

                            showNotification('Product added to cart successfully!', 'success');

                            setTimeout(function() {
                                button.prop('disabled', false)
                                    .removeClass('btn-success').addClass('btn-dark')
                                    .html('<i class="bx bxs-cart-add mr-2"></i> Add to Cart');
                            }, 2000);

                        } else {
                            button.prop('disabled', false)
                                .html('<i class="bx bxs-cart-add mr-2"></i> Add to Cart');
                            showNotification(response.message || 'Failed to add product to cart', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Cart Error:', xhr.responseText);
                        button.prop('disabled', false)
                            .html('<i class="bx bxs-cart-add mr-2"></i> Add to Cart');
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
                    '<div class="spinner-border spinner-border-sm mr-2" role="status"></div> Adding...'
                );

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
                                .html('<i class="bx bxs-heart mr-2"></i> In Wishlist');
                            showNotification('Product added to wishlist!', 'success');
                        } else {
                            button.prop('disabled', false)
                                .html('<i class="bx bx-heart mr-2"></i> Add to Wishlist');
                            showNotification(response.message || 'Failed to add to wishlist', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Wishlist Error:', xhr.responseText);
                        button.prop('disabled', false)
                            .html('<i class="bx bx-heart mr-2"></i> Add to Wishlist');
                        showNotification('Error adding to wishlist', 'error');
                    }
                });
            });

            // Notification function for Bootstrap 4.6
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