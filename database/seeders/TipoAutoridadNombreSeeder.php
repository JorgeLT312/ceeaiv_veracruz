<?php

namespace Database\Seeders;

use App\Models\TipoAutoridadNombre;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoAutoridadNombreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "MINISTERIO PUBLICO (FRACCION VIII)"],
            ["clave" => "2", "nombre" => "JUEZ (FRACCION I, II, III) -POR OFICIO O SENTENCIA"],
            ["clave" => "3", "nombre" => "ORGANISMO DE DEFENSA DE DERECHOS HUMANOS"],
            ["clave" => "4", "nombre" => "CEAV(FRACCION VII Y 88 BIS)"],
            ["clave" => "5", "nombre" => "OTRA AUTORIDAD (FRACCION VI)"],
            ["clave" => "6", "nombre" => "PARENTESCO"],
        ];

        foreach($datos as $dato)
            TipoAutoridadNombre::create($dato);
    }
}
