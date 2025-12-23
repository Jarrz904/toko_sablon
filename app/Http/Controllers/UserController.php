<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Menampilkan Dashboard User
     */
    public function index()
    {
        // Mengambil semua riwayat pesanan milik user
        $orders = Order::where('user_id', Auth::id())->latest()->get();
        return view('user.dashboard', compact('orders'));
    }

    /**
     * Menampilkan Form Buat Pesanan (Halaman Khusus)
     */
    public function create(Request $request)
    {
        $reorderData = null;
        if ($request->has('reorder_id')) {
            $reorderData = Order::where('user_id', Auth::id())
                ->where('id', $request->reorder_id)
                ->first();
        }

        return view('user.order_create', compact('reorderData'));
    }

    /**
     * Memproses Penyimpanan Pesanan
     * Mendukung Pesanan Katalog & Pesanan Custom
     */
    public function store(Request $request)
    {
        // 1. Validasi Input (Disesuaikan dengan name di file Blade/HTML)
        $request->validate([
            'package_name' => 'required|string',
            'design_file'  => 'nullable|image|mimes:jpeg,png,jpg|max:10240', // Nullable agar katalog tidak error
            'size'         => 'required|string',
            'quantity'     => 'required|integer|min:1',
            'total_price'  => 'required',
            'notes'        => 'nullable|string',
        ]);

        // 2. Normalisasi Harga (Membersihkan format "Rp" dan titik jika ada)
        $rawPrice = $request->total_price;
        $cleanPrice = is_numeric($rawPrice) ? $rawPrice : preg_replace('/[^0-9]/', '', $rawPrice);

        // 3. Proses Logika File Desain
        $path = null;
        if ($request->hasFile('design_file')) {
            // Jika user upload file (Custom Design)
            $path = $request->file('design_file')->store('designs', 'public');
        } elseif ($request->has('catalog_image')) {
            // Jika user pilih dari katalog (menggunakan input hidden catalog_image)
            $path = $request->catalog_image;
        }

        /**
         * 4. Simpan ke Database
         * Menyesuaikan nama kolom database (package_name, design_file, dll)
         */
        Order::create([
            'user_id'       => Auth::id(),
            'package_name'  => $request->package_name, 
            'quantity'      => $request->quantity,
            'size'          => $request->size,
            'notes'         => $request->notes,
            'design_file'   => $path,
            'total_price'   => (int) $cleanPrice,
            'printing_type' => $request->printing_type ?? 'Digital Sablon (DTF)',
            'status'        => 'pending',
        ]);

        // 5. Redirect ke halaman status
        return redirect()->route('user.status')->with('success', 'Pesanan berhasil dibuat!');
    }

    /**
     * Menampilkan Pesanan yang Sedang Berjalan (Status Tracking)
     */
    public function status()
    {
        $activeOrders = Order::where('user_id', Auth::id())
            ->whereNotIn('status', ['sampai', 'batal'])
            ->latest()->get();
        return view('user.status', compact('activeOrders'));
    }

    /**
     * Menampilkan Riwayat Pesanan Selesai
     */
    public function history()
    {
        $completedOrders = Order::where('user_id', Auth::id())
            ->whereIn('status', ['sampai', 'batal'])
            ->latest()->get();
        return view('user.history', compact('completedOrders'));
    }

    /**
     * Membatalkan atau Menghapus Pesanan
     */
    public function destroy($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        // Hanya pesanan pending yang bisa dihapus
        if ($order->status !== 'pending') {
            return redirect()->back()->with('error', 'Pesanan tidak bisa dibatalkan karena sudah diproses.');
        }

        // Hapus file fisik jika itu hasil upload (bukan path URL katalog)
        if ($order->design_file && !filter_var($order->design_file, FILTER_VALIDATE_URL)) {
            if (Storage::disk('public')->exists($order->design_file)) {
                Storage::disk('public')->delete($order->design_file);
            }
        }

        $order->delete();

        return redirect()->route('user.status')->with('success', 'Pesanan berhasil dibatalkan.');
    }
}