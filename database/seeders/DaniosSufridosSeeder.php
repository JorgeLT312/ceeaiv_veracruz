<?php

namespace Database\Seeders;

use App\Models\DaniosSufridos;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaniosSufridosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "SIN DATO"],
            ["clave" => "2", "nombre" => "FISICO"],
            ["clave" => "3", "nombre" => "PSICOLOGICO"],
            ["clave" => "4", "nombre" => "PATRIMONIAL"],
            ["clave" => "5", "nombre" => "SEXUAL"],
            ["clave" => "6", "nombre" => "OTRO"]
        ];

        foreach($datos as $dato)
            DaniosSufridos::create($dato);
    }
}
