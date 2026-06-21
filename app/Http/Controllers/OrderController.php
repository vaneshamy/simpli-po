<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class OrderController extends Controller
{
    public function __construct()
    {
        // Setup konfigurasi Midtrans
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function checkout(Request $request)
    {
        // 1. Tangkap data dari form (contoh sederhana)
        $request->validate([
            'customer_name' => 'required|string',
            'whatsapp_number' => 'required|string',
            'address' => 'required|string',
        ]);

        // 2. Buat ID Pesanan (harus unik untuk Midtrans)
        $orderId = (string) Str::uuid();
        $totalPrice = 150000; // Harga produk statis untuk contoh

        // 3. Simpan data pesanan ke Supabase
        $order = Order::create([
            'id' => $orderId,
            'customer_name' => $request->customer_name,
            'whatsapp_number' => $request->whatsapp_number,
            'address' => $request->address,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // 4. Siapkan parameter untuk Midtrans Snap
        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'phone' => $order->whatsapp_number,
            ],
        ];

        try {
            // 5. Dapatkan Token Pembayaran dari Midtrans
            $snapToken = Snap::getSnapToken($params);

            // 6. Simpan token ke database agar bisa dipanggil lagi jika perlu
            $order->update(['snap_token' => $snapToken]);

            // 7. Lempar data ke halaman view Blade untuk menampilkan tombol bayar
            return view('checkout', compact('order', 'snapToken'));

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        // 1. Ambil Server Key untuk dicocokkan dengan Signature Key dari Midtrans
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        // 2. Buat hash untuk verifikasi keamanan (Rumus standar Midtrans)
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);
        
        // 3. Pastikan request ini benar-benar datang dari Midtrans
        if ($hashed == $request->signature_key) {
            
            // Cari pesanan di database berdasarkan UUID
            $order = Order::find($request->order_id);
            
            if ($order) {
                // 4. Update status berdasarkan status transaksi dari Midtrans
                if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                    $order->update(['status' => 'settlement']); // Lunas
                } elseif (in_array($request->transaction_status, ['expire', 'cancel', 'deny'])) {
                    $order->update(['status' => 'failed']); // Gagal/Batal
                }
            }
        }
        
        // 5. Beri respons 200 OK ke Midtrans agar mereka tahu notifikasi berhasil diterima
        return response()->json(['message' => 'Notification successfully processed']);
    }
}