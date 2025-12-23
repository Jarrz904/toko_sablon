<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cek Role Admin
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 2. Jika User biasa, ambil data pesanan miliknya
        $orders = Order::where('user_id', Auth::id())
                       ->latest()
                       ->get();

        // 3. Pastikan file ini ada di resources/views/dashboard.blade.php
        return view('user.dashboard', compact('orders')); 
    }
}