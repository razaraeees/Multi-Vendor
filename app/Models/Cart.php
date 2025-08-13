<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id', 'user_id', 'product_id', 'selected_attributes', 'quantity'
    ];

    // Relationship of a Cart Item `carts` table with a Product `products` table (every cart item belongs to a product)    
    public function product()
    { // A Product `products` belongs to a Vendor `vendors`, and the Foreign Key of the Relationship is the `product_id` column
        return $this->belongsTo('App\Models\Product', 'product_id'); // 'product_id' is the Foreign Key of the Relationship
    }

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

        $items = $items->filter(function ($item) {
            return $item->product !== null;
        });

        return $items; 
    }

    // 🔥 Fixed: Handle both string and array formats safely
    public function getSelectedAttributesAttribute($value)
    {
        // If it's already an array, return it
        if (is_array($value)) {
            return $value;
        }
        
        // If it's a string, try to decode it
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        // If it's null or anything else, return empty array
        return [];
    }

    // 🔥 Added: Mutator to ensure consistent storage
    public function setSelectedAttributesAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['selected_attributes'] = json_encode($value);
        } else {
            $this->attributes['selected_attributes'] = $value;
        }
    }
}