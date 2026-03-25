<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuickReply extends Model
{
    protected $fillable = [
        'enterprise_id', 'question', 'answer', 'sort_order',
    ];

    public function enterprise()
    {
        return $this->belongsTo(Enterprise::class);
    }
}
