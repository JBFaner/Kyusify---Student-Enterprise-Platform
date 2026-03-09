<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'logo_path',
        'store_branding',
        'status',
        'is_student_verified',
        'contact_email',
        'contact_phone',
        'document_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
