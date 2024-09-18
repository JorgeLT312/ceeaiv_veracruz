<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EstatusAtencionEnum extends Enum
{
    const REGISTRADA  = '';
    const PRIMER_CONTACTO  = 'Primer Contacto';
    const ESPERANDO_APROBACION_REV  = 'Esperando Aprobación REV';
    const REV = 'REV';
    const NO_APROBADO  = 'No Aprobado';
    const ESPERANDO_REV_SIN_PC  = 'Esperando Aprobación REV sin PC';
    const REV_SIN_PC  = 'REV sin PC';
    const EN_ESPERA_RENAVI = 'En Espera RENAVI';
    const RENAVI = 'RENAVI';
    const CAPTURA_INICIAL = 'Captura Inicial';
}
