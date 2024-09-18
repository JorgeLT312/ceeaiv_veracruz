<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RolesEnum extends Enum
{
    const ADMIN = 'Admin';
    const INVITADO = 'Invitado';
    const OPERATIVO = 'Operativo';
    const SUPERIOR = 'Nivel Superior';
}
