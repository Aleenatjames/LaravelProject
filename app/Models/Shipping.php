<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    protected $table = 'shipping_charges';
    protected $fillable = ['country_id', 'amount', 'options'];

    // Define the relationship to the Country model
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
    use HasFactory;
}
