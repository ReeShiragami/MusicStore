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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();

            // nullOnDelete: kalau admin menghapus lagu, riwayat pembelian TETAP ada
            // (song_id jadi null, tapi data snapshot di bawah tetap utuh).
            $table->foreignId('song_id')->nullable()->constrained()->nullOnDelete();

            $table->unsignedInteger('price');       // harga saat dibeli (tidak ikut berubah kalau harga lagu diubah)

            // SNAPSHOT: salinan info dari 3 tabel master saat transaksi terjadi
            $table->string('song_title');
            $table->string('album_title');
            $table->string('genre_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
