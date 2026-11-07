{{-- Satu kartu lagu. Variabel yang dibutuhkan: $song, $favoriteIds, $boughtIds --}}
<article class="song-card">

    {{-- GAMBAR LAGU 1:1. Ukuran diatur di CSS (.song-cover & --card-min-width) --}}
    <img class="song-cover" src="{{ $song->cover_url }}" alt="Sampul {{ $song->title }}" loading="lazy">

    {{-- BAGIAN INFO: judul, album, genre, durasi --}}
    <div class="song-body">
        {{-- Judul maksimal 2 baris. Judul penuh muncul saat kursor diarahkan (atribut title) --}}
        <h3 class="song-title" title="{{ $song->title }}">{{ $song->title }}</h3>

        {{-- Nama album: 1 baris, kalau terlalu panjang dipotong dengan "..." --}}
        <p class="song-album" title="{{ $song->album->title }}">{{ $song->album->title }}</p>

        {{-- Tag genre (kapsul kecil) dan durasi --}}
        <div class="song-tags">
            <span class="tag">{{ $song->genre->name }}</span>
            <span class="song-duration">{{ $song->duration_formatted }}</span>
        </div>
    </div>

    {{-- BARIS BAWAH: harga di kiri, tombol di kanan. Selalu menempel di dasar kartu --}}
    <div class="song-actions">
        <span class="price">{{ $song->price_formatted }}</span>

        <div class="song-buttons">
            {{-- Tombol BELI (atau badge kalau sudah dimiliki). Ukuran keduanya sama --}}
            @if (in_array($song->id, $boughtIds))
                <span class="badge badge-owned">Dimiliki</span>
            @else
                <form method="POST" action="{{ route('orders.store', $song) }}">
                    @csrf
                    <button class="btn btn-primary btn-buy" type="submit">Beli</button>
                </form>
            @endif

            {{-- Tombol FAVORIT ♥ --}}
            <form method="POST" action="{{ route('favorites.toggle', $song) }}">
                @csrf
                <button class="btn-heart {{ in_array($song->id, $favoriteIds) ? 'is-active' : '' }}" type="submit" title="Favorit">♥</button>
            </form>
        </div>
    </div>
</article>