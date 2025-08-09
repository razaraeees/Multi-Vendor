<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WishList extends Model
{
    use HasFactory;


    protected $table = 'wish_lists';
    protected $fillable = ['user_id', 'product_id', 'session_id'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
