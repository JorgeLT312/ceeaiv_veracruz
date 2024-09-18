<?php

namespace Database\Seeders;

use App\Models\TipoTelefono;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoTelefonoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $datos = [
            ["clave" => "0", "nombre" => "CASA"],
            ["clave" => "1", "nombre" => "CELULAR"],
            ["clave" => "1", "nombre" => "TRABAJO"],
            ["clave" => "3", "nombre" => "OTRO"],
        ];


        foreach($datos as $dato)
            TipoTelefono::create($dato);
    }
}
