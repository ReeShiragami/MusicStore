<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('songs', function (Blueprint $table) {
            $table->id();

            // Relasi ke album & genre. restrictOnDelete = album/genre tidak bisa
            // dihapus selama masih dipakai lagu (mencegah data yatim).
            $table->foreignId('album_id')->constrained()->restrictOnDelete();
            $table->foreignId('genre_id')->constrained()->restrictOnDelete();

            $table->string('title');                          // judul lagu
            $table->unsignedInteger('price');                 // harga dalam Rupiah (tanpa desimal)
            $table->unsignedSmallInteger('duration_seconds'); // durasi, contoh 215 = 3:35
            $table->unsignedTinyInteger('track_number')->default(1); // urutan di album
            $table->string('cover_image')->nullable();        // gambar 1:1 untuk lagu ini
            $table->boolean('is_active')->default(true);      // false = disembunyikan dari katalog
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
