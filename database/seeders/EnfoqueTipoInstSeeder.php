<?php

namespace Database\Seeders;

use App\Models\EnfoqueTipoIns;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoqueTipoInstSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "No pertenece a ninguna Institución"],
            ["clave" => "2", "nombre" => "Sin dato"],
            ["clave" => "3", "nombre" => "Sin especificar"],
            ["clave" => "4", "nombre" => "Organización social"],
            ["clave" => "5", "nombre" => "Institución de asistencia privada"],
            ["clave" => "6", "nombre" => "Institución internacional"],
            ["clave" => "7", "nombre" => "Tipo de institución no definida"],
        ];
        

        foreach($datos as $dato)
            EnfoqueTipoIns::create($dato);
    }
}
