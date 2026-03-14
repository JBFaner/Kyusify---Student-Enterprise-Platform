<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'enterprise_id',
        'shipping_name',
        'shipping_address',
        'contact_number',
        'status',
        'total_amount',
        'payment_method',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
