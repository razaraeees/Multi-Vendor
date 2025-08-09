<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;


class Cart extends Model
{
    use HasFactory;



    // Relationship of a Cart Item `carts` table with a Product `products` table (every cart item belongs to a product)    
    public function product()
    { // A Product `products` belongs to a Vendor `vendors`, and the Foreign Key of the Relationship is the `product_id` column
        return $this->belongsTo('App\Models\Product', 'product_id'); // 'product_id' is the Foreign Key of the Relationship
    }


    // App\Models\Cart.php

    public static function getCartItems()
    {
        $items = Auth::check()
            ? Cart::with([
                'product' => function ($query) {
                    $query->select(
                        'id',
                        'category_id',
                        'product_name',
                        'product_code',
                        'product_color',
                        'product_weight',
                        'product_price',
                        'product_discount'
                    );
                },
                'product.images',
                'product.attributes',
                'product.attributes.attribute'
            ])
            ->where('user_id', Auth::user()->id)
            ->orderBy('id', 'Desc')
            ->get()
            : Cart::with([
                'product' => function ($query) {
                    $query->select(
                        'id',
                        'category_id',
                        'product_name',
                        'product_code',
                        'product_color',
                        'product_weight',
                        'product_price',
                        'product_discount'
                    );
                },
                'product.images',
                'product.attributes',
                'product.attributes.attribute'
            ])
            ->where('session_id', Session::get('session_id'))
            ->orderBy('id', 'Desc')
            ->get();

        // 🔥 Filter: Sirf wahi items jinke product ho
        $items = $items->filter(function ($item) {
            return $item->product !== null;
        });

        return $items; // Return filtered collection
    }

    // JSON ko auto decode karega
    public function getSelectedAttributesAttribute($value)
    {
        return json_decode($value, true) ?? [];
    }
}
