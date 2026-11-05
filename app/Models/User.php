<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    protected $fillable = ['name', 'email', 'password', 'role'];

    // Cek apakah user ini admin (dipakai untuk membatasi akses halaman admin)
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    // Lagu-lagu favorit user (lewat tabel pivot "favorites")
    public function favorites()
    {
        return $this->belongsToMany(Song::class, 'favorites')->withTimestamps();
    }

    // Semua transaksi milik user
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // ID lagu yang sudah dibayar (status paid) oleh user ini
    public function purchasedSongIds(): array
    {
        return OrderItem::whereHas('order', fn ($q) => $q
                ->where('user_id', $this->id)
                ->where('status', 'paid'))
            ->pluck('song_id')->filter()->values()->all();
    }
}
