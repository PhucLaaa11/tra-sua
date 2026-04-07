<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'stock',
        'price',
        'description',
        'image',
        'is_featured',
        'is_active'
    ];

    /**
     * A product belongs to a category.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A product has many reviews.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class)->orderBy('created_at', 'desc');
    }

    /**
     * Calculate average rating (for convenient display later).
     */
    public function avgRating()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}