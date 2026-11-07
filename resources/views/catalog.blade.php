@extends('layouts.store')
@section('title', 'Katalog')

@section('content')

    {{-- ===== HERO: nama artist & tagline. Teks diubah di config/store.php ===== --}}
    <section class="hero">
        <h1>{{ config('store.artist_name') }}</h1>
        <p>{{ config('store.tagline') }}</p>
    </section>

    {{-- ===== FILTER GENRE & SEARCH ===== --}}
    <div class="search-filter">
        <form method="GET" action="{{ route('catalog') }}" class="search-form">
            <div class="search-group">
                <input type="text" name="search" placeholder="Cari lagu..." value="{{ request('search') }}">
            </div>
            <div class="genre-filter">
                <a class="chip {{ request('genre') ? '' : 'is-active' }}" href="{{ route('catalog', ['search' => request('search')]) }}">Semua</a>
                @foreach ($genres as $genre)
                    <a class="chip {{ request('genre') === $genre->slug ? 'is-active' : '' }}"
                       href="{{ route('catalog', ['genre' => $genre->slug, 'search' => request('search')]) }}">{{ $genre->name }}</a>
                @endforeach
            </div>
        </form>
    </div>

    {{-- ===== GRID LAGU. Jumlah kolom & ukuran kartu diatur di CSS (.song-grid) ===== --}}
    <div class="song-grid">
        @forelse ($songs as $song)
            @include('partials.song-card')
        @empty
            <p>Belum ada lagu.</p>
        @endforelse
    </div>

@endsection