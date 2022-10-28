<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id',
        'product_id',
        'address_id',
        'quantity',
        'status',
        'unit_price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function chat()
    {
        return $this->morphOne(Chat::class, 'chateable');
    }

    public function shipp()
    {
        return $this->hasOne(Shipp::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function complaint()
    {
        return $this->hasOne(Complaint::class);
    }

    public function getTotalPriceAttribute()
    {
        return $this->unit_price * $this->quantity + ($this->shipp_price ?? 0);
    }
}
