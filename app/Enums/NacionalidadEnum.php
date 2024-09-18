<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class NacionalidadEnum extends Enum
{
    const MEXICANA = 1;
    const EXTRANJERA = 2;
    const NINGUNA = 3;
}
