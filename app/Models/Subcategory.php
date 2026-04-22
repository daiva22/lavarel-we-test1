<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $fillable = [
        'name',
        'category',
        'image',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}