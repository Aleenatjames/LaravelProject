<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';
    protected $fillable = [
        'product_id',
        'name',
        'price',
        'quantity',
        'customer_id',
        'image',
        'product_type',
        'option',
        'generated_by',
     
    ];
    use HasFactory;
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    protected $casts = [
        'option' => 'array',
    ];
}
