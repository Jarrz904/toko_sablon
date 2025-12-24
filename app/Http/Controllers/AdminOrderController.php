<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order; 
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->latest()->get();
        $users = User::all(); 
        
        // LOGIKA: Menghitung total omset hanya untuk pesanan yang TIDAK 'batal'
        $totalOmset = Order::where('status', '!=', 'batal')->sum('total_price');
        
        // Mengirimkan variabel ke view
        return view('admin.dashboard', compact('orders', 'users', 'totalOmset'));
    }

    /**
     * Halaman Kelola User (Terpisah)
     */
    public function userIndex()
    {
        $users = User::latest()->get();
        
        return view('admin.users', compact('users'));
    }

    /**
     * CRUD: Create (Simpan Pesanan Baru)
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'package_name'  => 'required|string',
            'printing_type' => 'required|string',
            'size'          => 'required|string',
            'quantity'      => 'required|integer|min:1',
            'total_price'   => 'required|numeric|min:0',
            'design_file'   => 'required|image|mimes:jpeg,png,jpg|max:10240',
            'notes'         => 'nullable|string'
        ], [
            'package_name.required' => 'Kategori paket wajib dipilih.',
            'design_file.required'  => 'File desain wajib diunggah.',
            'design_file.image'     => 'File harus berupa gambar.',
            'quantity.min'          => 'Jumlah minimal pemesanan tidak mencukupi.',
        ]);

        $order = new Order();
        $order->user_id       = $request->user_id;
        $order->package_name  = $request->package_name;  // Mengambil dari tabel orders kolom package_name
        $order->printing_type = $request->printing_type; // Mengambil dari tabel orders kolom printing_type
        $order->size          = $request->size;
        $order->quantity      = $request->quantity;
        $order->total_price   = $request->total_price;
        $order->notes         = $request->notes;
        $order->status        = 'pending'; 

        if ($request->hasFile('design_file')) {
            $path = $request->file('design_file')->store('designs', 'public');
            $order->design_file = $path;
        }

        $order->save();

        return redirect()->back()->with('success', 'Pesanan baru berhasil disimpan ke database!');
    }

    /**
     * CRUD: Update Status
     */
    public function updateStatus(Order $order, $status) {
        $validStatuses = ['pending', 'proses', 'dikerjakan', 'selesai_produksi', 'pengantaran', 'sampai', 'batal'];

        if (!in_array($status, $validStatuses)) {
            return back()->with('error', 'Status ' . $status . ' tidak dikenali oleh sistem!');
        }

        if ($status === 'batal' && $order->status === 'sampai') {
            return back()->with('error', 'Pesanan yang sudah sampai tidak bisa dibatalkan.');
        }

        $order->update([
            'status' => $status
        ]);

        return back()->with('success', 'Status pesanan #' . $order->id . ' diperbarui.');
    }

    /**
     * CRUD: Delete (Hapus Pesanan Permanen)
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $user = Auth::user();

        // 1. Logika untuk ADMIN
        if ($user->role === 'admin') {
            if ($order->design_file && Storage::disk('public')->exists($order->design_file)) {
                Storage::disk('public')->delete($order->design_file);
            }

            $order->delete();
            return redirect()->back()->with('success', 'Data pesanan berhasil dihapus permanen oleh Admin.');
        }

        // 2. Logika untuk USER BIASA
        if ($order->user_id === $user->id) {
            if ($order->status === 'pending') {
                if ($order->design_file && Storage::disk('public')->exists($order->design_file)) {
                    Storage::disk('public')->delete($order->design_file);
                }

                $order->delete();
                return redirect()->back()->with('success', 'Pesanan Anda berhasil dibatalkan.');
            }
            
            return redirect()->back()->with('error', 'Pesanan sedang diproses dan tidak bisa dihapus sendiri.');
        }

        return redirect()->back()->with('error', 'Tindakan tidak diizinkan.');
    }
}