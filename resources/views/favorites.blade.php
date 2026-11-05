@extends('layouts.store')
@section('title', 'Favorit')

@section('content')
    <h1>Lagu Favorit</h1>

    <div class="song-grid">
        @forelse ($songs as $song)
            @include('partials.song-card')
        @empty
            <p>Belum ada lagu favorit. Tekan ♥ di katalog untuk menambahkan.</p>
        @endforelse
    </div>
@endsection