<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function adress()
    {
        return $this->belongsTo(Adress::class);
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
}