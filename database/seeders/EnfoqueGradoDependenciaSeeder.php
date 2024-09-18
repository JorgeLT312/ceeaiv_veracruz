<?php

namespace Database\Seeders;

use App\Models\EnfoqueGradoDependencia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoqueGradoDependenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "SIN DATO"],
            ["clave" => "2", "nombre" => "SIN GRADO DE DEPENCIA"],
            ["clave" => "3", "nombre" => "MODERADA"],
            ["clave" => "4", "nombre" => "SEVERA"],
            ["clave" => "5", "nombre" => "GRAN DEPENDENCIA"],
        ];

        foreach($datos as $dato)
            EnfoqueGradoDependencia::create($dato);
    }
}
