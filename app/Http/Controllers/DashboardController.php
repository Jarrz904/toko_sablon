<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Cek Auth untuk keamanan di Vercel
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek Role Admin
        // Pastikan kolom 'role' di TiDB Cloud sudah ada isinya ('admin' atau 'user')
        if (Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 3. Jika User biasa, ambil data pesanan miliknya
        $orders = Order::where('user_id', Auth::id())
                        ->latest()
                        ->get();

        // 4. Case-Sensitive: Pastikan folder 'user' huruf kecil di resources/views/user/dashboard.blade.php
        return view('user.dashboard', compact('orders')); 
    }
}