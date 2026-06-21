<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pre-Order Hampers Dapur Mama Rafa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md border-t-4 border-orange-400">
        <h2 class="text-2xl font-bold text-gray-800 mb-2 text-center">Pre-Order Hampers</h2>
        <p class="text-gray-500 text-sm text-center mb-6">Isi data pengiriman Anda di bawah ini.</p>

        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama Lengkap</label>
                <input type="text" name="customer_name" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor WhatsApp</label>
                <input type="text" name="whatsapp_number" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500">
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Pengiriman</label>
                <textarea name="address" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500"></textarea>
            </div>

            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-3 px-4 rounded transition duration-200">
                Pesan Sekarang (Rp 150.000)
            </button>
        </form>
    </div>

</body>
</html>