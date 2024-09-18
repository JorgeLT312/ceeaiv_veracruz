<?php

namespace Database\Seeders;

use App\Models\EnfoqueViolenciaMujer;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoqueViolenciaMujerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "SIN DATO"],
            ["clave" => "2", "nombre" => "PSICOLOGICA"],
            ["clave" => "3", "nombre" => "FISICA"],
            ["clave" => "4", "nombre" => "ECONOMICA"],
            ["clave" => "5", "nombre" => "PATRIMONIAL"],
            ["clave" => "6", "nombre" => "SEXUAL"],
            ["clave" => "7", "nombre" => "OBSTRETICA"],
            ["clave" => "7", "nombre" => "FEMINICIDA"],
            ["clave" => "7", "nombre" => "OTRO"],
        ];

        foreach($datos as $dato)
            EnfoqueViolenciaMujer::create($dato);
    }
}
