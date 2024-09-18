<?php

namespace Database\Seeders;

use App\Models\TipoVictima;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoVictimaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "0", "nombre" => "DIRECTA"],
            ["clave" => "1", "nombre" => "INDIRECTA"],
            ["clave" => "1", "nombre" => "POTENCIAL"],
        ];


        foreach($datos as $dato)
            TipoVictima::create($dato);
    }
}
