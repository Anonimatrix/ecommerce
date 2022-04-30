<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adress extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'adress',
        'city',
        'country',
        'postal_code',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Adress::class);
    }
}
