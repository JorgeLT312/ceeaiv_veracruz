<?php

namespace Database\Seeders;

use App\Models\TipoSolicitante;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoSolicitanteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "0", "nombre" => "VICTIMA"],
            ["clave" => "1", "nombre" => "FAMILIAR O PERSONA DE CONFIANZA"],
            ["clave" => "2", "nombre" => "REPRESENTANTE"],
            ["clave" => "3", "nombre" => "AUTORIDAD"]
        ];

        foreach($datos as $dato)
            TipoSolicitante::create($dato);
    }
}
