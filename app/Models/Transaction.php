<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'shift_id', 'order_id', 'user_id', 'type',
        'category', 'amount', 'payment_method', 'description'
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
