<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TipoVictimaEnum extends Enum
{
    const DIRECTA = 1;
    const INDIRECTA  = 2;
}
