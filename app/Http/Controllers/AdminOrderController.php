<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; 
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminOrderController extends Controller
{
    /**
     * Menampilkan Dashboard Admin dengan statistik dan daftar pesanan.
     */
    public function index()
    {
        // Mengambil semua order dengan relasi user agar tidak error saat memanggil $order->user->name
        $orders = Order::with('user')->latest()->get();
        
        // Mengambil daftar user untuk dropdown "Pilih Pelanggan" di modal
        $users = User::where('role', 'user')->get(); 
        
        // Menghitung total omset dari pesanan yang tidak batal
        $totalOmset = Order::where('status', '!=', 'batal')->sum('total_price');
        
        return view('admin.dashboard', compact('orders', 'users', 'totalOmset'));
    }

    /**
     * Halaman Kelola User (Opsional jika Anda punya view admin/users.blade.php)
     */
    public function userIndex()
    {
        $users = User::latest()->get();
        return view('admin.users', compact('users'));
    }

    /**
     * CRUD: Create - Menyimpan pesanan baru dari modal Admin
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Sesuaikan dengan field di modal view Anda)
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'category'      => 'required|string',
            'package_name'  => 'required|string',
            'printing_type' => 'required|string',
            'size'          => 'required|string',
            'quantity'      => 'required|integer|min:1',
            'total_price'   => 'required|numeric|min:0',
            'design_file'   => 'nullable|image|mimes:jpeg,png,jpg|max:10240',
            'notes'         => 'nullable|string'
        ]);

        // 2. Inisialisasi Model
        $order = new Order();
        $order->user_id       = $request->user_id;
        $order->category      = $request->category; 
        $order->package_name  = $request->package_name;
        $order->printing_type = $request->printing_type;
        $order->size          = $request->size;
        $order->quantity      = $request->quantity;
        $order->total_price   = $request->total_price;
        $order->notes         = $request->notes;
        $order->status        = 'pending'; // Default status

        // 3. Handle Upload File (Khusus Vercel: file akan tersimpan sementara)
        if ($request->hasFile('design_file')) {
            $path = $request->file('design_file')->store('designs', 'public');
            $order->design_file = $path;
        }

        $order->save();

        return redirect()->back()->with('success', 'Pesanan baru berhasil ditambahkan!');
    }

    /**
     * CRUD: Update Status - Mengubah alur produksi pesanan
     */
    public function updateStatus(Order $order, $status) 
    {
        $validStatuses = ['pending', 'proses', 'dikerjakan', 'selesai_produksi', 'pengantaran', 'sampai', 'batal'];

        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Status tidak valid!');
        }

        // Proteksi: Jika sudah sampai tidak bisa dibatalkan
        if ($status === 'batal' && $order->status === 'sampai') {
            return back()->with('error', 'Pesanan yang sudah sampai tidak bisa dibatalkan.');
        }

        $order->update(['status' => $status]);

        return back()->with('success', 'Status pesanan #' . $order->id . ' berhasil diupdate menjadi ' . strtoupper($status));
    }

    /**
     * CRUD: Delete - Menghapus data pesanan secara permanen
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        // Cek apakah yang menghapus adalah admin
        if ($user->role === 'admin') {
            // Hapus file desain dari storage jika ada
            if ($order->design_file && Storage::disk('public')->exists($order->design_file)) {
                Storage::disk('public')->delete($order->design_file);
            }

            $order->delete();
            return redirect()->back()->with('success', 'Data pesanan berhasil dihapus permanen.');
        }

        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menghapus data ini.');
    }
}