<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan di halaman status atau riwayat.
     */
    public function index()
    {
        $userId = Auth::id();
        $routeName = Route::currentRouteName();

        // Ambil semua data pesanan milik user
        $allOrders = Order::where('user_id', $userId)->latest()->get();

        // 1. Logika untuk Halaman Status Pesanan (user.status)
        if ($routeName === 'user.status') {
            $activeOrders = $allOrders->whereIn('status', ['pending', 'proses', 'dikirim']);
            
            return view('user.status', [
                'activeOrders' => $activeOrders,
                'orders' => $allOrders, 
                'title' => 'Status Pesanan Aktif'
            ]);
        }

        // 2. Logika untuk Halaman Riwayat (user.history)
        $completedOrders = $allOrders->whereIn('status', ['selesai', 'batal']);
        
        return view('user.history', [
            'orders' => $allOrders,
            'completedOrders' => $completedOrders,
            'activeOrders' => $allOrders,
            'title' => 'Riwayat Pesanan Anda'
        ]);
    }

    /**
     * Menampilkan form pembuatan pesanan.
     */
    public function create()
    {
        return view('user.order_create'); 
    }

    /**
     * Tahap 1: Validasi dan Ambil Snap Token (BELUM SIMPAN KE DB)
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'design_file'   => 'nullable|image|mimes:png,jpg,jpeg|max:10240',
            'catalog_image' => 'nullable|string', 
            'size'          => 'required|string',
            'quantity'      => 'required|integer|min:1',
            'package_name'  => 'required|string',
            'total_price'   => 'required|numeric',
        ], [
            'package_name.required' => 'Kategori paket/produk wajib dipilih.',
            'quantity.min'          => 'Jumlah minimal pemesanan adalah 1.',
            'size.required'         => 'Ukuran wajib dipilih.',
            'total_price.required'  => 'Harga tidak valid.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        // 2. Logika Penentuan Path Gambar
        $path = null;
        if ($request->hasFile('design_file')) {
            try {
                // Di Vercel, pastikan filesystem di config/filesystems.php tidak memaksa 'local' jika gagal
                $path = $request->file('design_file')->store('designs', 'public');
            } catch (\Exception $e) {
                Log::warning("Storage Error (Vercel/Permission): " . $e->getMessage());
                // Fallback: Tetap buat path string agar database tidak null, 
                // meskipun file fisiknya mungkin tidak tersimpan di local Vercel (disarankan S3/Cloudinary)
                $file = $request->file('design_file');
                $path = 'designs/' . time() . '_' . $file->getClientOriginalName();
            }
        } elseif ($request->filled('catalog_image')) {
            $path = $request->catalog_image;
        }

        // 3. Konfigurasi Midtrans
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');

        // Order ID unik untuk Midtrans
        $temp_order_id = 'SABLON-' . time() . '-' . Auth::id();

        $params = [
            'transaction_details' => [
                'order_id' => $temp_order_id,
                'gross_amount' => (int) $request->total_price,
            ],
            'customer_details' => [
                'first_name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ],
            'item_details' => [[
                'id' => substr($request->package_name, 0, 20),
                'price' => (int) $request->total_price,
                'quantity' => 1,
                'name' => "Pesanan " . ucfirst($request->package_name),
            ]]
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            return response()->json([
                'status' => 'success',
                'snap_token' => $snapToken,
                'design_file' => $path, // Dikirim balik ke JS untuk disimpan di finalize
                'message' => 'Token berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            Log::error("Midtrans Snap Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal terhubung ke Midtrans: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tahap 2: Simpan ke Database
     */
    public function finalize(Request $request)
    {
        try {
            // Gunakan path file yang dikirim dari tahap store
            $designPath = $request->design_file_path ?? $request->design_file;

            $order = Order::create([
                'user_id'       => Auth::id(),
                'design_file'   => $designPath, 
                'size'          => $request->size,
                'quantity'      => $request->quantity,
                'package_name'  => $request->package_name, 
                'printing_type' => $request->printing_type ?? 'Digital Sablon (DTF)',
                'notes'         => $request->notes,
                'total_price'   => $request->total_price,
                'status'        => 'pending',
                'payment_status'=> 'unpaid',
                'snap_token'    => $request->snap_token,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Pesanan berhasil dicatat',
                'redirect_url' => route('user.status')
            ]);
        } catch (\Exception $e) {
            Log::error("Finalize Order Error: " . $e->getMessage());
            return response()->json([
                'status' => 'error', 
                'message' => 'Gagal menyimpan ke database: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Membatalkan pesanan (Hapus).
     */
    public function destroy($id)
    {
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        
        if ($order->status === 'pending') {
            if ($order->design_file && !filter_var($order->design_file, FILTER_VALIDATE_URL)) {
                try {
                    if (Storage::disk('public')->exists($order->design_file)) {
                        Storage::disk('public')->delete($order->design_file);
                    }
                } catch (\Exception $e) {
                    Log::error("Gagal hapus file: " . $e->getMessage());
                }
            }
            
            $order->delete();
            return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->back()->with('error', 'Pesanan tidak dapat dibatalkan karena sudah diproses.');
    }

    /**
     * Update Status Pesanan.
     */
    public function update(Request $request, $id, $status)
    {
        $order = Order::findOrFail($id);

        if ($status === 'batal' && $order->status !== 'pending') {
            return redirect()->back()->with('error', 'Gagal! Pesanan sudah masuk tahap produksi.');
        }

        $order->status = $status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Menampilkan Detail Pesanan.
     */
    public function indexDetailed(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('user.order-detail', compact('order'));
    }
}