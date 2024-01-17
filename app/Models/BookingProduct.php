<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'booking_id', // Add this line to the fillable array
        'product_id',
        'price',
        'discount',
    ];
}
