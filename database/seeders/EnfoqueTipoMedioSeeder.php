<?php

namespace Database\Seeders;

use App\Models\EnfoqueTipoMedio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoqueTipoMedioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "AUDIOVISUAL"],
            ["clave" => "2", "nombre" => "ELECTRONICO"],
            ["clave" => "3", "nombre" => "ESCRITO"],
            ["clave" => "4", "nombre" => "MEDIO IMPRESO"],
            ["clave" => "5", "nombre" => "OTRO"],
            ["clave" => "6", "nombre" => "SIN DATO"],
            ["clave" => "7", "nombre" => "RADIO"],
            ["clave" => "7", "nombre" => "TELEVISION"],
        ];
        

        foreach($datos as $dato)
            EnfoqueTipoMedio::create($dato);
    }
}
