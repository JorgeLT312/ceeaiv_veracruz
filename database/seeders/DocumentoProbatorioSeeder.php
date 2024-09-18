<?php

namespace Database\Seeders;

use App\Models\DocumentoProbatorio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentoProbatorioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "CURP"],
            ["clave" => "2", "nombre" => "DOCUMENTO PROBATORIO NO ESPECIFICADO"],
            ["clave" => "3", "nombre" => "SIN DATO"],
            ["clave" => "4", "nombre" => "COPIA CERTIFICADA DE ACTA DE NACIMIENTO"],
            ["clave" => "5", "nombre" => "DOCUMENTO MIGRATORIO FM2"],
            ["clave" => "6", "nombre" => "DOCUMENTO MIGRATORIO FM3"],
            ["clave" => "7", "nombre" => "CARTA DE NATURALIZACION"],
            ["clave" => "8", "nombre" => "CERTIFICADO DE NACIONALIDAD MEXICANA"],
            ["clave" => "9", "nombre" => "PASAPORTE"],
            ["clave" => "10", "nombre" => "MATRICULA CONSULAR"],
        ];


        foreach($datos as $dato)
            DocumentoProbatorio::create($dato);
    }

}
