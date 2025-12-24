<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Pastikan user login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // 2. Cek role DENGAN AMAN (tidak bikin 500)
        if (isset($user->role) && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // 3. Ambil pesanan user
        $orders = Order::where('user_id', $user->id)
            ->latest()
            ->get();

        // 4. Pastikan view lowercase
        return view('user.dashboard', [
            'orders' => $orders
        ]);
    }
}
