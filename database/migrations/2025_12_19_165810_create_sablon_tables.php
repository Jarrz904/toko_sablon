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
        // Tabel Kategori (Kaos, Hoodie, Topi)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Tabel Produk (Bahan kain)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->string('image')->nullable();
            $table->timestamps();
        });

        // Tabel Pesanan Kustom
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('package'); // TAMBAHAN: Untuk menyimpan tipe paket (satuan/vendor/brand)
            $table->string('design_file'); // Path file desain pelanggan
            $table->string('size'); // S, M, L, XL
            $table->integer('quantity');
            $table->decimal('total_price', 15, 2)->default(0); // TAMBAHAN: Untuk menyimpan total harga
            $table->enum('status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
    }
};