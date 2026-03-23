<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::create([
            'document_type' => 'DNI',
            'document_number' => '12345678',
            'name' => 'Juan Perez',
            'address' => 'Calle Falsa 123',
            'phone' => '555-1234'
        ]);

        Client::create([
            'document_type' => 'RUC',
            'document_number' => '20123456789',
            'name' => 'Empresa XYZ S.A.',
            'address' => 'Avenida Principal 456',
            'phone' => '555-5678'
        ]);
    }
}
