<?php

namespace Database\Seeders;

use App\Models\Sexo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SexoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "0", "nombre" => "HOMBRE"],
            ["clave" => "1", "nombre" => "MUJER"],
        ];

        foreach($datos as $dato)
            Sexo::create($dato);
    }
}
