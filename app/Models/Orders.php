<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    protected $table = 'orders';
    protected $fillable = [
        
        'total_amount',
        'quantity',
        'customer_address',
        'customer_id',
        'customer_name',
        'delivery_date',
        'status',
        'gift_cards_used',
        'generated_by'
        
     
    ];
    use HasFactory;
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }
}
