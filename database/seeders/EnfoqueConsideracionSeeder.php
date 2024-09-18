<?php

namespace Database\Seeders;

use App\Models\EnfoqueConsideracion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoqueConsideracionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "SIN DATO"],
            ["clave" => "2", "nombre" => "RELIGION O CREENCIAS"],
            ["clave" => "3", "nombre" => "PREFERENCIA U ORIENTACION SEXUAL"],
            ["clave" => "4", "nombre" => "IDENTIDAD O EXPRESION DE GENERO"],
            ["clave" => "5", "nombre" => "SEXO"],
            ["clave" => "6", "nombre" => "RAZA"],
            ["clave" => "7", "nombre" => "OTRO"],
        ];

        foreach($datos as $dato)
            EnfoqueConsideracion::create($dato);
    }
}
