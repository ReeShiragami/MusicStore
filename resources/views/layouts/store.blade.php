<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Judul tab browser. Nama artist diambil dari config/store.php --}}
    <title>@yield('title', 'Toko Musik') · {{ config('store.artist_name') }}</title>
    {{-- Semua gaya visual ada di public/css/store.css --}}
    <link rel="stylesheet" href="{{ asset('css/store.css') }}">
</head>
<body>

    {{-- ===== HEADER / BAR ATAS. EDIT DI SINI untuk mengubah menu ===== --}}
    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="{{ route('dashboard') }}">{{ config('store.artist_name') }}</a>

            <nav class="nav">
                @if (auth()->user()->isAdmin())
                    {{-- Menu untuk ADMIN --}}
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    <a href="{{ route('admin.songs.index') }}">Lagu</a>
                    <a href="{{ route('admin.genres.index') }}">Genre</a>
                    <a href="{{ route('admin.albums.index') }}">Album</a>
                @else
                    {{-- Menu untuk USER --}}
                    <a href="{{ route('catalog') }}">Katalog</a>
                    <a href="{{ route('favorites.index') }}">Favorit</a>
                    <a href="{{ route('orders.index') }}">Pembelian Saya</a>
                @endif

                <span>{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn" type="submit">Keluar</button>
                </form>
            </nav>
        </div>
    </header>

    {{-- ===== AREA ISI HALAMAN ===== --}}
    <main class="container">
        {{-- Pesan sukses / error dari controller --}}
        @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
        @if (session('error'))   <div class="alert alert-error">{{ session('error') }}</div> @endif
        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error) <div>{{ $error }}</div> @endforeach
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ===== FOOTER. EDIT DI SINI untuk mengubah teks bawah ===== --}}
    <footer class="site-footer">
        &copy; {{ date('Y') }} {{ config('store.artist_name') }} · Simulasi toko musik digital
    </footer>

</body>
</html>