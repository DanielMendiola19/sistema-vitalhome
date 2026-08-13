<?php

namespace Database\Seeders;

use App\Models\Kardex;
use Illuminate\Database\Seeder;

class KardexSeeder extends Seeder
{
    public function run(): void
    {
        Kardex::updateOrCreate(
            ['medicamento_id' => 1],
            [
                'stock_actual' => 90,
                'stock_minimo' => 20,
                'stock_maximo' => 200,
            ]
        );

        Kardex::updateOrCreate(
            ['medicamento_id' => 2],
            [
                'stock_actual' => 75,
                'stock_minimo' => 20,
                'stock_maximo' => 150,
            ]
        );

        Kardex::updateOrCreate(
            ['medicamento_id' => 3],
            [
                'stock_actual' => 50,
                'stock_minimo' => 10,
                'stock_maximo' => 100,
            ]
        );

        Kardex::updateOrCreate(
            ['medicamento_id' => 4],
            [
                'stock_actual' => 60,
                'stock_minimo' => 15,
                'stock_maximo' => 120,
            ]
        );

        Kardex::updateOrCreate(
            ['medicamento_id' => 5],
            [
                'stock_actual' => 40,
                'stock_minimo' => 10,
                'stock_maximo' => 80,
            ]
        );
    }
}
