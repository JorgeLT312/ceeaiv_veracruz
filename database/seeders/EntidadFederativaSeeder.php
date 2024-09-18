<?php

namespace Database\Seeders;

use App\Models\EntidadFederativa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EntidadFederativaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $datos = [
            [
            'entidades_federativas_id' => '01',
            'name' =>  'Aguascalientes',
            'abbrev' =>  'Ags.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '02',
            'name' =>  'Baja California',
            'abbrev' =>  'BC',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '03',
            'name' =>  'Baja California Sur',
            'abbrev' =>  'BCS',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '04',
            'name' =>  'Campeche',
            'abbrev' =>  'Camp.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '05',
            'name' =>  'Coahuila de Zaragoza',
            'abbrev' =>  'Coah.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '06',
            'name' =>  'Colima',
            'abbrev' =>  'Col.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '07',
            'name' =>  'Chiapas',
            'abbrev' =>  'Chis.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '08',
            'name' =>  'Chihuahua',
            'abbrev' =>  'Chih.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '09',
            'name' =>  'Distrito Federal',
            'abbrev' =>  'DF',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '10',
            'name' =>  'Durango',
            'abbrev' =>  'Dgo.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '11',
            'name' =>  'Guanajuato',
            'abbrev' =>  'Gto.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '12',
            'name' =>  'Guerrero',
            'abbrev' =>  'Gro.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '13',
            'name' =>  'Hidalgo',
            'abbrev' =>  'Hgo.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '14',
            'name' =>  'Jalisco',
            'abbrev' =>  'Jal.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '15',
            'name' =>  'México',
            'abbrev' =>  'Mex.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '16',
            'name' =>  'Michoacán de Ocampo',
            'abbrev' =>  'Mich.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '17',
            'name' =>  'Morelos',
            'abbrev' =>  'Mor.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '18',
            'name' =>  'Nayarit',
            'abbrev' =>  'Nay.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '19',
            'name' =>  'Nuevo León',
            'abbrev' =>  'NL',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '20',
            'name' =>  'Oaxaca',
            'abbrev' =>  'Oax.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '21',
            'name' =>  'Puebla',
            'abbrev' =>  'Pue.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '22',
            'name' =>  'Querétaro',
            'abbrev' =>  'Qro.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '23',
            'name' =>  'Quintana Roo',
            'abbrev' =>  'Q. Roo',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '24',
            'name' =>  'San Luis Potosí',
            'abbrev' =>  'SLP',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '25',
            'name' =>  'Sinaloa',
            'abbrev' =>  'Sin.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '26',
            'name' =>  'Sonora',
            'abbrev' =>  'Son.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '27',
            'name' =>  'Tabasco',
            'abbrev' =>  'Tab.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '28',
            'name' =>  'Tamaulipas',
            'abbrev' =>  'Tamps.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '29',
            'name' =>  'Tlaxcala',
            'abbrev' =>  'Tlax.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '30',
            'name' =>  'Veracruz de Ignacio de la Llave',
            'abbrev' =>  'Ver.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '31',
            'name' =>  'Yucatán',
            'abbrev' =>  'Yuc.',
            'country' =>  'MX'
        ],
        [
            'entidades_federativas_id' => '32',
            'name' =>  'Zacatecas',
            'abbrev' =>  'Zac.',
            'country' =>  'MX'
        ]
        ];


        foreach($datos as $dato)
            EntidadFederativa::create($dato);
    }
}
