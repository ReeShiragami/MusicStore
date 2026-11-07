@extends('layouts.store')
@section('title', 'Dashboard Admin')

@section('content')
    <h1>Dashboard</h1>

    {{-- Ringkasan jumlah order per status --}}
    <div class="stats">
        <div class="stat"><strong>{{ $count['pending'] }}</strong> Sedang dibeli (pending)</div>
        <div class="stat"><strong>{{ $count['paid'] }}</strong> Sudah dibayar</div>
        <div class="stat"><strong>{{ $count['cancelled'] }}</strong> Dibatalkan</div>
    </div>

    {{-- Satu panel per user (User A dan User B). Layout kolom diatur di CSS (.user-columns) --}}
    <div class="user-columns">
        @foreach ($users as $user)
            <section class="panel">
                <h2>{{ $user->name }}</h2>
                <table>
                    <thead><tr><th>Transaksi</th><th>Lagu</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                        @forelse ($user->orders as $order)
                            <tr>
                                <td>{{ $order->order_code }}<br><small style="color: var(--muted-color)">{{ $order->total_price_formatted }}    </small></td>
                                <td>{{ $order->items->pluck('song_title')->join(', ') }}</td>
                                <td><span class="badge badge-{{ $order->status }}">{{ $order->status }}</span></td>
                                <td>
                                    {{-- Tombol batalkan: hanya untuk order yang belum dibatalkan --}}
                                    @if ($order->status !== 'cancelled')
                                        <form method="POST" action="{{ route('admin.orders.cancel', $order) }}"
                                              onsubmit="return confirm('Batalkan order {{ $order->order_code }}?')">
                                            @csrf
                                            <button class="btn btn-danger" type="submit">Batalkan</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4">Belum ada order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        @endforeach
    </div>
@endsection