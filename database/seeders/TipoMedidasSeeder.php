<?php

namespace Database\Seeders;

use App\Models\TipoMedida;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoMedidasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "AYUDA INMEDIATA"],
            ["clave" => "2", "nombre" => "MEDIDAS DE ASISTENCIA"],
            ["clave" => "3", "nombre" => "MEDIDAS DE REPARACION INTEGRAL"],
            ["clave" => "4", "nombre" => "MEDIDAS DE ATENCION"],
        ];
  

        foreach($datos as $dato)
            TipoMedida::create($dato);
    }
}
