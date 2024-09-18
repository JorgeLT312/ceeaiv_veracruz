<?php

namespace Database\Seeders;

use App\Models\DocProbatorioIdentidad;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocProbatorioIdentidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "CARTILLA DEL SERVICIO MILITAR NACIONAL"],
            ["clave" => "2", "nombre" => "CREDENCIAL EMITIDA POR SERVIDOR PUBLICO EN FUNCIONES"],
            ["clave" => "3", "nombre" => "TESTIMONIAL DE LA AUTORIDAD TRADICIONAL INDIGENA Y DE LA AUTORIDAD MUNICIPAL O DE LA DELEGACION DEL LUGAR"],
            ["clave" => "4", "nombre" => "IDENTIFICACION NO PRESENTADA"],
            ["clave" => "5", "nombre" => "PASAPORTE EXPEDIDO POR LA SECRETARIA DE RELACIONES EXTERIORES"],
            ["clave" => "6", "nombre" => "CEDULA PROFESIONAL"],
            ["clave" => "7", "nombre" => "IDENTIFICACION NO PRESENTADA"],
            ["clave" => "8", "nombre" => "CERTIFICADO DE ESTUDIOS CON FOTOGRAFIA"],
            ["clave" => "9", "nombre" => "CREDENCIAL OFICIAL DEL INSTITUTO MEXICANO DEL SEGURO SOCIAL"],
            ["clave" => "10", "nombre" => "CREDENCIAL OFICIAL DEL INTITUTO DE SEGUIRDAD Y SERVICIOS SOCIALES DE LOS TRABAJADORES DEL ESTADO"],
            ["clave" => "11", "nombre" => "CONSTANCIA DE RESIDENCIA EMITIDA POR LA AUTORIDAD DEL LUGAR DONDE RESIDE EL INTERESADO"],
            ["clave" => "12", "nombre" => "CREDENCIAL DE ELECTOR"],
            ["clave" => "13", "nombre" => "LICENCIA DE MANEJO"],
            ["clave" => "14", "nombre" => "TARJETA DE RESIDENCIA TEMPORAL"],
            ["clave" => "15", "nombre" => "TARJETA DE RESIDENCIA PERMANENTE"],
        ];


        foreach($datos as $dato)
            DocProbatorioIdentidad::create($dato);
    }
}
