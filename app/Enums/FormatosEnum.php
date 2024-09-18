<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class FormatosEnum extends Enum
{
    const PRIMER_CONTACTO = 'PRIMER_CONTACTO';
    const CARTA_ADULTA_REGIONES  = 'CARTA_ADULTA_REGIONES';
    const CARTA_ADULTA_CENTRALES  = 'CARTA_ADULTA_CENTRALES';
    const CARTA_NNA_CENTRALES  = 'CARTA_NNA_CENTRALES';
    const CARTA_NNA_REGIONES  = 'CARTA_NNA_REGIONES';
    const FUD  = 'FUD';
    const CEDULA  = 'CEDULA';
    const ACUERDOS  = 'ACUERDO';
}
