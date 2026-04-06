<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = Brand::all();
        $units = Unit::all();
        $categories = Category::all();

        Product::factory(66)->create()->each(function ($product) use ($brands, $units, $categories) {
            $product->brand_id = $brands->random()->id;
            $product->unit_id = $units->random()->id;
            $product->category_id = $categories->random()->id;
            $product->save();
        });
    }
}
