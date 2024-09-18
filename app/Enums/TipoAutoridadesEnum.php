<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoAutoridadesEnum extends Enum
{
    const MINISTERIAL = 'Investigación ministerial';
    const PODER_JUDICIAL = 'Proceso judicial';
    const DDHH = 'Procedimientos ante DDHH';
    const OTRA_AUTORIDAD = 'Otra autoridad';
}