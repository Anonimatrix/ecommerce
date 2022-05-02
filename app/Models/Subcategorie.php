<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subcategorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'categorie_id'
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
