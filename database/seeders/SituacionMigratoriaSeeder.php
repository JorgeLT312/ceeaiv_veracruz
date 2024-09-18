<?php

namespace Database\Seeders;

use App\Models\SituacionMigratoria;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SituacionMigratoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave" => "1", "nombre" => "MIGRANTE"],
            ["clave" => "2", "nombre" => "NO MIGRANTE"],
            ["clave" => "3", "nombre" => "INMIGRANTE ILEGAL"],
            ["clave" => "4", "nombre" => "NACIONALIZADO"],
            ["clave" => "5", "nombre" => "INMIGRADO"],
            ["clave" => "6", "nombre" => "NO ESPECIFICADO"],
            ["clave" => "7", "nombre" => "NO APLICA"],
            ["clave" => "8", "nombre" => "NO INMIGRANTE"],
            ["clave" => "9", "nombre" => "INMIGRANTE"],
            ["clave" => "10", "nombre" => "CALIDAD MIGRATORIA NO ESPECIFICADA"],
            ["clave" => "11", "nombre" => "SIN DATO"],
            ["clave" => "12", "nombre" => "SIN ESPECIFICAR"],
        ];


        foreach($datos as $dato)
            SituacionMigratoria::create($dato);
    }
}
