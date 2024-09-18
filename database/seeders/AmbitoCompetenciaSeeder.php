<?php

namespace Database\Seeders;

use App\Models\AmbitoCompetencia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmbitoCompetenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "DELITO DE COMPETENCIA ESTATAL"],
            ["clave" => "2", "nombre" => "DELITO DE COMPETENCIA FEDERAL"],
            ["clave" => "3", "nombre" => "DELITO DE COMPETENCIA MILITAR"],
            ["clave" => "4", "nombre" => "DELITO DE COMPETENCIA ESTATAL Y FEDERAL"],
            ["clave" => "5", "nombre" => "VIOLACION DE DERECHOS HUMANOS FEDERAL"],
            ["clave" => "6", "nombre" => "VIOLACION DE DERECHOS HUMANOS ESTATAL"],
            ["clave" => "7", "nombre" => "VIOLACION DE DERECHOS HUMANOS INTERNACIONAL"],
        ];


        foreach($datos as $dato)
            AmbitoCompetencia::create($dato);
    }
}
