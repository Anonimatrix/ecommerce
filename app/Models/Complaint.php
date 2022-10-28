<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'reason',
        'status'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function chat()
    {
        return $this->morphOne(Chat::class, 'chateable');
    }

    public function intermediary()
    {
        return $this->belongsTo(User::class, 'intermediary_id');
    }


    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }
}
