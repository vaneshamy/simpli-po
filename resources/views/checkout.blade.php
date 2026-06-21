<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Pesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script type="text/javascript"
            src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>
<body class="bg-orange-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md text-center border-t-4 border-orange-400">
        <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Pembayaran</h2>
        
        <div class="text-left mb-6 bg-orange-100 p-4 rounded text-orange-900 text-sm">
            <p><strong>Order ID:</strong> {{ $order->id }}</p>
            <p><strong>Nama:</strong> {{ $order->customer_name }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
        </div>

        <button id="pay-button" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded transition duration-200">
            Bayar Sekarang
        </button>
    </div>

    <script type="text/javascript">
        document.getElementById('pay-button').onclick = function () {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result){
                    alert("Pembayaran berhasil!"); 
                    console.log(result);
                },
                onPending: function(result){
                    alert("Menunggu pembayaran Anda!"); 
                    console.log(result);
                },
                onError: function(result){
                    alert("Pembayaran gagal!"); 
                    console.log(result);
                },
                onClose: function(){
                    alert('Anda menutup pop-up tanpa menyelesaikan pembayaran');
                }
            });
        };
    </script>

</body>
</html>