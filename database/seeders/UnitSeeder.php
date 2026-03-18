<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->insert([
            ['name' => 'Unidad'],
            ['name' => 'Docena'],
            ['name' => 'Centena'],
            ['name' => 'Caja'],
            ['name' => 'Paquete'],
            ['name' => 'Kilogramo'],
            ['name' => 'Litro'],
            ['name' => 'Metro'],
            ['name' => 'Galón'],
            ['name' => 'Bolsa']
        ]);
    }
}
