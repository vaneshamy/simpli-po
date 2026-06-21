<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog - Dapur Mama Rafa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 min-h-screen p-4 md:p-8">

    <div class="text-center mb-8">
        <h1 class="text-4xl font-bold text-orange-800 mb-2">Dapur Mama Rafa</h1>
        <p class="text-orange-600">Pilih kue favorit Anda dan rasakan kehangatannya.</p>
    </div>

    <div class="max-w-7xl mx-auto flex flex-col lg:flex-row gap-8">
        
        <div class="lg:w-2/3">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Daftar Menu</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($products as $product)
                <div class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-shadow border border-orange-100 overflow-hidden">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-48 object-cover">
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $product->name }}</h3>
                        <p class="text-sm text-gray-500 mb-4 line-clamp-2">{{ $product->description }}</p>
                        <div class="flex items-center justify-between mt-4">
                            <span class="text-lg font-bold text-orange-600">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <button onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->price }})" class="bg-orange-500 hover:bg-orange-600 text-white text-sm font-semibold py-2 px-4 rounded-full transition-colors">
                                + Keranjang
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="lg:w-1/3">
            <div class="bg-white p-6 rounded-2xl shadow-lg border-t-4 border-orange-400 sticky top-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Keranjang Belanja</h2>
                
                <div id="cart-items" class="mb-4 space-y-3 min-h-[100px] max-h-[300px] overflow-y-auto">
                    <p class="text-gray-400 text-sm text-center italic mt-4">Keranjang masih kosong</p>
                </div>

                <div class="border-t pt-4 mb-6 flex justify-between items-center">
                    <span class="font-bold text-gray-700">Total:</span>
                    <span id="cart-total" class="font-bold text-xl text-orange-600">Rp 0</span>
                </div>

                <form action="{{ route('checkout') }}" method="POST" id="checkout-form">
                    @csrf
                    <input type="hidden" name="cart_data" id="cart-data-input">

                    <div class="mb-3">
                        <input type="text" name="customer_name" placeholder="Nama Lengkap" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500 text-sm">
                    </div>
                    <div class="mb-3">
                        <input type="text" name="whatsapp_number" placeholder="Nomor WhatsApp" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500 text-sm">
                    </div>
                    <div class="mb-4">
                        <textarea name="address" rows="2" placeholder="Alamat Pengiriman" required class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-orange-500 text-sm"></textarea>
                    </div>

                    <button type="submit" id="checkout-button" disabled class="w-full bg-gray-400 text-white font-bold py-3 px-4 rounded transition duration-200 cursor-not-allowed">
                        Pilih Menu Dulu
                    </button>
                </form>
            </div>
        </div>

    </div>

    <script>
        // State (Data Keranjang)
        let cart = [];

        // Fungsi Tambah ke Keranjang
        function addToCart(id, name, price) {
            // Cek apakah barang sudah ada di keranjang
            let existingItem = cart.find(item => item.id === id);
            
            if (existingItem) {
                existingItem.quantity += 1; // Jika ada, tambah jumlahnya
            } else {
                // Jika belum, masukkan data baru
                cart.push({ id: id, name: name, price: price, quantity: 1 });
            }
            
            renderCart(); // Perbarui tampilan
        }

        // Fungsi Ubah Jumlah Barang (+ / -)
        function changeQuantity(id, amount) {
            let item = cart.find(item => item.id === id);
            if (item) {
                item.quantity += amount;
                // Jika jumlah 0 atau kurang, hapus dari keranjang
                if (item.quantity <= 0) {
                    cart = cart.filter(cartItem => cartItem.id !== id);
                }
            }
            renderCart();
        }

        // Fungsi Render (Menggambar ulang keranjang di layar)
        function renderCart() {
            const cartContainer = document.getElementById('cart-items');
            const totalContainer = document.getElementById('cart-total');
            const cartDataInput = document.getElementById('cart-data-input');
            const checkoutButton = document.getElementById('checkout-button');

            cartContainer.innerHTML = ''; // Bersihkan kontainer
            let total = 0;

            if (cart.length === 0) {
                cartContainer.innerHTML = '<p class="text-gray-400 text-sm text-center italic mt-4">Keranjang masih kosong</p>';
                checkoutButton.disabled = true;
                checkoutButton.classList.replace('bg-orange-500', 'bg-gray-400');
                checkoutButton.classList.replace('hover:bg-orange-600', 'cursor-not-allowed');
                checkoutButton.innerText = 'Pilih Menu Dulu';
            } else {
                checkoutButton.disabled = false;
                checkoutButton.classList.replace('bg-gray-400', 'bg-orange-500');
                checkoutButton.classList.replace('cursor-not-allowed', 'hover:bg-orange-600');
                checkoutButton.innerText = 'Proses Pembayaran';

                cart.forEach(item => {
                    let subtotal = item.price * item.quantity;
                    total += subtotal;

                    // Buat elemen HTML untuk setiap item di keranjang
                    cartContainer.innerHTML += `
                        <div class="flex justify-between items-center bg-gray-50 p-2 rounded">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-800">${item.name}</p>
                                <p class="text-xs text-gray-500">Rp ${item.price.toLocaleString('id-ID')} x ${item.quantity}</p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button" onclick="changeQuantity(${item.id}, -1)" class="w-6 h-6 flex items-center justify-center bg-red-100 text-red-600 rounded hover:bg-red-200">-</button>
                                <span class="text-sm font-bold">${item.quantity}</span>
                                <button type="button" onclick="changeQuantity(${item.id}, 1)" class="w-6 h-6 flex items-center justify-center bg-green-100 text-green-600 rounded hover:bg-green-200">+</button>
                            </div>
                        </div>
                    `;
                });
            }

            // Perbarui teks total harga
            totalContainer.innerText = 'Rp ' + total.toLocaleString('id-ID');
            
            // Konversi array cart menjadi JSON string dan masukkan ke input tersembunyi
            cartDataInput.value = JSON.stringify(cart);
        }
    </script>
</body>
</html>