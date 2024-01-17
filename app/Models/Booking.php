<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'paid_amount',
        // Add other fillable attributes as needed
        'discount_amount',
        'total_amount',
        'user_id',
        // Add other fillable attributes as needed
    ];
    public function products()
    {
        return $this->belongsToMany(Product::class, 'booking_products', 'booking_id', 'product_id')
            ->withPivot('price', 'discount'); // Assuming you have a pivot table 'booking_products'
    }
        public function user()
    {
        return $this->belongsTo(User::class);
    }


}
