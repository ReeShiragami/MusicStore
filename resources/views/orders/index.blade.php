@extends('layouts.store')
@section('title', 'Pembelian Saya')

@section('content')
    <h1>Pembelian Saya</h1>

    <div class="panel">
        <table>
            <thead><tr><th>ID Transaksi</th><th>Lagu</th><th>Metode</th><th>Total</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->order_code }}</td>
                        <td>{{ $order->items->pluck('song_title')->join(', ') }}</td>
                        <td>{{ $order->payment_method ?? '-' }}</td>
                        <td>{{ $order->total_price_formatted }}</td>
                        <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                        <td>
                            {{-- Order yang belum dibayar bisa dilanjutkan --}}
                            @if ($order->status === 'pending')
                                <a class="btn" href="{{ route('orders.pay', $order) }}">Bayar</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6">Belum ada pembelian.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection