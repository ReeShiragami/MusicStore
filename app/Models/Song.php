<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    protected $fillable = [
        'album_id', 'genre_id', 'title', 'price',
        'duration_seconds', 'track_number', 'cover_image', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    // Lagu milik satu album dan satu genre
    public function album() { return $this->belongsTo(Album::class); }
    public function genre() { return $this->belongsTo(Genre::class); }

    // User-user yang memfavoritkan lagu ini
    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // URL gambar lagu. Kalau belum ada gambar, pakai placeholder.
    // GANTI di sini: path placeholder kalau mau gambar default lain
    public function getCoverUrlAttribute(): string
    {
        return $this->cover_image
            ? asset('storage/' . $this->cover_image)   // lagu punya gambar: pakai gambar upload
            : asset('images/placeholder.svg');          // belum punya gambar: pakai placeholder
    }

    // Format harga: 272 menjadi "¥272" (Yen tidak memakai desimal)
    public function getPriceFormattedAttribute(): string
    {
        return config('store.currency_symbol') . number_format($this->price, 0, '.', ',');
    }

    // Format durasi: 215 menjadi "3:35"
    public function getDurationFormattedAttribute(): string
    {
        return floor($this->duration_seconds / 60) . ':' . str_pad($this->duration_seconds % 60, 2, '0', STR_PAD_LEFT);

    }
}