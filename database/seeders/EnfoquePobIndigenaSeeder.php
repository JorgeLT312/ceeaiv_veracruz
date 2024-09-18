<?php

namespace Database\Seeders;

use App\Models\EnfoquePobIndigena;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EnfoquePobIndigenaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            ["clave"=> "1","nombre" => "CORA"],
            ["clave"=> "2","nombre" => "CUCAPA"],
            ["clave"=> "3","nombre" => "GUARIJIO"],
            ["clave"=> "4","nombre" => "HUASTECO"],
            ["clave"=> "5","nombre" => "HUAVE"],
            ["clave"=> "6","nombre" => "HUICHOL"],
            ["clave"=> "7","nombre" => "IXCATECO"],
            ["clave"=> "8","nombre" => "IXIL"],
            ["clave"=> "9","nombre" => "JACALTECO"],
            ["clave"=> "10","nombre" => "AMUZGO"],
            ["clave"=> "11","nombre" => "KAKCHIQUEL"],
            ["clave"=> "12","nombre" => "KANJOBAL"],
            ["clave"=> "13","nombre" => "KEKCHI"],
            ["clave"=> "14","nombre" => "KIKAPU"],
            ["clave"=> "15","nombre" => "KILIWA"],
            ["clave"=> "16","nombre" => "KUMIAI"],
            ["clave"=> "17","nombre" => "LACANDON"],
            ["clave"=> "18","nombre" => "MAME"],
            ["clave"=> "19","nombre" => "MATLATZINCA"],
            ["clave"=> "20","nombre" => "MAYA"],
            ["clave"=> "21","nombre" => "CHATINO"],
            ["clave"=> "22","nombre" => "MAYO"],
            ["clave"=> "23","nombre" => "MAZAHUA"],
            ["clave"=> "24","nombre" => "MAZATECO"],
            ["clave"=> "25","nombre" => "MIXE"],
            ["clave"=> "26","nombre" => "MIXTECO"],
            ["clave"=> "27","nombre" => "MOTOCINTLECO"],
            ["clave"=> "28","nombre" => "NAHUATL"],
            ["clave"=> "29","nombre" => "OCUILTECO"],
            ["clave"=> "30","nombre" => "OTOMI"],
            ["clave"=> "31","nombre" => "PAIPAI"],
            ["clave"=> "32","nombre" => "CHICHIMECA JONAZ"],
            ["clave"=> "33","nombre" => "PAME"],
            ["clave"=> "34","nombre" => "PAPAGO"],
            ["clave"=> "35","nombre" => "PIMA"],
            ["clave"=> "36","nombre" => "POPOLOCA"],
            ["clave"=> "37","nombre" => "POPOLUCA"],
            ["clave"=> "38","nombre" => "PUREPECHA"],
            ["clave"=> "39","nombre" => "QUICHE"],
            ["clave"=> "40","nombre" => "SERI"],
            ["clave"=> "41","nombre" => "TACUATE"],
            ["clave"=> "42","nombre" => "TARAHUMARA"],
            ["clave"=> "43","nombre" => "CHINANTECO"],
            ["clave"=> "44","nombre" => "TEPEHUA"],
            ["clave"=> "45","nombre" => "TEPEHUANO"],
            ["clave"=> "46","nombre" => "TLAPASNEK"],
            ["clave"=> "47","nombre" => "TOJOLABAL"],
            ["clave"=> "48","nombre" => "TOTONACA"],
            ["clave"=> "49","nombre" => "TRIQUI"],
            ["clave"=> "50","nombre" => "TZELTAL"],
            ["clave"=> "51","nombre" => "TZOTZIL"],
            ["clave"=> "52","nombre" => "YAQUI"],
            ["clave"=> "53","nombre" => "ZAPOTECO"],
            ["clave"=> "54","nombre" => "CHOCHO"],
            ["clave"=> "55","nombre" => "ZOQUE"],
            ["clave"=> "56","nombre" => "CHOLES"],
            ["clave"=> "57","nombre" => "CHONTAL DE TABASCO"],
            ["clave"=> "58","nombre" => "CHUJ"],
            ["clave"=> "59","nombre" => "CHONTAL DE OAXACA"],
            ["clave"=> "60","nombre" => "OTROS PUEBLOS"],
        ];


        foreach($datos as $dato)
            EnfoquePobIndigena::create($dato);
    }
}
