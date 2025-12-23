<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // 1. Tambahkan kolom package_name setelah user_id
            if (!Schema::hasColumn('orders', 'package_name')) {
                $table->string('package_name')->nullable()->after('user_id');
            }
            
            // 2. Tambahkan kolom printing_type setelah package_name
            if (!Schema::hasColumn('orders', 'printing_type')) {
                $table->string('printing_type')->nullable()->after('package_name');
            }
            
            // 3. Tambahkan kolom total_price setelah quantity
            if (!Schema::hasColumn('orders', 'total_price')) {
                $table->decimal('total_price', 15, 2)->default(0)->after('quantity');
            }

            // 4. Hapus kolom 'package' yang lama jika masih ada
            if (Schema::hasColumn('orders', 'package')) {
                $table->dropColumn('package');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['package_name', 'printing_type', 'total_price']);
        });
    }
};