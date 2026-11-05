<?php

namespace App\Http\Controllers;

use App\Models\{Order, Song};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Klik "Beli": buat order berstatus PENDING lalu ke halaman pembayaran
    public function store(Song $song)
    {
        abort_unless($song->is_active, 404);
        $user = auth()->user();

        if (in_array($song->id, $user->purchasedSongIds())) {
            return back()->with('error', 'Kamu sudah memiliki lagu ini.');
        }

        // Kalau sudah ada order pending untuk lagu yang sama, pakai itu (hindari order dobel)
        $existing = Order::where('user_id', $user->id)->where('status', 'pending')
            ->whereHas('items', fn ($q) => $q->where('song_id', $song->id))->first();
        if ($existing) {
            return redirect()->route('orders.pay', $existing);
        }

        $order = DB::transaction(function () use ($song, $user) {
            $order = Order::create([
                'order_code'  => $this->generateCode(),
                'user_id'     => $user->id,
                'total_price' => $song->price,
                'status'      => 'pending',
            ]);

            // Salin (snapshot) info dari tabel lagu, album, dan genre ke item order
            $order->items()->create([
                'song_id'     => $song->id,
                'price'       => $song->price,
                'song_title'  => $song->title,
                'album_title' => $song->album->title,
                'genre_name'  => $song->genre->name,
            ]);

            return $order;
        });

        return redirect()->route('orders.pay', $order);
    }

    // Halaman pilih metode pembayaran
    public function pay(Order $order)
    {
        $this->authorizeOwner($order);
        abort_unless($order->status === 'pending', 404);

        return view('orders.pay', ['order' => $order->load('items')]);
    }

    // Klik "Bayar": SIMULASI, tanpa validasi pembayaran apa pun. Langsung jadi PAID.
    public function confirm(Request $request, Order $order)
    {
        $this->authorizeOwner($order);
        abort_unless($order->status === 'pending', 404);

        $data = $request->validate([
            'payment_method' => 'required|in:transfer,e-wallet,kartu',
        ]);

        $order->update([
            'payment_method' => $data['payment_method'],
            'status'         => 'paid',
            'paid_at'        => now(),
        ]);

        return redirect()->route('orders.index')->with('success', "Pembayaran {$order->order_code} berhasil (simulasi).");
    }

    // Riwayat pembelian milik user yang login
    public function index()
    {
        return view('orders.index', [
            'orders' => auth()->user()->orders()->with('items')->latest()->get(),
        ]);
    }

    // Format ID transaksi: TRX-20261103-0001 (nomor urut per hari)
    private function generateCode(): string
    {
        $count = Order::whereDate('created_at', today())->count() + 1;

        return 'TRX-' . now()->format('Ymd') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // User tidak boleh membuka order milik user lain
    private function authorizeOwner(Order $order): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
    }
}