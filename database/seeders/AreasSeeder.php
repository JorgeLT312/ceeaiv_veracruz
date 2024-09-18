<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["nombre" => "Recepción"],
            ["nombre" => "Primer Contacto"],
            ["nombre" => "Registro Estatal de Víctimas"],
            ["nombre" => "Juridico"],
            ["nombre" => "Administración"],
            ["nombre" => "Foraneos"],
            ["nombre" => "Otros"],
        ];

        foreach($datos as $dato)
            Area::create($dato);
    }
}
