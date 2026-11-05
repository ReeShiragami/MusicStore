<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id', 'song_id', 'price',
        'song_title', 'album_title', 'genre_name', // snapshot
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function song()  { return $this->belongsTo(Song::class); }
    // Harga item saat dibeli dengan format mata uang
    public function getPriceFormattedAttribute(): string
    {
        return config('store.currency_symbol') . number_format($this->price, 0, '.', ',');
    }
}