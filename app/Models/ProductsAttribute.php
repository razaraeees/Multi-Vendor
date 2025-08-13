<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductsAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes_values';
    protected $fillable = [
        'product_id',
        'attribute_id',
        'attribute_value_id',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id', 'id');
    }

    public function attributeValue()
    {
        return $this->belongsTo(AttributeValues::class, 'attribute_value_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }


        public static function getProductStock($product_id, $attributes)
        {
            // $attributes ek array hoga: [ ['attribute_id' => 1, 'attribute_value_id' => 5], ... ]

            $query = ProductsAttribute::where('product_id', $product_id);

            foreach ($attributes as $attr) {
                $query->where('attribute_id', $attr['attribute_id'])
                    ->where('attribute_value_id', $attr['attribute_value_id']);
            }

            $result = $query->first();

            return $result ? $result->stock : 0;
        }
        public static function getAttributeStatus($product_id, $attributes)
        {
            $query = ProductsAttribute::where('product_id', $product_id);

            foreach ($attributes as $attr) {
                $query->where('attribute_id', $attr['attribute_id'])
                    ->where('attribute_value_id', $attr['attribute_value_id']);
            }

            $result = $query->first();

            return $result ? $result->status : 0;
        }
}
