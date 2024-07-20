<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $table = 'address';
    protected $fillable = [
        'name',
        'address1',
        'address2',
        'city',
        'state',
        'pincode',
        'mobileno',
        'customer_id',
        'address',
        'country_id'
     
    ];
    use HasFactory;
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function country()
{
    return $this->belongsTo(Country::class);
}
}
