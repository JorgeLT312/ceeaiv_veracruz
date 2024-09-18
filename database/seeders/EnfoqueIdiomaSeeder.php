<?php

namespace Database\Seeders;

use App\Models\EnfoqueIdioma;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoqueIdiomaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave"=> "1","nombre" => "SIN DATO"],
            ["clave"=> "2","nombre" => "ALEMAN"],
            ["clave"=> "3","nombre" => "HUCHE"],
            ["clave"=> "4","nombre" => "HUICHOL"],
            ["clave"=> "5","nombre" => "INGLES"],
            ["clave"=> "6","nombre" => "ITALIANO"],
            ["clave"=> "7","nombre" => "JAPONES"],
            ["clave"=> "8","nombre" => "KACCHIQUEL"],
            ["clave"=> "9","nombre" => "KAKCHIKEL"],
            ["clave"=> "10","nombre" => "MAM"],
            ["clave"=> "11","nombre" => "MAYA"],
            ["clave"=> "12","nombre" => "MAZAHUA"],
            ["clave"=> "13","nombre" => "ARABE"],
            ["clave"=> "14","nombre" => "MIXTECO"],
            ["clave"=> "15","nombre" => "NAHUATL"],
            ["clave"=> "16","nombre" => "NAHUATL Y TEENEK BASICO"],
            ["clave"=> "17","nombre" => "ÑAÑHU"],
            ["clave"=> "18","nombre" => "PORTUGUES"],
            ["clave"=> "19","nombre" => "PUREPECHA"],
            ["clave"=> "20","nombre" => "QUECHUA"],
            ["clave"=> "21","nombre" => "RUSO"],
            ["clave"=> "22","nombre" => "TEENEK"],
            ["clave"=> "23","nombre" => "TEPEHUAN"],
            ["clave"=> "24","nombre" => "BENGALI"],
            ["clave"=> "25","nombre" => "TLAPANECO"],
            ["clave"=> "26","nombre" => "TOTONICAPANA"],
            ["clave"=> "27","nombre" => "TSELTAL MAYA"],
            ["clave"=> "28","nombre" => "TSETSIL"],
            ["clave"=> "29","nombre" => "TZATZI"],
            ["clave"=> "30","nombre" => "TZELTAL"],
            ["clave"=> "31","nombre" => "TZOTZIL"],
            ["clave"=> "32","nombre" => "ZAPOTECO"],
            ["clave"=> "33","nombre" => "CALO"],
            ["clave"=> "34","nombre" => "CANJOBAL"],
            ["clave"=> "35","nombre" => "CHINO"],
            ["clave"=> "36","nombre" => "FRANCES"],
            ["clave"=> "37","nombre" => "GARIFUNA"],
            ["clave"=> "38","nombre" => "HINDI"],
            ["clave"=> "39","nombre" => "TARASCO"],
            ["clave"=> "40","nombre" => "QUICHE"],
            ["clave"=> "41","nombre" => "MAZATECO"],
            ["clave"=> "42","nombre" => "OTOMI"],

        ];


        foreach($datos as $dato)
            EnfoqueIdioma::create($dato);
    }
}
