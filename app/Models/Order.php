<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_code', 'user_id', 'total_price',
        'payment_method', 'status', 'paid_at', 'cancelled_at',
    ];

    protected $casts = ['paid_at' => 'datetime', 'cancelled_at' => 'datetime'];

    public function user()  { return $this->belongsTo(User::class); }
    public function items() { return $this->hasMany(OrderItem::class); }
    // Total transaksi dengan format mata uang
    public function getTotalPriceFormattedAttribute(): string
    {
        return config('store.currency_symbol') . number_format($this->total_price, 0, '.', ',');
    }
}