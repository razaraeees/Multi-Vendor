<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;



use App\Models\Category;
use App\Models\DeliveryAddress;
use App\Models\Product;
use App\Models\ProductsAttribute;
use App\Models\Rating;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\ProductsImage;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Country;
use App\Models\ShippingCharge;
use App\Models\OrdersProduct;
use App\Models\RecentlyViewedProduct;


class ProductsController extends Controller
{
    public function listing(Request $request)
    {
        $url = $request->get('current_url') ?? request()->route()->uri();

        // Try to find category
        $category = Category::where('url', $url)->where('status', 1)->first();

        if ($category) {
            $categoryDetails = Category::categoryDetails($url);
            $catIds = $categoryDetails['catIds'];
            $meta = $categoryDetails['categoryDetails'];

            // Breadcrumbs ko array banate hain
            $breadcrumbs = [];
            $breadcrumbs[] = [
                'title' => $meta['category_name'],
                'url'   => url($url)
            ];
        } else {
            // 🛍️ Shop Page Logic (no category found)
            $catIds = Category::where('status', 1)->pluck('id')->toArray();
            $meta = [
                'category_name' => 'All Products',
                'description' => 'Browse all available products'
            ];

            $breadcrumbs = [
                ['title' => 'Shop', 'url' => url('/shop')]
            ];

            // Fake categoryDetails
            $categoryDetails = [
                'breadcrumbs' => $breadcrumbs,
                'categoryDetails' => $meta,
                'catIds' => $catIds
            ];
        }

        // Base query
        $products = Product::with(['brand', 'ratings'])->where('status', 1);

        // CATEGORY FILTER
        if (!empty($request->category)) {
            $products->whereIn('category_id', $request->category);
        } else {
            $products->whereIn('category_id', $catIds);
        }

        // FILTERS
        if (!empty($request->brand)) {
            $products->whereIn('brand_id', $request->brand);
        }

        if (!empty($request->min_price) || !empty($request->max_price)) {
            $minPrice = !empty($request->min_price) ? (float)$request->min_price : 0;
            $maxPrice = !empty($request->max_price) ? (float)$request->max_price : 999999;
            $products->whereBetween('product_price', [$minPrice, $maxPrice]);
        }

        if (!empty($request->color)) {
            $products->whereIn('product_color', $request->color);
        }

        if (!empty($request->discount)) {
            $products->where('product_discount', '>=', $request->discount);
        }

        // SORTING
        $sort = $request->get('sort');
        switch ($sort) {
            case 'product_latest':
                $products->orderBy('id', 'desc');
                break;
            case 'price_lowest':
                $products->orderBy('product_price', 'asc');
                break;
            case 'price_highest':
                $products->orderBy('product_price', 'desc');
                break;
            case 'name_a_z':
                $products->orderBy('product_name', 'asc');
                break;
            case 'name_z_a':
                $products->orderBy('product_name', 'desc');
                break;
            default:
                $products->orderBy('id', 'desc');
        }

        $categoryProducts = $products->paginate(30);

        // Filter Data for Sidebar
        $categories = Category::where('status', 1)->withCount('products')->get();
        $brands = Brand::where('status', 1)->withCount('products')->get();
        $colors = Product::where('status', 1)
            ->whereNotNull('product_color')
            ->where('product_color', '!=', '')
            ->distinct()
            ->pluck('product_color')
            ->filter();

        // AJAX Return
        if ($request->ajax()) {
            return view(
                'front.products.ajax_products_listing',
                compact('categoryDetails', 'categoryProducts', 'meta', 'categories', 'brands', 'colors', 'breadcrumbs')
            )->render();
        }

        return view('front.products.listing', compact(
            'categoryDetails',
            'categoryProducts',
            'url',
            'meta',
            'categories',
            'brands',
            'colors',
            'breadcrumbs'
        ));
    }

    public function quickView($id)
    {
        try {
            // 1. Product with relationships
            $product = Product::with([
                'category',
                'brand',
                'attributes',
                'images',
                'vendor'
            ])->find($id);

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found or unavailable'
                ], 404);
            }

            $productDetails = $product->toArray();

            // === Discount Calculation ===
            $originalPrice = $product->price;
            $discountedPrice = null;

            if (!empty($product->discount_percent) && $product->discount_percent > 0) {
                // Percentage based discount
                $discountedPrice = round($originalPrice - ($originalPrice * $product->discount_percent / 100), 2);
            } elseif (!empty($product->discount_amount) && $product->discount_amount > 0) {
                // Fixed amount discount
                $discountedPrice = max(round($originalPrice - $product->discount_amount, 2), 0);
            }

            $productDetails['original_price'] = $originalPrice;
            $productDetails['discounted_price'] = $discountedPrice;

            // 2. Category breadcrumbs
            $categoryDetails = Category::categoryDetails($product->category->url);

            // 3. Similar products
            $similarProducts = Product::with('brand')
                ->where('category_id', $product->category->id)
                ->where('id', '!=', $id)
                ->inRandomOrder()
                ->limit(4)
                ->get()
                ->toArray();

            // 4. Session ID
            $sessionId = Session::get('session_id') ?? md5(uniqid(rand(), true));
            Session::put('session_id', $sessionId);

            // 5. Recently viewed products
            DB::table('recently_viewed_products')->updateOrInsert([
                'product_id' => $id,
                'session_id' => $sessionId
            ]);

            $recentProductIds = DB::table('recently_viewed_products')
                ->where('session_id', $sessionId)
                ->where('product_id', '!=', $id)
                ->inRandomOrder()
                ->limit(4)
                ->pluck('product_id');

            $recentlyViewedProducts = Product::with('brand')
                ->whereIn('id', $recentProductIds)
                ->get()
                ->toArray();

            // 6. Group products
            $groupProducts = [];
            if (!empty($product->group_code)) {
                $groupProducts = Product::select('id', 'product_image')
                    ->where('id', '!=', $id)
                    ->where('group_code', $product->group_code)
                    ->where('status', 1)
                    ->get()
                    ->toArray();
            }

            // 7. Ratings & reviews
            $ratings = Rating::with('user')
                ->where('product_id', $id)
                ->where('status', 1)
                ->get()
                ->toArray();

            $ratingSum = Rating::where('product_id', $id)->where('status', 1)->sum('rating');
            $ratingCount = Rating::where('product_id', $id)->where('status', 1)->count();

            $avgRating = $ratingCount > 0 ? round($ratingSum / $ratingCount, 2) : 0;
            $avgStarRating = $ratingCount > 0 ? round($ratingSum / $ratingCount) : 0;

            $starCounts = collect(range(1, 5))->mapWithKeys(function ($star) use ($id) {
                return ["rating{$star}StarCount" => Rating::where([
                    'product_id' => $id,
                    'status' => 1,
                    'rating' => $star
                ])->count()];
            });

            // 8. Group attributes
            $productAttributes = ProductsAttribute::with(['attribute', 'attributeValue', 'product'])
                ->where('product_id', $id)
                ->get();

            $groupedAttributes = [];
            foreach ($productAttributes as $productAttr) {
                if ($productAttr->attribute && $productAttr->attributeValue) {
                    $attributeName = $productAttr->attribute->name;
                    $attributeValue = $productAttr->attributeValue->value;
                    $attributeType = $productAttr->attribute->attribute_type;

                    if (!isset($groupedAttributes[$attributeName])) {
                        $groupedAttributes[$attributeName] = [
                            'attribute_type' => $attributeType,
                            'attribute_id' => $productAttr->attribute->id,
                            'values' => []
                        ];
                    }

                    $groupedAttributes[$attributeName]['values'][] = [
                        'value' => $attributeValue,
                        'product_attr_id' => $productAttr->id,
                        'attribute_value_id' => $productAttr->attributeValue->id,
                        'price' => $productAttr->price ?? 0,
                        'stock' => $productAttr->stock ?? 0
                    ];
                }
            }

            // 9. Stock
            $totalStock = $product->stock ?? 0;
            if ($productAttributes->count() > 0) {
                $totalStock = $productAttributes->sum('stock') ?: $totalStock;
            }
            $stockStatus = $product->stock_status ?? 'out_of_stock';

            // 10. Meta tags
            $meta_title = $product->meta_title;
            $meta_description = $product->meta_description;
            $meta_keywords = $product->meta_keywords;

            return response()->json([
                'success' => true,
                'productDetails' => $productDetails,
                'categoryDetails' => $categoryDetails,
                'totalStock' => $totalStock,
                'stockStatus' => $stockStatus,
                'similarProducts' => $similarProducts,
                'recentlyViewedProducts' => $recentlyViewedProducts,
                'groupProducts' => $groupProducts,
                'groupedAttributes' => $groupedAttributes,
                'meta_title' => $meta_title,
                'meta_description' => $meta_description,
                'meta_keywords' => $meta_keywords,
                'ratings' => $ratings,
                'avgRating' => $avgRating,
                'avgStarRating' => $avgStarRating,
                'starCounts' => $starCounts->toArray()
            ]);
        } catch (\Exception $e) {
            Log::error('QuickView Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while loading product details'
            ], 500);
        }
    }

    public function detail($id)
    {
        $product = Product::with([
            'category',
            'brand',
            'attributes',
            'images',
            'vendor'
        ])->findOrFail($id);

        $productDetails = $product->toArray();

        // 2. Category breadcrumbs (for frontend nav)
        $categoryDetails = Category::categoryDetails($product->category->url);

        // 3. Similar products (same category, not this product)
        $similarProducts = Product::with('brand')
            ->where('category_id', $product->category->id)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->get()
            ->toArray();

        // 4. Session ID handling
        $sessionId = Session::get('session_id') ?? md5(uniqid(rand(), true));
        Session::put('session_id', $sessionId);

        // 5. Recently viewed products — insert only once
        DB::table('recently_viewed_products')->updateOrInsert([
            'product_id' => $id,
            'session_id' => $sessionId
        ]);

        // 6. Get recently viewed product IDs (excluding current product)
        $recentProductIds = DB::table('recently_viewed_products')
            ->where('session_id', $sessionId)
            ->where('product_id', '!=', $id)
            ->inRandomOrder()
            ->limit(4)
            ->pluck('product_id');

        $recentlyViewedProducts = Product::with('brand')
            ->whereIn('id', $recentProductIds)
            ->get()
            ->toArray();

        // 7. Group code products (for color variants, etc.)
        $groupProducts = [];

        if (!empty($product->group_code)) {
            $groupProducts = Product::select('id', 'product_image')
                ->where('id', '!=', $id)
                ->where('group_code', $product->group_code)
                ->where('status', 1)
                ->get()
                ->toArray();
        }

        // 8. Ratings & reviews
        $ratings = Rating::with('user')
            ->where('product_id', $id)
            ->where('status', 1)
            ->get()
            ->toArray();

        $ratingSum = Rating::where('product_id', $id)->where('status', 1)->sum('rating');
        $ratingCount = Rating::where('product_id', $id)->where('status', 1)->count();

        $avgRating = $ratingCount > 0 ? round($ratingSum / $ratingCount, 2) : 0;
        $avgStarRating = $ratingCount > 0 ? round($ratingSum / $ratingCount) : 0;

        // 9. Count per star
        $starCounts = collect(range(1, 5))->mapWithKeys(function ($star) use ($id) {
            return ["rating{$star}StarCount" => Rating::where([
                'product_id' => $id,
                'status' => 1,
                'rating' => $star
            ])->count()];
        });

        $productAttributes = ProductsAttribute::with(['attribute', 'attributeValue', 'product'])
            ->where('product_id', $id)
            ->get();
        // dd($productAttributes->toArray());
        // Group attributes by attribute name for easier display
        $groupedAttributes = [];
        foreach ($productAttributes as $productAttr) {
            if ($productAttr->attribute && $productAttr->attributeValue) {
                $attributeName = $productAttr->attribute->name; // DB column name
                $attributeValue = $productAttr->attributeValue->value; // DB column name
                $attributeType = $productAttr->attribute->attribute_type; // DB column name

                if (!isset($groupedAttributes[$attributeName])) {
                    $groupedAttributes[$attributeName] = [
                        'attribute_type' => $attributeType,
                        'attribute_id' => $productAttr->attribute->id,
                        'values' => []
                    ];
                }

                $groupedAttributes[$attributeName]['values'][] = [
                    'value' => $attributeValue,
                    'product_attr_id' => $productAttr->id,
                    'attribute_value_id' => $productAttr->attributeValue->id,
                    'price' => $productAttr->price ?? 0,
                    'stock' => $productAttr->stock ?? 0
                ];
            }
        }


        // 11. Stock - Calculate total stock
        $totalStock = $product->stock ?? 0;

        // If stock is managed through attributes, calculate from there
        if ($productAttributes->count() > 0) {
            $totalStock = $productAttributes->sum('stock') ?: $totalStock;
        }

        // Get stock status from product table
        $stockStatus = $product->stock_status ?? 'out_of_stock';

        // 12. Meta tags
        $meta_title = $product->meta_title;
        $meta_description = $product->meta_description;
        $meta_keywords = $product->meta_keywords;

        // 13. Return to view
        return view('front.products.detail', compact(
            'productDetails',
            'categoryDetails',
            'totalStock',
            'stockStatus',
            'similarProducts',
            'recentlyViewedProducts',
            'groupProducts',
            'groupedAttributes', // Make sure this is passed
            'meta_title',
            'meta_description',
            'meta_keywords',
            'ratings',
            'avgRating',
            'avgStarRating'
        ))->with($starCounts->toArray());
    }



    // The AJAX call from front/js/custom.js file, to show the the correct related `price` and `stock` depending on the selected `size` (from the `products_attributes` table)) by clicking the size <select> box in front/products/detail.blade.php    
    public function getProductPrice(Request $request)
    {
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)

            $getDiscountAttributePrice = Product::getDiscountAttributePrice($data['product_id'], $data['size']); // $data['product_id'] and $data['size'] come from the 'data' object inside the $.ajax() method in front/js/custom.js file


            return $getDiscountAttributePrice;
        }
    }



    // Show all Vendor products in front/products/vendor_listing.blade.php    // This route is accessed from the <a> HTML element in front/products/vendor_listing.blade.php    
    public function vendorListing($vendorid)
    { // Required Parameters: https://laravel.com/docs/9.x/routing#required-parameters
        // Get vendor shop name
        $getVendorShop = Vendor::getVendorShop($vendorid);

        // Get all vendor products
        $vendorProducts = Product::with('brand')->where('vendor_id', $vendorid)->where('status', 1); // Eager Loading (using with() method): https://laravel.com/docs/9.x/eloquent-relationships#eager-loading    // 'brand' is the relationship method name in Product.php model that is being Eager Loaded

        // $vendorProducts Pagination
        $vendorProducts = $vendorProducts->paginate(30); // Paginating Eloquent Results: https://laravel.com/docs/9.x/pagination#paginating-eloquent-results


        return view('front.products.vendor_listing')->with(compact('getVendorShop', 'vendorProducts'));
    }



    // Add to Cart <form> submission in front/products/detail.blade.php    
    public function cartAdd(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();

            Session::forget('couponAmount');
            Session::forget('couponCode');

            if ($data['quantity'] <= 0) {
                $data['quantity'] = 1;
            }

            // Make sure attributes are passed from form
            $selectedAttributes = [];
            if (!empty($data['attributes'])) {
                foreach ($data['attributes'] as $attribute_id => $value_id) {
                    $selectedAttributes[] = [
                        'attribute_id' => $attribute_id,
                        'attribute_value_id' => $value_id
                    ];
                }
            }

            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id', $session_id);
            }

            if (Auth::check()) {
                $user_id = Auth::user()->id;
                $countProducts = Cart::where([
                    'user_id' => $user_id,
                    'product_id' => $data['product_id'],
                    'selected_attributes' => json_encode($selectedAttributes)
                ])->count();
            } else {
                $user_id = 0;
                $countProducts = Cart::where([
                    'session_id' => $session_id,
                    'product_id' => $data['product_id'],
                    'selected_attributes' => json_encode($selectedAttributes)
                ])->count();
            }

            if ($countProducts > 0) {
                Cart::where([
                    'session_id' => $session_id,
                    'user_id' => $user_id ?? 0,
                    'product_id' => $data['product_id'],
                    'selected_attributes' => json_encode($selectedAttributes)
                ])->increment('quantity', $data['quantity']);
            } else {
                $item = new Cart;
                $item->session_id = $session_id;
                $item->user_id = $user_id;
                $item->product_id = $data['product_id'];
                $item->selected_attributes = json_encode($selectedAttributes);
                $item->quantity = $data['quantity'];
                $item->save();
            }

            return redirect()->back()->with('success_message', 'Product has been added in Cart! <a href="/cart" style="text-decoration: underline !important">View Cart</a>');
        }
    }

    public function cart(Request $request)
    {
        $getCartItems = Cart::getCartItems();

        foreach ($getCartItems as &$item) {
            if (!$item->product) {
                continue;
            }

            // 🔥 Fix: Handle selected_attributes properly
            $selectedAttrs = $item->selected_attributes;

            // Handle both string and array formats
            if (is_string($selectedAttrs)) {
                $selectedAttrs = json_decode($selectedAttrs, true) ?? [];
            } elseif (!is_array($selectedAttrs)) {
                $selectedAttrs = [];
            }

            // Get product details
            $product = \App\Models\Product::select(
                'product_price',
                'product_discount',
                'category_id'
            )->find($item->product_id);

            if (!$product) {
                $item['unit_price']     = 0;
                $item['original_price'] = 0;
                $item['discount']       = 0;
                $item['total_price']    = 0;
                $item['attributes_list'] = $selectedAttrs;
                continue;
            }

            $price = (float) $product->product_price;

            // Category discount
            $categoryDiscount = 0;
            if (!empty($product->category_id)) {
                $cat = \App\Models\Category::select('category_discount')->find($product->category_id);
                $categoryDiscount = $cat->category_discount ?? 0;
            }

            // Calculate final price & discount
            if ($product->product_discount > 0) {
                $final_price = $price - ($price * $product->product_discount / 100);
                $discount = $price - $final_price;
            } elseif ($categoryDiscount > 0) {
                $final_price = $price - ($price * $categoryDiscount / 100);
                $discount = $price - $final_price;
            } else {
                $final_price = $price;
                $discount = 0;
            }

            // Assign values
            $item['unit_price']     = round($final_price, 2);
            $item['original_price'] = round($price, 2);
            $item['discount']       = round($discount, 2);
            $item['total_price']    = round($final_price * $item->quantity, 2);
            $item['attributes_list'] = $selectedAttrs;
        }

        $total_price = collect($getCartItems)->sum('total_price');

        return view('front.products.cart', compact('getCartItems', 'total_price'));
    }

    public function cartUpdate(Request $request)
    {
        $data = $request->all();

        // Debug - yeh line add karo temporary
        Log::info('Cart Update Data:', $data);

        // ✅ Coupon reset
        Session::forget('couponAmount');
        Session::forget('couponCode');

        if (empty($data['items'])) {
            return redirect()->back()->with('error', 'No items to update');
        }

        foreach ($data['items'] as $item) {
            if (empty($item['id']) || empty($item['quantity'])) {
                continue;
            }

            $cartDetails = Cart::find($item['id']);
            if (!$cartDetails) continue;

            // ✅ Quantity validation
            $quantity = (int) $item['quantity'];
            if ($quantity < 1) $quantity = 1;
            if ($quantity > 10) $quantity = 10;

            // ✅ Stock check
            $productStock = Product::where('id', $cartDetails['product_id'])->value('stock');
            if ($productStock && $quantity > $productStock) {
                return redirect()->back()->with('error', 'Product stock is not available for some items');
            }

            // ✅ Attribute availability check
            $attributes = $cartDetails['selected_attributes'];

            // Handle both string (JSON) and array formats
            if (is_string($attributes)) {
                $attributes = json_decode($attributes, true) ?? [];
            } elseif (!is_array($attributes)) {
                $attributes = [];
            }

            if (!empty($attributes)) {
                $queryStatus = ProductsAttribute::where('product_id', $cartDetails['product_id']);

                foreach ($attributes as $attr) {
                    if (isset($attr['attribute_id']) && isset($attr['attribute_value_id'])) {
                        $queryStatus->where('attribute_id', $attr['attribute_id'])
                            ->where('attribute_value_id', $attr['attribute_value_id'])
                            ->where('status', 1);
                    }
                }

                if ($queryStatus->count() == 0) {
                    return redirect()->back()->with('error', 'Some product attributes are not available. Please update your cart.');
                }
            }

            // ✅ Quantity update
            $cartDetails->quantity = $quantity;
            $cartDetails->save();
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
    }

    // Add this new method for AJAX quantity updates
    public function updateQuantity(Request $request)
    {
        if ($request->ajax()) {
            $cartid = $request->cartid;
            $quantity = (int) $request->quantity;

            if ($quantity < 1) $quantity = 1;
            if ($quantity > 10) $quantity = 10;

            $cartItem = Cart::find($cartid);
            if (!$cartItem) {
                return response()->json(['status' => false, 'message' => 'Cart item not found']);
            }

            // Stock check
            $product = Product::find($cartItem->product_id);
            if ($product && $product->stock && $quantity > $product->stock) {
                return response()->json(['status' => false, 'message' => 'Insufficient stock']);
            }

            // Update quantity
            $cartItem->quantity = $quantity;
            $cartItem->save();

            // Calculate new totals
            $getCartItems = Cart::getCartItems();
            $totalCartItems = count($getCartItems);

            // Recalculate prices (same logic as your cart method)
            foreach ($getCartItems as &$item) {
                if (!$item->product) continue;

                $product = Product::select('product_price', 'product_discount', 'category_id')
                    ->find($item->product_id);

                if (!$product) continue;

                $price = (float) $product->product_price;

                // Category discount
                $categoryDiscount = 0;
                if (!empty($product->category_id)) {
                    $cat = Category::select('category_discount')->find($product->category_id);
                    $categoryDiscount = $cat->category_discount ?? 0;
                }

                // Calculate final price
                if ($product->product_discount > 0) {
                    $final_price = $price - ($price * $product->product_discount / 100);
                } elseif ($categoryDiscount > 0) {
                    $final_price = $price - ($price * $categoryDiscount / 100);
                } else {
                    $final_price = $price;
                }

                $item['unit_price'] = round($final_price, 2);
                $item['total_price'] = round($final_price * $item->quantity, 2);
            }

            $cartTotal = collect($getCartItems)->sum('total_price');

            // Get updated item total
            $updatedItem = $getCartItems->where('id', $cartid)->first();
            $itemTotal = $updatedItem ? $updatedItem['total_price'] : 0;

            return response()->json([
                'status' => true,
                'message' => 'Quantity updated',
                'cartTotal' => $cartTotal,
                'totalCartItems' => $totalCartItems,
                'itemTotal' => $itemTotal
            ]);
        }
    }


    // Delete a Cart Item AJAX call in front/products/cart_items.blade.php. Check front/js/custom.js    
    public function cartDelete(Request $request)
    {
        if ($request->ajax()) {
            try {
                // Clear coupon sessions
                Session::forget('couponAmount');
                Session::forget('couponCode');

                $data = $request->all();

                // Validation
                if (empty($data['cartid'])) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Cart ID is required'
                    ], 400);
                }

                // Check if cart item exists
                $cartItem = Cart::find($data['cartid']);
                if (!$cartItem) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Cart item not found'
                    ], 404);
                }

                // Delete the cart item
                $deleted = Cart::where('id', $data['cartid'])->delete();

                if (!$deleted) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to delete cart item'
                    ], 500);
                }

                // Get updated cart items
                $getCartItems = Cart::getCartItems();
                $totalCartItems = totalCartItems();

                // Generate views
                $cartView = '';
                $headerView = '';

                try {
                    $cartView = (string) \Illuminate\Support\Facades\View::make('front.products.cart_items')
                        ->with(compact('getCartItems'));

                    $headerView = (string) \Illuminate\Support\Facades\View::make('front.layout.header_cart_items')
                        ->with(compact('getCartItems'));
                } catch (\Exception $e) {
                    Log::error('View rendering error: ' . $e->getMessage());

                    // Fallback HTML
                    if (empty($getCartItems)) {
                        $cartView = '<div class="empty-cart text-center py-5">
                        <i class="bx bx-cart bx-lg mb-3"></i>
                        <p class="h5">Your cart is empty</p>
                        <a href="' . url('/') . '" class="btn btn-primary">Continue Shopping</a>
                    </div>';
                    }
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Item removed successfully',
                    'totalCartItems' => $totalCartItems,
                    'view' => $cartView,
                    'headerview' => $headerView,
                    'cartCount' => count($getCartItems)
                ]);
            } catch (\Exception $e) {
                // Log::error('Cart delete error: ' . $e->getMessage());

                return response()->json([
                    'status' => false,
                    'message' => 'An error occurred while deleting the item'
                ], 500);
            }
        }

        return response()->json([
            'status' => false,
            'message' => 'Invalid request'
        ], 400);
    }

    public function applyCoupon(Request $request)
    {
        $data = $request->all();

        Session::forget('couponAmount');
        Session::forget('couponCode');

        $getCartItems = Cart::getCartItems();

        $coupon = Coupon::where('coupon_code', $data['coupon_code'])->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'The coupon is invalid!');
        }

        if ($coupon->status == 0) {
            return redirect()->back()->with('error', 'The coupon is inactive!');
        }

        if ($coupon->expiry_date < date('Y-m-d')) {
            return redirect()->back()->with('error', 'The coupon is expired!');
        }

        // Calculate cart total
        $total_amount = 0;
        foreach ($getCartItems as $item) {
            $attrPrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
            $total_amount += $attrPrice['final_price'] * $item['quantity'];
        }

        // Coupon discount
        $couponAmount = ($coupon->amount_type == 'Fixed')
            ? $coupon->amount
            : ($total_amount * $coupon->amount / 100);

        $grand_total = $total_amount - $couponAmount;

        Session::put('couponAmount', $couponAmount);
        Session::put('couponCode', $coupon->coupon_code);

        return redirect()->back()->with('success', "Coupon applied successfully! Discount: EGP{$couponAmount}");
    }




    public function checkout(Request $request)
    {
        try {
            $getCartItems = Cart::getCartItems();

            if ($getCartItems->isEmpty()) {
                return redirect('cart')->with('error_message', 'Shopping Cart is empty! Please add products to your Cart to checkout');
            }

            // 🔥 FIXED: Proper session handling for both logged in and guest users
            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id', $session_id);
            }

            // Calculate total price with proper cart logic
            $total_price = 0;
            foreach ($getCartItems as &$item) {
                if (!$item->product) {
                    continue;
                }

                // Get product details
                $product = Product::select('product_price', 'product_discount', 'category_id')
                    ->find($item->product_id);

                if (!$product) {
                    continue;
                }

                $price = (float) $product->product_price;

                // Category discount
                $categoryDiscount = 0;
                if (!empty($product->category_id)) {
                    $cat = Category::select('category_discount')->find($product->category_id);
                    $categoryDiscount = $cat->category_discount ?? 0;
                }

                // Calculate final price & discount
                if ($product->product_discount > 0) {
                    $final_price = $price - ($price * $product->product_discount / 100);
                } elseif ($categoryDiscount > 0) {
                    $final_price = $price - ($price * $categoryDiscount / 100);
                } else {
                    $final_price = $price;
                }

                $item_total = round($final_price * $item->quantity, 2);
                $total_price += $item_total;

                // Add calculated values to item
                $item['unit_price'] = round($final_price, 2);
                $item['total_price'] = $item_total;
            }

            //  FIXED: Get delivery addresses for both logged in and guest users with proper session
            if (Auth::check()) {
                $deliveryAddresses = DeliveryAddress::where('user_id', Auth::user()->id)->get();
            } else {
                // For guest users, use session_id
                $deliveryAddresses = DeliveryAddress::where('session_id', $session_id)->get();
            }

            // Get dynamic shipping configuration
            $shippingConfig = ShippingCharge::first();
            $freeShippingThreshold = $shippingConfig ? $shippingConfig->free_shipping_min_amount : 500;
            $standardShippingCharges = $shippingConfig ? $shippingConfig->shipping_charge : 50;

            foreach ($deliveryAddresses as $address) {
                $address->shipping_charges = $total_price >= $freeShippingThreshold ? 0 : $standardShippingCharges;
                $address->is_free_shipping = $total_price >= $freeShippingThreshold;
                $address->cart_total = $total_price;
                $address->free_shipping_threshold = $freeShippingThreshold;
                $address->codpincodeCount = 1;
                $address->prepaidpincodeCount = 1;
            }

            // Log for debugging
            Log::info('Checkout Data:', [
                'user_logged_in' => Auth::check(),
                'session_id' => $session_id,
                'addresses_count' => $deliveryAddresses->count(),
                'total_price' => $total_price,
                'free_shipping_threshold' => $freeShippingThreshold
            ]);

            if ($request->isMethod('post')) {
                $data = $request->all();

                // Check if address is selected or provided
                if (empty($data['address_id']) && empty($data['name'])) {
                    return response()->json(['status' => 'error', 'message' => 'Please select or enter Delivery Address!']);
                }

                if (empty($data['payment_gateway'])) {
                    return response()->json(['status' => 'error', 'message' => 'Please select Payment Method!']);
                }

                if (empty($data['accept'])) {
                    return response()->json(['status' => 'error', 'message' => 'Please agree to T&C!']);
                }

                // Save / get delivery address
                if (!empty($data['address_id'])) {
                    // Using existing address
                    $deliveryAddress = DeliveryAddress::find($data['address_id']);
                    if (!$deliveryAddress) {
                        return response()->json(['status' => 'error', 'message' => 'Selected delivery address not found!']);
                    }
                } else {
                    // Creating new address inline
                    $addressData = [
                        'name' => $data['name'],
                        'address' => $data['address'],
                        'city' => $data['city'],
                        'state' => $data['state'],
                        'country' => 'Pakistan',
                        'pincode' => $data['pincode'],
                        'mobile' => $data['mobile']
                    ];

                    if (Auth::check()) {
                        $addressData['user_id'] = Auth::id();
                        $addressData['session_id'] = null;
                    } else {
                        $addressData['user_id'] = null;
                        $addressData['session_id'] = $session_id;
                    }

                    $deliveryAddress = DeliveryAddress::create($addressData);
                }

                $payment_method = $data['payment_gateway'] == 'COD' ? 'COD' : 'Prepaid';
                $order_status = $data['payment_gateway'] == 'COD' ? 'New' : 'Pending';

                DB::beginTransaction();

                try {
                    // Calculate dynamic shipping charges
                    $shipping_charges = $total_price >= $freeShippingThreshold ? 0 : $standardShippingCharges;
                    $couponAmount = Session::get('couponAmount', 0);
                    $grand_total = $total_price + $shipping_charges - $couponAmount;

                    // Create Order
                    $order = new Order;
                    $order->user_id = Auth::id(); // Will be null for guest users
                    $order->session_id = !Auth::check() ? $session_id : null; // 🔥 FIXED: Use proper session_id
                    $order->name = $deliveryAddress->name;
                    $order->address = $deliveryAddress->address;
                    $order->city = $deliveryAddress->city;
                    $order->state = $deliveryAddress->state;
                    $order->country = $deliveryAddress->country;
                    $order->pincode = $deliveryAddress->pincode;
                    $order->mobile = $deliveryAddress->mobile;
                    $order->email = Auth::check() ? Auth::user()->email : ($data['email'] ?? 'guest@example.com');
                    $order->shipping_charges = $shipping_charges;
                    $order->coupon_code = Session::get('couponCode', '');
                    $order->coupon_amount = $couponAmount;
                    $order->order_status = $order_status;
                    $order->payment_method = $payment_method;
                    $order->payment_gateway = $data['payment_gateway'];
                    $order->grand_total = $grand_total;
                    $order->save();

                    Session::put('order_id', $order->id);
                    // Save Order Products
                    foreach ($getCartItems as $item) {
                        $attributes = $item->selected_attributes;

                        $cartItem = new OrdersProduct;
                        $cartItem->order_id = $order->id;
                        $cartItem->user_id = Auth::id();
                        $cartItem->session_id = !Auth::check() ? $session_id : null;

                        $productDetails = Product::select('product_code', 'product_name', 'admin_id', 'vendor_id')
                            ->find($item->product_id);

                        $cartItem->product_code = $productDetails->product_code;
                        $cartItem->product_name = $productDetails->product_name;
                        $cartItem->admin_id = $productDetails->admin_id;
                        $cartItem->vendor_id = $productDetails->vendor_id;

                        // if ($productDetails->vendor_id > 0) {
                        //     $cartItem->commission = Vendor::getVendorCommission($productDetails->vendor_id);
                        // }

                        $cartItem->product_id = $item->product_id;
                        $cartItem->attributes = json_encode($attributes);
                        $cartItem->product_price = $item['unit_price'];
                        $cartItem->product_qty = $item->quantity;

                        // 🔹 Add default for item_status
                        $cartItem->item_status = 'Pending'; // ya jo default chahiye

                        $cartItem->save();
                    }





                    if (Auth::check()) {
                        Cart::where('user_id', Auth::id())->delete();
                    } else {
                        Cart::where('session_id', $session_id)->delete();
                    }

                    Session::forget(['couponCode', 'couponAmount']);

                    DB::commit();

                    return response()->json([
                        'status' => 'success',
                        'order_id' => $order->id,
                        'redirect_url' => $data['payment_gateway'] == 'COD' ? url('thanks') : url(strtolower($data['payment_gateway']))
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            }

            return view('front.products.checkout', compact(
                'deliveryAddresses',
                'getCartItems',
                'total_price'
            ))->with([
                'session_id' => $session_id,
                'freeShippingThreshold' => $freeShippingThreshold,
                'standardShippingCharges' => $standardShippingCharges
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
    public function thanks()
    {
        if (Session::has('order_id')) {
            if (Auth::check()) {
                Cart::where('user_id', Auth::id())->delete();
            } else {
                $session_id = Session::get('session_id');
                Cart::where('session_id', $session_id)->delete();
            }

            return view('front.products.thanks');
        }

        return redirect('cart');
    }




    // PIN code Availability Check: check if the PIN code of the user's Delivery Address exists in our database (in both `cod_pincodes` and `prepaid_pincodes`) or not in front/products/detail.blade.php via AJAX. Check front/js/custom.js    
    public function checkPincode(Request $request)
    {
        if ($request->ajax()) { // if the request is coming via an AJAX call
            $data = $request->all(); // Getting the name/value pairs array that are sent from the AJAX request (AJAX call)


            // Checking PIN code availability of BOTH COD and Prepaid PIN codes in BOTH `cod_pincodes` and `prepaid_pincodes` tables    
            // Check if the COD PIN code of that Delivery Address of the user exists in `cod_pincodes` table    
            $codPincodeCount = DB::table('cod_pincodes')->where('pincode', $data['pincode'])->count(); // $data['pincode'] comes from the 'data' object sent from inside the $.ajax() method in front/js/custom.js file

            // Check if the Prepaid PIN code of that Delivery Address of the user exists in `prepaid_pincodes` table    
            $prepaidPincodeCount = DB::table('prepaid_pincodes')->where('pincode', $data['pincode'])->count(); // $data['pincode'] comes from the 'data' object sent from inside the $.ajax() method in front/js/custom.js file

            // Check if the entered PIN code exists in BOTH `cod_pincodes` and `prepaid_pincodes` tables
            if ($codPincodeCount == 0 && $prepaidPincodeCount == 0) {
                echo 'This pincode is not available for delivery';
            } else {
                echo 'This pincode is available for delivery';
            }
        }
    }
}
