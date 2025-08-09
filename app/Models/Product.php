<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

use App\Models\Category;

/**
 * @property string|null $product_image
 */

class Product extends Model
{
    use HasFactory;




    // Every 'product' belongs to a 'section'
    public function section()
    {
        return $this->belongsTo('App\Models\Section', 'section_id'); // 'section_id' is the foreign key
    }

    // Every 'product' belongs to a 'category'
    public function category()
    {
        return $this->belongsTo('App\Models\Category', 'category_id'); // 'category_id' is the foreign key
    }

    public function brand()
    { // Every product belongs to some brand    // this relationship method is used in Front/ProductsController.php    
        return $this->belongsTo('App\Models\Brand', 'brand_id'); // 'brand_id' is the foreign key
    }

    // Every product has many attributes
    public function attributes()
    {
        return $this->hasMany('App\Models\ProductsAttribute', 'product_id')
            ->with('attribute'); // Optional: parent attribute (e.g., "Color")
    }


    // Every product has many images
    public function images()
    {
        return $this->hasMany('App\Models\ProductsImage');
    }


    // Relationship of a Product `products` table with Vendor `vendors` table (every product belongs to a vendor)    
    public function vendor()
    {
        return $this->belongsTo('App\Models\Vendor', 'vendor_id')->with('vendorbusinessdetails'); // 'vendor_id' is the Foreign Key of the Relationship    
    }




    public static function getDiscountPrice($product_id)
    {
        // Step 1: Product details fetch karo
        $productDetails = Product::select('product_price', 'product_discount', 'category_id')
            ->where('id', $product_id)
            ->first();

        // Agar product nahi mila, toh 0 return karo
        if (!$productDetails) {
            return 0;
        }

        // Step 2: Category discount check karo
        $category_id = $productDetails->category_id;

        // Agar category_id valid hai, toh category discount fetch karo
        $categoryDiscount = 0;
        if ($category_id) {
            $category = Category::where('id', $category_id)->value('category_discount');
            $categoryDiscount = $category ?? 0; // Agar category nahi mili toh 0
        }

        $price = $productDetails->product_price;
        $productDiscount = $productDetails->product_discount ?? 0;

        // Step 3: Discount calculate karo
        if ($productDiscount > 0) {
            // Product level discount
            return $price - ($price * $productDiscount / 100);
        } elseif ($categoryDiscount > 0) {
            // Category level discount
            return $price - ($price * $categoryDiscount / 100);
        }

        // Agar koi discount nahi hai
        return $price; // Ya 0 return karne ki jagah original price return karo (best practice)
    }

    public static function getDiscountAttributePrice($product_id, $attributes = null)
    {
        // $attributes can be:
        // - null
        // - a string (legacy 'size')
        // - an array of ['attribute_id' => X, 'attribute_value_id' => Y] items

        // Build query
        $query = \App\Models\ProductsAttribute::where('product_id', $product_id);

        if (is_string($attributes) && $attributes !== '') {
            // legacy: size string
            $query->where('size', $attributes);
        } elseif (is_array($attributes) && count($attributes) > 0) {
            // attributes array: add where clauses for each pair
            foreach ($attributes as $attr) {
                if (isset($attr['attribute_id']) && isset($attr['attribute_value_id'])) {
                    $query->where('attribute_id', $attr['attribute_id'])
                        ->where('attribute_value_id', $attr['attribute_value_id']);
                }
            }
        } // else no extra filters (will pick first matching product attribute)

        $proAttr = $query->first();

        // If no attribute row found, return zeros (safe)
        if (!$proAttr) {
            Log::warning("getDiscountAttributePrice(): ProductsAttribute not found", [
                'product_id' => $product_id,
                'attributes' => $attributes
            ]);
            return [
                'product_price' => 0,
                'final_price'   => 0,
                'discount'      => 0
            ];
        }

        // Determine the price column safely (some tables use different column names)
        $pa = $proAttr->toArray();
        $price = null;
        if (array_key_exists('price', $pa)) {
            $price = (float)$pa['price'];
        } elseif (array_key_exists('product_price', $pa)) {
            $price = (float)$pa['product_price'];
        } elseif (array_key_exists('attr_price', $pa)) {
            $price = (float)$pa['attr_price'];
        } else {
            Log::warning("getDiscountAttributePrice(): price column missing on products_attributes", ['products_attributes_row' => $pa]);
            return [
                'product_price' => 0,
                'final_price'   => 0,
                'discount'      => 0
            ];
        }

        // Get product and category discounts (safe)
        $proDetails = Product::select('product_discount', 'category_id')->find($product_id);
        $productDiscount = $proDetails->product_discount ?? 0;
        $categoryDiscount = 0;
        if (!empty($proDetails->category_id)) {
            $cat = Category::select('category_discount')->find($proDetails->category_id);
            $categoryDiscount = $cat->category_discount ?? 0;
        }

        // Calculate final price and discount
        if ($productDiscount > 0) {
            $final_price = $price - ($price * $productDiscount / 100);
            $discount = $price - $final_price;
        } elseif ($categoryDiscount > 0) {
            $final_price = $price - ($price * $categoryDiscount / 100);
            $discount = $price - $final_price;
        } else {
            $final_price = $price;
            $discount = 0;
        }

        return [
            'product_price' => round($price, 2),
            'final_price'   => round($final_price, 2),
            'discount'      => round($discount, 2)
        ];
    }



    public static function isProductNew($product_id)
    {
        // Get the last (latest) three 3 added products ids
        $productIds = Product::select('id')->where('status', 1)->orderBy('id', 'Desc')->limit(3)->pluck('id');
        $productIds = json_decode(json_encode($productIds, true));

        if (in_array($product_id, $productIds)) { // if the passed in $product_id is in the array of the last (latest) 3 added products ids
            $isProductNew = 'Yes';
        } else {
            $isProductNew = 'No';
        }


        return $isProductNew;
    }

    public function product_iamge()
    {

        return $this->hasMany('');
    }


    public static function getProductImage($product_id)
    {
        $getProductImage = Product::select('product_image')->where('id', $product_id)->first()->toArray();


        return $getProductImage['product_image'];
    }
    public function ratings()
    {
        return $this->hasMany(Rating::class, 'product_id');
    }


    // Note: We need to prevent orders (upon checkout and payment) of the 'disabled' products (`status` = 0), where the product ITSELF can be disabled in admin/products/products.blade.php (by checking the `products` database table) or a product's attribute (`stock`) can be disabled in 'admin/attributes/add_edit_attributes.blade.php' (by checking the `products_attributes` database table). We also prevent orders of the out of stock / sold-out products (by checking the `products_attributes` database table)
    public static function getProductStatus($product_id)
    {
        $getProductStatus = Product::select('status')->where('id', $product_id)->first();


        return $getProductStatus->status;
    }

    // Delete a product from Cart if it's 'disabled' (`status` = 0) or it's out of stock (sold out)    
    public static function deleteCartProduct($product_id)
    {
        Cart::where('product_id', $product_id)->delete();
    }
}
