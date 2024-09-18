<?php

namespace Database\Seeders;

use App\Models\EnfoqueTipoDiscapacidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoqueTipoDiscapacidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "SIN DATO"],
            ["clave" => "2", "nombre" => "FISICA"],
            ["clave" => "3", "nombre" => "MENTAL"],
            ["clave" => "4", "nombre" => "INTELECTUAL"],
            ["clave" => "5", "nombre" => "VISUAL"],
            ["clave" => "6", "nombre" => "AUDITIVA"],
            ["clave" => "7", "nombre" => "NO CLASIFICADO"],
        ];


        foreach($datos as $dato)
            EnfoqueTipoDiscapacidad::create($dato);
    }
}
