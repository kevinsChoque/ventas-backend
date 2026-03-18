<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'name' => 'Arroz',
                'price' => 3.50,
                'stock' => 100,
                'unit_id' => 6, // Kilogramo
                'brand' => 'Costeño',
                'category' => 'Alimentos',
            ],
            [
                'name' => 'Gaseosa',
                'price' => 2.50,
                'stock' => 50,
                'unit_id' => 7, // Litro
                'brand' => 'Coca Cola',
                'category' => 'Bebidas',
            ],
            [
                'name' => 'Laptop',
                'price' => 2500,
                'stock' => 10,
                'unit_id' => null, // 🔥 sin unidad
                'brand' => 'HP',
                'category' => 'Tecnología',
            ],
            [
                'name' => 'Huevos',
                'price' => 12,
                'stock' => 30,
                'unit_id' => 2, // Docena
                'brand' => 'Granja',
                'category' => 'Alimentos',
            ],
        ]);
    }
}
