<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;


    protected $table = 'shipping';

    protected $fillable = [
        'shipping_charge',
        'free_shipping_min_amount',
    ];
}
