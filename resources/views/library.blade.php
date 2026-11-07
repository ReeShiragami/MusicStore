@extends('layouts.store')
@section('title', 'Koleksi Saya')

@section('content')
    <h1>Koleksi Saya</h1>

    {{-- ===== RINGKASAN: jumlah lagu & total pengeluaran ===== --}}
    <p class="library-summary">
        <strong>{{ $items->count() }}</strong> lagu · total <strong>{{ $totalSpent }}</strong>
    </p>

    {{-- ===== GRID KOLEKSI. Memakai gaya grid yang sama dengan katalog ===== --}}
    <div class="song-grid">
        @forelse ($items as $item)
            <article class="song-card">

                {{-- Gambar: pakai gambar lagu; kalau lagu sudah dihapus admin, pakai placeholder --}}
                <img class="song-cover"
                     src="{{ $item->song?->cover_url ?? asset('images/placeholder.svg') }}"
                     alt="Sampul {{ $item->song_title }}" loading="lazy">

                {{-- Info dari SNAPSHOT order_items, jadi tetap benar walau admin mengubah lagu --}}
                <div class="song-body">
                    <h3 class="song-title" title="{{ $item->song_title }}">{{ $item->song_title }}</h3>
                    <p class="song-album" title="{{ $item->album_title }}">{{ $item->album_title }}</p>

                    <div class="song-tags">
                        <span class="tag">{{ $item->genre_name }}</span>

                        {{-- Penanda kalau lagu sudah tidak dijual lagi (dihapus/disembunyikan admin) --}}
                        @if (! $item->song || ! $item->song->is_active)
                            <span class="song-duration">Tidak dijual lagi</span>
                        @endif
                    </div>
                </div>

                {{-- BARIS BAWAH: tanggal beli, kode transaksi, dan harga yang dibayar --}}
                <div class="owned-meta">
                    <div>
                        <span class="owned-date">Dibeli {{ $item->order->paid_at->format('d M Y') }}</span>
                        <span class="owned-code">{{ $item->order->order_code }}</span>
                    </div>
                    {{-- Harga saat dibeli (bukan harga lagu sekarang) --}}
                    <span class="price">{{ $item->price_formatted }}</span>
                </div>
            </article>
        @empty
            <p>Belum ada lagu yang dibeli. <a href="{{ route('catalog') }}">Lihat katalog</a></p>
        @endforelse
    </div>
@endsection