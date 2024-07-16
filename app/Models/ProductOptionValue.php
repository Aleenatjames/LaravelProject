<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductOptionValue extends Model
{
    use HasFactory;
    protected $table = 'product_option_values';
    protected $fillable = ['option_id','value','price','quantity','product_type'];

    public function option()
    {
        return $this->belongsTo(Option::class);
    }
}
