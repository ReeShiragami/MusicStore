@extends('layouts.store')
@section('title', 'Pembayaran')

@section('content')
    <h1>Pembayaran</h1>

    {{-- Ringkasan order (data dari snapshot order_items) --}}
    <div class="panel">
        <p>ID Transaksi: <strong>{{ $order->order_code }}</strong></p>
        <table>
            <thead><tr><th>Lagu</th><th>Album</th><th>Genre</th><th>Harga</th></tr></thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->song_title }}</td>
                        <td>{{ $item->album_title }}</td>
                        <td>{{ $item->genre_name }}</td>
                        <td>￥ {{ $item->price_formatted }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <p><strong>Total: ￥ {{ $item->price_formatted }}</strong></p>
    </div>

    {{-- Pilih metode pembayaran (SIMULASI, tidak ada validasi pembayaran) --}}
    <form class="panel form-panel" method="POST" action="{{ route('orders.confirm', $order) }}">
        @csrf
        <label for="payment_method">Metode pembayaran</label>
        <select name="payment_method" id="payment_method" required>
            <option value="transfer">Transfer Bank</option>
            <option value="e-wallet">E-Wallet</option>
            <option value="kartu">Kartu Kredit/Debit</option>
        </select>
        <button class="btn btn-primary" type="submit">Bayar</button>
        <a class="btn" href="{{ route('catalog') }}">Kembali</a>
    </form>
@endsection