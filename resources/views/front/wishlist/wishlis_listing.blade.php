@extends('front.layout.layout')

@section('content')
<div class="page-content">
    <!-- breadcrumb etc... (aapka purana code yahi rahega) -->

    <section class="py-4">
        <div class="container">
            <div class="product-grid">
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">

                    @forelse ($wishlistItems as $item)
                        @php
                            $product = $item->product;
                            $productImage = null;
                            if ($product->images && $product->images->isNotEmpty()) {
                                foreach ($product->images as $img) {
                                    if (!empty($img->image)) {
                                        $productImage = $img->image;
                                        break;
                                    }
                                }
                            }
                            if (!$productImage && !empty($product->product_image)) {
                                $productImage = $product->product_image;
                            }

                            $imageUrl = $productImage
                                ? asset('front/images/product_images/small/' . $productImage)
                                : asset('assets/images/no-product-image.png');
                        @endphp

                        <div class="col wishlist-item wishlist-item-{{ $product->id }}">
                            <div class="card rounded-0 border">
                                <a href="{{ url('product/' . $product->id) }}">
                                    <img src="{{ $imageUrl }}" alt="{{ $product->product_name }}" class="card-img-top">
                                </a>
                                <div class="card-body">
                                    <div class="product-info">
                                        <p class="product-category font-13 mb-1">{{ $product->category->category_name ?? 'No Category' }}</p>
                                        <a href="{{ url('product/' . $product->id) }}">
                                            <h6 class="product-name mb-2">{{ $product->product_name }}</h6>
                                        </a>
                                        <div class="d-flex align-items-center">
                                            @if ($product->product_price && $product->product_price > $product->product_discount)
                                                <span class="me-1 text-decoration-line-through">${{ $product->product_price }}</span>
                                                <span class="fs-5">PKR {{ $product->product_discount }}</span>
                                            @else
                                                <span class="fs-5">PKR {{ $product->product_discount }}</span>
                                            @endif

                                            <div class="cursor-pointer ms-auto">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="bx bxs-star text-warning"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        <div class="product-action mt-2">
                                            <div class="d-grid gap-2">
                                                <a href="{{ url('product/' . $product->id) }}" class="btn btn-dark btn-ecomm">
                                                    <i class='bx bxs-cart-add'></i> Add to Cart
                                                </a>

                                                <button data-id="{{ $product->id }}" class="btn btn-light btn-ecomm wishlist-toggle in-wishlist">
                                                    <i class='bx bxs-heart'></i> Remove From List
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="col-12">
                            <p class="text-center">Your wishlist is empty.</p>
                        </div>
                    @endforelse

                </div>
            </div>
        </div>
    </section>
</div>

<div id="wishlistToast" style="position: fixed; top: 1rem; right: 1rem; z-index: 1055; background: #fff8d5; border-left: 5px solid #ffcc00; padding: 10px 15px; border-radius: 5px; display: none;">
    <span id="wishlistToastText">Message here...</span>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $(document).on('click', '.btn-ecomm.wishlist-toggle', function(e) {
        e.preventDefault();

        let button = $(this);
        let productId = button.data('id');
        if (!productId) return;

        let isRemoving = button.hasClass('in-wishlist');

        $.ajax({
            url: isRemoving ? '/wishlist/remove' : '/wishlist/add',
            type: 'POST',
            data: {
                product_id: productId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    if (isRemoving) {
                        $('.wishlist-item-' + productId).fadeOut(300, function() {
                            $(this).remove();

                            if ($('.wishlist-item').length === 0) {
                                $('.product-grid .row').html(
                                    '<div class="col-12 text-center">Your wishlist is empty.</div>'
                                );
                            }
                        });
                    } else {
                        // You can optionally update UI here if needed for add
                    }
                    showToast(response.message);
                } else {
                    showToast(response.message || 'Something went wrong.');
                }
            },
            error: function(xhr) {
                if (xhr.status === 401) {
                    showToast('Please login to manage your wishlist.');
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 1500);
                } else {
                    showToast('Unable to update wishlist. Please try again.');
                }
            }
        });
    });

    function showToast(message) {
        $('#wishlistToastText').text(message);
        $('#wishlistToast').fadeIn(300);
        setTimeout(function() {
            $('#wishlistToast').fadeOut(500);
        }, 3000);
    }
});
</script>
@endsection
