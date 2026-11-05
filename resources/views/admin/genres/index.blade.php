@extends('layouts.store')
@section('title', 'Kelola Genre')

@section('content')
    <h1>Kelola Genre</h1>

    {{-- Form tambah genre --}}
    <form class="add-form" method="POST" action="{{ route('admin.genres.store') }}">
        @csrf
        <input type="text" name="name" placeholder="Nama genre baru" required>
        <button class="btn btn-primary" type="submit">Tambah</button>
    </form>

    <div class="panel">
        <table>
            <thead><tr><th>Nama</th><th>Jumlah lagu</th><th></th></tr></thead>
            <tbody>
                @foreach ($genres as $genre)
                    <tr>
                        <td>
                            {{-- Edit langsung di baris: ubah nama lalu klik Simpan --}}
                            <form class="inline-form" method="POST" action="{{ route('admin.genres.update', $genre) }}">
                                @csrf @method('PUT')
                                <input type="text" name="name" value="{{ $genre->name }}" required>
                                <button class="btn" type="submit">Simpan</button>
                            </form>
                        </td>
                        <td>{{ $genre->songs_count }}</td>
                        <td>
                            <form method="POST" action="{{ route('admin.genres.destroy', $genre) }}" onsubmit="return confirm('Hapus genre ini?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger" type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection