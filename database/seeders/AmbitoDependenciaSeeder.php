<?php

namespace Database\Seeders;

use App\Models\AmbitoDependencia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AmbitoDependenciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "MUNICIPAL"],
            ["clave" => "2", "nombre" => "ESTATAL"],
            ["clave" => "3", "nombre" => "FEDERAL"],
            ["clave" => "4", "nombre" => "NACIONAL"],
            ["clave" => "5", "nombre" => "INTERNACIONAL"],
        ];

        foreach($datos as $dato)
            AmbitoDependencia::create($dato);
    }

}
