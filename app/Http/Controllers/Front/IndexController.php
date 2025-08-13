<?php

/**
 * FIXED IndexController - Updated to match detail page logic
 */

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;


use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Banner;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    public function index()
    {
        try {
            $sliderBanners = Banner::where('type', 'Slider')
                ->where('status', 1)
                ->get()
                ->toArray();

            $fixBanners = Banner::where('type', 'Fix')
                ->where('status', 1)
                ->get()
                ->toArray();

            // FIXED: New Products - Remove limit(1) from images, let all images load
            $newProducts = Product::with([
                'images' => function ($query) {
                    $query->where('status', 1)->orderBy('id', 'asc'); // Removed limit(1)
                },
                'category' // Added category for consistency
            ])
                ->where('status', 1)
                ->orderBy('id', 'desc')
                ->limit(8)
                ->get()
                ->toArray();

            // FIXED: Best Sellers - Remove limit(1) from images
            $bestSellers = Product::with([
                'images' => function ($query) {
                    $query->where('status', 1)->orderBy('id', 'asc'); // Removed limit(1)
                },
                'category'
            ])
                ->where([
                    'is_bestseller' => 'Yes',
                    'status' => 1
                ])
                ->inRandomOrder()
                ->limit(8) // Added limit for consistency
                ->get()
                ->toArray();

            // FIXED: Discounted Products - Remove limit(1) from images
            $discountedProducts = Product::with([
                'images' => function ($query) {
                    $query->where('status', 1)->orderBy('id', 'asc'); // Removed limit(1)
                },
                'category'
            ])
                ->where('product_discount', '>', 0)
                ->where('status', 1)
                ->limit(6)
                ->inRandomOrder()
                ->get()
                ->toArray();
            $featuredProducts = Product::with([
                'images' => function ($query) {
                    $query->where('status', 1)->orderBy('id', 'asc');
                },
                'category'
            ])
                ->where([
                    'is_featured' => 'Yes',
                    'status' => 1
                ])
                ->limit(12)
                ->get()
                ->toArray();

            // Categories - same as your current code
            $categories = Category::where('status', '1')
                ->where('parent_id', '0')
                ->get();

            // Meta tags - same as your current code
            $meta_title = 'Multi Vendor E-commerce Website';
            $meta_description = 'Online Shopping Website which deals in Clothing, Electronics & Appliances Products';
            $meta_keywords = 'eshop website, online shopping, multi vendor e-commerce';

            // Debug logging (optional - remove in production)
            if (config('app.debug')) {
                Log::info('Homepage Product Counts', [
                    'new_products' => count($newProducts),
                    'best_sellers' => count($bestSellers),
                    'discounted_products' => count($discountedProducts),
                    'featured_products' => count($featuredProducts),
                ]);

                // Log image counts for first few products
                if (!empty($featuredProducts)) {
                    $imageDebug = [];
                    foreach (array_slice($featuredProducts, 0, 3) as $product) {
                        $imageDebug[] = [
                            'product_id' => $product['id'],
                            'product_name' => $product['product_name'],
                            'images_count' => isset($product['images']) ? count($product['images']) : 0,
                            'has_product_image' => !empty($product['product_image']),
                            'first_image' => isset($product['images'][0]['image']) ? $product['images'][0]['image'] : 'none'
                        ];
                    }
                    Log::info('Featured Products Image Debug', $imageDebug);
                }
            }

            return view('front.index')->with(compact(
                'sliderBanners',
                'fixBanners',
                'newProducts',
                'bestSellers',
                'discountedProducts',
                'featuredProducts',
                'meta_title',
                'meta_description',
                'meta_keywords',
                'categories'
            ));
        } catch (\Exception $e) {
            Log::error('Homepage Error: ' . $e->getMessage());

            // Return with empty arrays to prevent page crash
            return view('front.index')->with([
                'sliderBanners' => [],
                'fixBanners' => [],
                'newProducts' => [],
                'bestSellers' => [],
                'discountedProducts' => [],
                'featuredProducts' => [],
                'meta_title' => 'Multi Vendor E-commerce Website',
                'meta_description' => 'Online Shopping Website',
                'meta_keywords' => 'eshop website, online shopping',
                'categories' => collect([])
            ]);
        }
    } //End Method

    public function homeSearch(Request $request)
    {
        try {
            $query = trim($request->q);

            // Agar query empty ho to back bhej do with message
            if (empty($query)) {
                return redirect()->back()->with('error', 'Please enter a search term.');
            }

            // Products ke saath relations load
            $categoryProducts = Product::with(['images', 'brand', 'ratings'])
                ->where('product_name', 'LIKE', "%{$query}%")
                ->paginate(20);

            // Agar results empty hain
            if ($categoryProducts->isEmpty()) {
                return view('front.products.search', [
                    'categoryProducts' => $categoryProducts,
                    'query' => $query,
                    'message' => 'No products found matching your search.'
                ]);
            }

            return view('front.products.search', compact('categoryProducts', 'query'));
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Something went wrong while searching. Please try again later.');
        }
    }
}
