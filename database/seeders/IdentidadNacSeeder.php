<?php

namespace Database\Seeders;

use App\Models\IdentidadNac;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IdentidadNacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "MÉXICO"],
            ["clave" => "2", "nombre" => "EXTRANJERO"],
            ["clave" => "3", "nombre" => "SIN DATO"],
        ];


        foreach($datos as $dato)
            IdentidadNac::create($dato);
    }
}
