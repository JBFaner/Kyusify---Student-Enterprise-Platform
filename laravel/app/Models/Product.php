<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'enterprise_id',
        'name',
        'description',
        'price',
        'stock',
        'category',
        'image_path',
        'status',
    ];

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
