<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'images',
        'sizes',
        'description',
        'price',
        'is_active',
        'is_featured',
        'in_stock',
        'on_sale'
    ];

    protected $casts = [
        'images' => 'array'
    ];

    public function setSizesAttribute($value) {
        $this->attributes['sizes'] = is_array($value) ? implode(',', $value) : $value;
    }

    public function getSizesAttribute($value) {
        return explode(',', $value);
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function orderItem() {
        return $this->hasMany(OrderItem::class);
    }
}
