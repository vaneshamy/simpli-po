<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasUuids; // Mengotomatiskan pembuatan UUID

    // Mengizinkan kolom-kolom ini diisi data dari form
    protected $fillable = [
        'customer_name',
        'whatsapp_number',
        'address',
        'total_price',
        'status',
        'snap_token',
    ];
}