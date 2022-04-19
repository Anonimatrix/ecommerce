<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory, Sluggable, SoftDeletes;

    protected $fillable = [
        'subcategorie_id',
        'user_id',
        'title',
        'slug',
        'description',
        'price',
        'stock',
        'paused'
    ];

    protected $casts = [
        'paused_at' => 'datetime',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function photos()
    {
        return $this->morphMany(Photo::class, 'photoable');
    }

    public function subcategorie()
    {
        return $this->belongsTo(Subcategorie::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function pause()
    {
        $this->paused_at = Carbon::now();

        $this->save();
    }

    public function paused()
    {
        if ($this->paused_at) {
            return $this->paused_at <= Carbon::now();
        }

        return false;
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }
}
