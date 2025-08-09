<?php

namespace App\Http\Controllers\front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        // Since only logged-in users can add/remove, show wishlist only for logged-in users
        if (!Auth::check()) {
            return redirect('/login');
        }

        $wishlistItems = Wishlist::with(['product.category', 'product.images'])
            ->where('user_id', Auth::id())
            ->get();

        $wishlistProductIds = $wishlistItems->pluck('product_id')->toArray();

        return view('front.wishlist.wishlis_listing', compact('wishlistItems', 'wishlistProductIds'));
    }

    public function addWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add items to your wishlist.'
            ], 401);
        }

        $productId = $request->product_id;

        $exists = Wishlist::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Already in wishlist'
            ]);
        }

        Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'session_id' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Added to wishlist'
        ]);
    }

    public function removeWishlist(Request $request)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to remove items from your wishlist.'
            ], 401);
        }

        $productId = $request->product_id;

        Wishlist::where('product_id', $productId)
            ->where('user_id', Auth::id())
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Removed from wishlist'
        ]);
    }
}
