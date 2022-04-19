<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

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
}
