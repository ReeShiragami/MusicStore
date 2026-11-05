{{-- Satu kartu lagu. Variabel yang dibutuhkan: $song, $favoriteIds, $boughtIds --}}
<article class="song-card">

    {{-- GAMBAR LAGU 1:1. Ukuran diatur di CSS (.song-cover & --card-min-width) --}}
    <img class="song-cover" src="{{ $song->cover_url }}" alt="Sampul {{ $song->title }}" loading="lazy">

    <h3 class="song-title">{{ $song->title }}</h3>
    {{-- Info dari tabel album, genre, dan durasi --}}
    <p class="song-meta">{{ $song->album->title }} · {{ $song->genre->name }} · {{ $song->duration_formatted }}</p>

    <div class="song-actions">
        <span class="price">{{ $song->price_formatted }}</span>

        {{-- Tombol BELI (atau badge kalau sudah dimiliki) --}}
        @if (in_array($song->id, $boughtIds))
            <span class="badge">Dimiliki</span>
        @else
            <form method="POST" action="{{ route('orders.store', $song) }}">
                @csrf
                <button class="btn btn-primary" type="submit">Beli</button>
            </form>
        @endif

        {{-- Tombol FAVORIT ♥ (warna aktif diatur di CSS .btn-heart.is-active) --}}
        <form method="POST" action="{{ route('favorites.toggle', $song) }}">
            @csrf
            <button class="btn-heart {{ in_array($song->id, $favoriteIds) ? 'is-active' : '' }}" type="submit" title="Favorit">♥</button>
        </form>
    </div>
</article>