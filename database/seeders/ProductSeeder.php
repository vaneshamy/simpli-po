<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Hampers Signature',
            'description' => 'Paket hampers eksklusif dengan 3 toples kue kering premium.',
            'price' => 150000,
            'stock' => 50,
            'image' => 'https://images.unsplash.com/photo-1542826438-bd32f43d626f?q=80&w=500&auto=format&fit=crop'
        ]);

        Product::create([
            'name' => 'Fudgy Brownies',
            'description' => 'Brownies panggang dengan tekstur fudgy dan topping choco chips melimpah.',
            'price' => 65000,
            'stock' => 20,
            'image' => 'https://images.unsplash.com/photo-1606313564200-e75d5e30476c?q=80&w=500&auto=format&fit=crop'
        ]);

        Product::create([
            'name' => 'Nastar Klasik',
            'description' => 'Nastar lembut lumer di mulut dengan selai nanas asli homemade.',
            'price' => 85000,
            'stock' => 30,
            'image' => 'https://images.unsplash.com/photo-1558961363-fa8fdf82db35?q=80&w=500&auto=format&fit=crop'
        ]);
    }
}