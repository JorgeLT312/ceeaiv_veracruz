<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class AreasEnum extends Enum
{
    const RECEPCION ="Recepción";
    const PC ="Primer Contacto";
    const REV ="Registro Estatal de Víctimas";
    const JURIDICO ="Juridico";
    const ADMINISTRACION ="Administración";
    const FORANEOS ="Foraneos";
    const OTROS ="Otros";
}
