<?php

namespace Database\Seeders;

use App\Models\EstadoCivil;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EstadoCivilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["nombre" => "Soltero/a"],
            ["nombre" => "Casado/a"],
            ["nombre" => "Divorsiado/a"],
            ["nombre" => "Viudo/a"],
            ["nombre" => "Unión libre"],
            ["nombre" => "Concubinato"],
            ["nombre" => "Separado/a"],
        ];


        foreach($datos as $dato)
            EstadoCivil::create($dato);
    }
}
