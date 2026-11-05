<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, User};

class DashboardController extends Controller
{
    // Dashboard: tampilkan order setiap user (User A & User B) beserta lagunya
    public function index()
    {
        return view('admin.dashboard', [
            'users' => User::where('role', 'user')
                ->with(['orders' => fn ($q) => $q->with('items')->latest()])
                ->orderBy('name')->get(),
            'count' => [
                'pending'   => Order::where('status', 'pending')->count(),
                'paid'      => Order::where('status', 'paid')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ],
        ]);
    }

    // Tombol "Batalkan": ubah status jadi cancelled (data tidak dihapus, jadi tetap ada jejaknya)
    public function cancel(Order $order)
    {
        if ($order->status !== 'cancelled') {
            $order->update(['status' => 'cancelled', 'cancelled_at' => now()]);
        }

        return back()->with('success', "Order {$order->order_code} dibatalkan.");
    }
}