<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Kolom lama Anda (dengan proteksi agar tidak error duplicate)
            if (!Schema::hasColumn('orders', 'package_name')) {
                $table->string('package_name')->nullable()->after('user_id');
            }
            
            if (!Schema::hasColumn('orders', 'printing_type')) {
                $table->string('printing_type')->nullable()->after('package_name');
            }
            
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 15, 2)->default(0)->after('quantity');
            }

            // Tambahan kolom WAJIB untuk integrasi Midtrans
            if (!Schema::hasColumn('orders', 'snap_token')) {
                $table->string('snap_token')->nullable()->after('total_price');
            }

            if (!Schema::hasColumn('orders', 'payment_status')) {
                // Status: unpaid, paid, expired, failed
                $table->string('payment_status')->default('unpaid')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Menghapus kolom jika rollback dijalankan
            $table->dropColumn([
                'package_name', 
                'printing_type', 
                'total_price', 
                'snap_token', 
                'payment_status'
            ]);
        });
    }
};