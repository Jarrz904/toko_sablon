<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    // Pastikan nama kolom di sini SESUAI dengan yang ada di MySQL Anda
protected $fillable = [
    'user_id', 
    'category', 
    'package_name', 
    'printing_type', 
    'size', 
    'quantity', 
    'total_price', 
    'snap_token',     // Penting untuk Midtrans
    'payment_status',  // Penting untuk Midtrans
    'status', 
    'notes', 
    'design_file'
];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}