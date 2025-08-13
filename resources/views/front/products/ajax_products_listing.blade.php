<div class="product-grid">
    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-3 row-cols-xl-3 row-cols-xxl-3 g-3 g-sm-4">
        @foreach ($categoryProducts as $product)
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="position-relative overflow-hidden" style="height: 250px;">
                        <div class="add-cart position-absolute top-0 end-0 mt-2 me-2 z-10">
                            <a href="javascript:;"><i class='bx bx-cart-add fs-4 text-dark'></i></a>
                        </div>
                        <div
                            class="quick-view position-absolute start-0 bottom-0 w-100 text-center bg-dark bg-opacity-50 py-2">
                            <a href="javascript:;" class="text-white text-decoration-none" data-bs-toggle="modal"
                                data-bs-target="#QuickViewProduct">Quick View</a>
                        </div>
                        <a href="{{ url('product/' . $product->id) }}">
                            @php
                                $productImage = null;
                                if ($product->images && $product->images->count() > 0) {
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
                                    ? asset('front/images/product_images/' . $productImage)
                                    : asset('assets/images/no-product-image.png');
                            @endphp

                            <img src="{{ $imageUrl }}" alt="{{ $product->product_name }}"
                                class="img-fluid h-100 w-100 object-fit-cover"
                                onerror="this.src='{{ asset('assets/images/no-product-image.png') }}';">
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
