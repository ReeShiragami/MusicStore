<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;

class LibraryController extends Controller
{
    // Halaman "Koleksi Saya": semua lagu yang sudah dibayar oleh user yang login
    public function index()
    {
        $items = OrderItem::with(['order', 'song'])
            // Hanya order milik user ini yang statusnya sudah dibayar
            ->whereHas('order', fn ($q) => $q
                ->where('user_id', auth()->id())
                ->where('status', 'paid'))
            ->get()
            // Urutkan dari pembelian terbaru
            ->sortByDesc(fn ($item) => $item->order->paid_at)
            ->values();

        return view('library', [
            'items'      => $items,
            // Total pengeluaran, ditampilkan di ringkasan atas halaman
            'totalSpent' => config('store.currency_symbol') . number_format($items->sum('price'), 0, '.', ','),
        ]);
    }
}