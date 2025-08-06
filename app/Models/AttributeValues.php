<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeValues extends Model
{
    use HasFactory;

    protected $table = 'attributes_values';
    protected $fillable = [
        'attribute_id',
        'value',
        'status',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
