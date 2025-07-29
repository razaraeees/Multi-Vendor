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
use App\Models\ProductsFilter;
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


    // public function shop(){

    //     $category = Category::where('status', 1)->first();
    //     // Base query - YE IMPORTANT CHANGE HAI
    //     $products = Product::with(['brand', 'ratings'])->where('status', 1);

    //     return view('front.products.shop', compact('category', 'products'));
    // }
    public function detail($id)
    {
        // 1. Get product details with eager loaded relationships
        $product = Product::with([
            'section',
            'category',
            'brand',
            'attributes' => fn($q) => $q->where('stock', '>', 0)->where('status', 1),
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

        // 10. Stock
        $totalStock = ProductsAttribute::where('product_id', $id)->sum('stock');

        // 11. Meta tags
        $meta_title = $product->meta_title;
        $meta_description = $product->meta_description;
        $meta_keywords = $product->meta_keywords;

        // 12. Return to view
        return view('front.products.detail', compact(
            'productDetails',
            'categoryDetails',
            'totalStock',
            'similarProducts',
            'recentlyViewedProducts',
            'groupProducts',
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


            Session::forget('couponAmount'); // Deleting Data: https://laravel.com/docs/9.x/session#deleting-data
            Session::forget('couponCode');   // Deleting Data: https://laravel.com/docs/9.x/session#deleting-data


            // Prevent the ability to add an item to the Cart with 0 zero quantity
            if ($data['quantity'] <= 0) { // if the ordered quantity is 0, convert it to at least 1
                $data['quantity'] = 1;
            }


            // Check if the selected product `product_id` with that selected `size` have available `stock` in `products_attributes` table
            $getProductStock = ProductsAttribute::getProductStock($data['product_id'], $data['size']);

            if ($getProductStock < $data['quantity']) { // if the `stock` available (in `products_attributes` table) is less than the ordered quantity by user (the quantity that the user desires)
                return redirect()->back()->with('error_message', 'Required Quantity is not available!');
            }

            $session_id = Session::get('session_id');
            if (empty($session_id)) {
                $session_id = Session::getId();
                Session::put('session_id', $session_id);
            }

            // Get $user_id and $countProducts in two cases. Check if the same product `product_id` with the same `size` already exists (was ordered by the same user depending on `user_id` or `session_id`) in Cart `carts` table in TWO cases: firstly, the user is authenticated/logged in, and secondly, the user is NOT logged in i.e. guest
            // To prevent repetition of the ordered Cart products `product_id` with the same sizes `size` for a certain user (`session_id` or `user_id` depending on whether the user is authenticated/logged in or not) in the `carts` table
            if (Auth::check()) { // Here we're using the default 'web' Authentication Guard    // if the user is authenticated/logged in (using the default Laravel Authentication Guard 'web' Guard (check config/auth.php file) whose 'Provider' is the User.php Model i.e. `users` table)    // Determining If The Current User Is Authenticated: https://laravel.com/docs/9.x/authentication#determining-if-the-current-user-is-authenticated
                $user_id = Auth::user()->id; // Retrieving The Authenticated User: https://laravel.com/docs/9.x/authentication#retrieving-the-authenticated-user

                // Check if that authenticated/logged in user has already THE SAME product `product_id` with THE SAME `size` (in `carts` table) in the Cart i.e. the `carts` table
                $countProducts = Cart::where([
                    'user_id'    => $user_id, // THAT EXACT authenticated/logged in user (using their `user_id` because they're authenticated/logged in)
                    'product_id' => $data['product_id'],
                    'size'       => $data['size']
                ])->count();
            } else { // if the user is NOT logged in (guest)
                // Check if that guest or NOT logged in user has already THE SAME products `product_id` with THE SAME `size` (in `carts` table) in the Cart i.e. the `carts` table    // When user logins, their `user_id` gets updated (check userLogin() method in UserController.php)
                $user_id = 0; // is the same as    $user_id = null;    // When user logins, their `user_id` gets updated (check userLogin() method in UserController.php)    // this is because that the use is NOT authenticated / NOT logged in i.e. guest 
                $countProducts = Cart::where([ // We get the count (number) of that specific product `product_id` with that specific `size` to prevent repetition in the `carts` table 
                    'session_id' => $session_id, // THAT EXACT NON-authenticated/NOT logged or Guest user (using their `session_id` because they're NOT authenticated/NOT logged in or Guest)
                    'product_id' => $data['product_id'],
                    'size'       => $data['size']
                ])->count();
            }



            // To prevent repetition of the ordered products `product_id` with the same sizes `size` for a certain user (`session_id` or `user_id` depending on whether the user is authenticated/logged in or not) in the `carts` table:
            if ($countProducts > 0) { // if that specific user (`session_id` or `user_id` i.e. depending on the user is authenticated/logged or not (guest)) ALREADY ordered that specific product `product_id` with that same exact `size`, we're going to just UPDATE the `quantity` in the `carts` table to prevent repetition of the ordered products inside the table (and won't create a new record)    // In other words, if the same product with the same size ALREADY EXISTS (ordered with the SAME user) in the `carts` table
                Cart::where([
                    'session_id' => $session_id, // THAT EXACT NON-authenticated/NOT logged or Guest user (using their `session_id` because they're NOT authenticated/NOT logged in or Guest)
                    'user_id'    => $user_id ?? 0, // if the user is authenticated/logged in, take its $user_id. If not, make it zero 0    // When user logins, their `user_id` gets updated (check userLogin() method in UserController.php)
                    'product_id' => $data['product_id'],
                    'size'       => $data['size']
                ])->increment('quantity', $data['quantity']); // Add the new added quantity (    $data['quantity']    ) to the already existing `quantity` in the `carts` table    // Update Statements: Increment & Decrement: https://laravel.com/docs/9.x/queries#increment-and-decrement
            } else { // if that `product_id` with that `size` was never ordered by that user `session_id` or `user_id` (i.e. that product with that size for that user doesn't exist in the `carts` table), INSERT it into the `carts` table for the first time
                // INSERT the ordered product `product_id`, the user's session ID `session_id`, `size` and `quantity` in the `carts` table
                $item = new Cart; // the `carts` table

                $item->session_id = $session_id; // $session_id will be stored whether the user is authenticated/logged in or NOT
                $item->user_id    = $user_id; // depending on the last if statement (whether user is authenticated/logged in or NOT (guest))    // $user_id will be always zero 0 if the user is NOT authenticated/logged in    // When user logins, their `user_id` gets updated (check userLogin() method in UserController.php)
                $item->product_id = $data['product_id'];
                $item->size       = $data['size'];
                $item->quantity   = $data['quantity'];

                $item->save();
            }


            return redirect()->back()->with('success_message', 'Product has been added in Cart! <a href="/cart" style="text-decoration: underline !important">View Cart</a>');
        }
    }

    // Render Cart page (front/products/cart.blade.php)    
    public function cart(Request $request)
    {
        // Cart items get karo
        $getCartItems = Cart::getCartItems();

        foreach ($getCartItems as &$item) {
            $priceData = \App\Models\Product::getDiscountAttributePrice($item['product_id'], $item['size']);
            $item['unit_price'] = $priceData['final_price'];
            $item['original_price'] = $priceData['product_price'];
            $item['discount'] = $priceData['discount'];
            $item['total_price'] = $priceData['final_price'] * $item['quantity'];
        }

        // Total calculation - sirf items ka total
        $total_price = collect($getCartItems)->sum('total_price');

        return view('front.products.cart', compact('getCartItems', 'total_price'));
    }

    public function cartUpdate(Request $request)
    {
        $data = $request->all();

        Session::forget('couponAmount');
        Session::forget('couponCode');

        foreach ($data['items'] as $item) {
            $cartDetails = Cart::find($item['id']);

            if (!$cartDetails) continue;

            // Stock check
            $availableStock = ProductsAttribute::select('stock')->where([
                'product_id' => $cartDetails['product_id'],
                'size'       => $cartDetails['size']
            ])->first();

            if ($availableStock && $item['quantity'] > $availableStock->stock) {
                return redirect()->back()->with('error', 'Product stock is not available for some items');
            }

            // Size availability check
            $availableSize = ProductsAttribute::where([
                'product_id' => $cartDetails['product_id'],
                'size'       => $cartDetails['size'],
                'status'     => 1
            ])->count();

            if ($availableSize == 0) {
                return redirect()->back()->with('error', 'Some product sizes are not available. Please update your cart.');
            }

            // ✅ Direct assign (no fillable issue)
            $cartDetails->quantity = $item['quantity'];
            $cartDetails->save();
        }

        return redirect()->back()->with('success', 'Cart updated successfully!');
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




    // Checkout page (using match() method for the 'GET' request for rendering the front/products/checkout.blade.php page or the 'POST' request for the HTML Form submission in the same page) (for submitting the user's Delivery Address and Payment Method))    
    public function checkout(Request $request)
    {
        try {
            // Fetch countries
            $countries = Country::where('status', 1)->get()->toArray();

            // Get Cart Items
            $getCartItems = Cart::getCartItems();

            if (count($getCartItems) == 0) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Shopping Cart is empty! Please add products to your Cart to checkout'
                ]);
            }

            // Calculate totals
            $total_price = 0;
            $total_weight = 0;

            foreach ($getCartItems as $item) {
                $attrPrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                $total_price += ($attrPrice['final_price'] * $item['quantity']);
                $product_weight = $item['product']['product_weight'] ?? 0;
                $total_weight += $product_weight;
            }

            $deliveryAddresses = DeliveryAddress::deliveryAddresses();

            foreach ($deliveryAddresses as $key => $value) {
                $shippingCharges = ShippingCharge::getShippingCharges($total_weight, $value['country']);
                $deliveryAddresses[$key]['shipping_charges'] = $shippingCharges;

                $deliveryAddresses[$key]['codpincodeCount'] = DB::table('cod_pincodes')
                    ->where('pincode', $value['pincode'])->count();

                $deliveryAddresses[$key]['prepaidpincodeCount'] = DB::table('prepaid_pincodes')
                    ->where('pincode', $value['pincode'])->count();
            }

            if ($request->isMethod('post')) {
                $data = $request->all();

                // Validation
                if (empty($data['address_id'])) {
                    return response()->json(['status' => 'error', 'message' => 'Please select Delivery Address!']);
                }

                if (empty($data['payment_gateway'])) {
                    return response()->json(['status' => 'error', 'message' => 'Please select Payment Method!']);
                }

                if (empty($data['accept'])) {
                    return response()->json(['status' => 'error', 'message' => 'Please agree to T&C!']);
                }

                // Get delivery address
                $deliveryAddress = DeliveryAddress::where('id', $data['address_id'])->first();
                if (!$deliveryAddress) {
                    return response()->json(['status' => 'error', 'message' => 'Selected delivery address not found!']);
                }
                $deliveryAddress = $deliveryAddress->toArray();

                // Payment + Order Status
                if ($data['payment_gateway'] == 'COD') {
                    $payment_method = 'COD';
                    $order_status = 'New';
                } else {
                    $payment_method = 'Prepaid';
                    $order_status = 'Pending';
                }

                DB::beginTransaction();

                try {
                    // Recalculate total
                    $total_price = 0;
                    foreach ($getCartItems as $item) {
                        $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                        $total_price += ($getDiscountAttributePrice['final_price'] * $item['quantity']);
                    }

                    // Shipping charges
                    $shipping_charges = ShippingCharge::getShippingCharges($total_weight, $deliveryAddress['country']);

                    // Grand total
                    $couponAmount = Session::get('couponAmount', 0);
                    $grand_total = $total_price + $shipping_charges - $couponAmount;

                    Session::put('grand_total', $grand_total);

                    // Save Order
                    $order = new Order;
                    $order->user_id = Auth::user()->id;
                    $order->name = $deliveryAddress['name'];
                    $order->address = $deliveryAddress['address'];
                    $order->city = $deliveryAddress['city'];
                    $order->state = $deliveryAddress['state'];
                    $order->country = $deliveryAddress['country'];
                    $order->pincode = $deliveryAddress['pincode'];
                    $order->mobile = $deliveryAddress['mobile'];
                    $order->email = Auth::user()->email;
                    $order->shipping_charges = $shipping_charges;
                    $order->coupon_code = Session::get('couponCode', '');
                    $order->coupon_amount = $couponAmount;
                    $order->order_status = $order_status;
                    $order->payment_method = $payment_method;
                    $order->payment_gateway = $data['payment_gateway'];
                    $order->grand_total = $grand_total;
                    $order->save();

                    $order_id = $order->id;

                    // Save Order Products
                    foreach ($getCartItems as $item) {
                        $getProductStock = ProductsAttribute::getProductStock($item['product_id'], $item['size']);
                        if ($item['quantity'] > $getProductStock) {
                            throw new \Exception($item['product']['product_name'] . ' (' . $item['size'] . ') stock not enough.');
                        }

                        $cartItem = new OrdersProduct;
                        $cartItem->order_id = $order_id;
                        $cartItem->user_id = Auth::user()->id;

                        $getProductDetails = Product::select('product_code', 'product_name', 'product_color', 'admin_id', 'vendor_id')
                            ->where('id', $item['product_id'])->first();

                        if (!$getProductDetails) {
                            throw new \Exception('Product not found.');
                        }

                        $getProductDetails = $getProductDetails->toArray();
                        $cartItem->admin_id = $getProductDetails['admin_id'];
                        $cartItem->vendor_id = $getProductDetails['vendor_id'];

                        if ($getProductDetails['vendor_id'] > 0) {
                            $vendorCommission = Vendor::getVendorCommission($getProductDetails['vendor_id']);
                            $cartItem->commission = $vendorCommission;
                        }

                        $cartItem->product_id = $item['product_id'];
                        $cartItem->product_code = $getProductDetails['product_code'];
                        $cartItem->product_name = $getProductDetails['product_name'];
                        $cartItem->product_color = $getProductDetails['product_color'];
                        $cartItem->product_size = $item['size'];

                        $getDiscountAttributePrice = Product::getDiscountAttributePrice($item['product_id'], $item['size']);
                        $cartItem->product_price = $getDiscountAttributePrice['final_price'];
                        $cartItem->product_qty = $item['quantity'];
                        $cartItem->save();

                        // Update stock
                        $newStock = $getProductStock - $item['quantity'];
                        ProductsAttribute::where([
                            'product_id' => $item['product_id'],
                            'size' => $item['size']
                        ])->update(['stock' => $newStock]);
                    }

                    Session::put('order_id', $order_id);

                    DB::commit();

                    // JSON response for AJAX
                    if ($data['payment_gateway'] == 'COD') {
                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => url('thanks')
                        ]);
                    } elseif ($data['payment_gateway'] == 'Paypal') {
                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => url('paypal')
                        ]);
                    } elseif ($data['payment_gateway'] == 'iyzipay') {
                        return response()->json([
                            'status' => 'success',
                            'redirect_url' => url('iyzipay')
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Payment method not supported yet'
                        ]);
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage()
                    ]);
                }
            }

            // Agar sirf checkout page kholna hai (GET request)
            return view('front.products.checkout')
                ->with(compact('deliveryAddresses', 'countries', 'getCartItems', 'total_price'));
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred during checkout. Please try again.'
            ]);
        }
    }

    // Rendering Thanks page (after placing an order)    
    public function thanks()
    {
        if (Session::has('order_id')) { // if there's an order has been placed, empty the Cart (remove the order (the cart items/products) from `carts`table)    // 'user_id' was stored in Session inside checkout() method in Front/ProductsController.php
            // We empty the Cart after placing the order
            Cart::where('user_id', Auth::user()->id)->delete(); // Retrieving The Authenticated User: https://laravel.com/docs/9.x/authentication#retrieving-the-authenticated-user


            return view('front.products.thanks');
        } else { // if there's no order has been placed
            return redirect('cart'); // redirect user to cart.blade.php page
        }
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
