<?php

namespace Database\Seeders;

use App\Models\Parentesco;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ParentescoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave"=> "1","nombre" => "SIN DATO"],
            ["clave"=> "2","nombre" => "ABUELA"],
            ["clave"=> "3","nombre" => "AMIGO"],
            ["clave"=> "4","nombre" => "BISNIETA"],
            ["clave"=> "5","nombre" => "BISNIETO"],
            ["clave"=> "6","nombre" => "CONCUBINA"],
            ["clave"=> "7","nombre" => "CONCUBINO"],
            ["clave"=> "8","nombre" => "CONYUGE"],
            ["clave"=> "9","nombre" => "CUÑADA"],
            ["clave"=> "10","nombre" => "CUÑADO"],
            ["clave"=> "11","nombre" => "DEPENDIENTE"],
            ["clave"=> "12","nombre" => "ESPOSA"],
            ["clave"=> "13","nombre" => "ABUELA MATERNA"],
            ["clave"=> "14","nombre" => "ESPOSO"],
            ["clave"=> "15","nombre" => "EX CONCUBINA"],
            ["clave"=> "16","nombre" => "EX ESPOSA"],
            ["clave"=> "17","nombre" => "FAMILIA POLITICA"],
            ["clave"=> "18","nombre" => "HERMANA"],
            ["clave"=> "19","nombre" => "HERMANASTRA"],
            ["clave"=> "20","nombre" => "HERMANASTRO"],
            ["clave"=> "21","nombre" => "HERMANO"],
            ["clave"=> "22","nombre" => "HIJA"],
            ["clave"=> "23","nombre" => "HIJASTRA"],
            ["clave"=> "24","nombre" => "ABUELA PATERNA"],
            ["clave"=> "25","nombre" => "HIJASTRO"],
            ["clave"=> "26","nombre" => "HIJO"],
            ["clave"=> "27","nombre" => "MADRASTRA"],
            ["clave"=> "28","nombre" => "MADRE"],
            ["clave"=> "29","nombre" => "MADRE ADOPTIVA"],
            ["clave"=> "30","nombre" => "MEDIA HERMANA"],
            ["clave"=> "31","nombre" => "MEDIO HERMANO"],
            ["clave"=> "32","nombre" => "NIETA"],
            ["clave"=> "33","nombre" => "NIETO"],
            ["clave"=> "34","nombre" => "NOVIA"],
            ["clave"=> "35","nombre" => "ABUELA POLITICA"],
            ["clave"=> "36","nombre" => "NOVIO"],
            ["clave"=> "37","nombre" => "NUERA"],
            ["clave"=> "38","nombre" => "PADRASTRO"],
            ["clave"=> "39","nombre" => "PADRE"],
            ["clave"=> "40","nombre" => "PADRE ADOPTIVO"],
            ["clave"=> "41","nombre" => "PADRINO"],
            ["clave"=> "42","nombre" => "PAREJA"],
            ["clave"=> "43","nombre" => "PAREJA SENTIMENTAL"],
            ["clave"=> "44","nombre" => "PRIMA"],
            ["clave"=> "45","nombre" => "PRIMA HERMANA"],
            ["clave"=> "46","nombre" => "ABUELO"],
            ["clave"=> "47","nombre" => "PRIMA POLITICA"],
            ["clave"=> "48","nombre" => "PRIMA SEGUNDA"],
            ["clave"=> "49","nombre" => "PRIMO"],
            ["clave"=> "50","nombre" => "PRIMO HERMANO"],
            ["clave"=> "51","nombre" => "PRIMO POLITICO"],
            ["clave"=> "52","nombre" => "PRIMO SEGUNDO"],
            ["clave"=> "53","nombre" => "SOBRINA"],
            ["clave"=> "54","nombre" => "SOBRINA NIETA"],
            ["clave"=> "55","nombre" => "SOBRINA POLITICA"],
            ["clave"=> "56","nombre" => "SOBRINO"],
            ["clave"=> "57","nombre" => "ABUELO MATERNO"],
            ["clave"=> "58","nombre" => "SOBRINO NIETO"],
            ["clave"=> "59","nombre" => "SOBRINO POLITICO"],
            ["clave"=> "60","nombre" => "SUEGRA"],
            ["clave"=> "61","nombre" => "SUEGRO"],
            ["clave"=> "62","nombre" => "TATARABUELA"],
            ["clave"=> "63","nombre" => "TATARABUELO"],
            ["clave"=> "64","nombre" => "TIA"],
            ["clave"=> "65","nombre" => "TIA ABUELA"],
            ["clave"=> "66","nombre" => "TIA MATERNA"],
            ["clave"=> "67","nombre" => "TIA PATERNA"],
            ["clave"=> "68","nombre" => "ABUELO PATERNO"],
            ["clave"=> "69","nombre" => "TIA POLITICA"],
            ["clave"=> "70","nombre" => "TIA SEGUNDA"],
            ["clave"=> "71","nombre" => "TIO"],
            ["clave"=> "72","nombre" => "TIO ABUELO"],
            ["clave"=> "73","nombre" => "TIO MATERNO"],
            ["clave"=> "74","nombre" => "TIO PATERNO"],
            ["clave"=> "75","nombre" => "TIO POLITICO"],
            ["clave"=> "76","nombre" => "TIO SEGUNDO"],
            ["clave"=> "77","nombre" => "TUTOR"],
            ["clave"=> "78","nombre" => "YERNO"],
            ["clave"=> "79","nombre" => "ABUELO POLITICO"],
            ["clave"=> "80","nombre" => "AMIGA"],
            ["clave"=> "81","nombre" => "BISABUELA"],
            ["clave"=> "82","nombre" => "BISABUELO"],
            ["clave"=> "83","nombre" => "CONSUEGRA"],
            ["clave"=> "84","nombre" => "CONSUEGRO"],
        ];

        foreach($datos as $dato)
            Parentesco::create($dato);
    }
}
