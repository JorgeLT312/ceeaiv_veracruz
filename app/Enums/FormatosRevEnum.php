<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class FormatosRevEnum extends Enum
{
    const SOLICITUD_INGRESO = 'Solicitud de ingreso';
    const FUD  = 'FUD';
    const ACTA_NACIMIENTO  = 'Acta de nacimiento';
    const CURP  = 'CURP';
    const IDENTIFICACION_OFICIAL  = 'Identificación Oficial';
    const COMPROBANTE_DOMICILIO  = 'Comprobante de domicilio';
    const CONSTANCIA_CALIDAD  = 'Constancia de calidad de víctima';
    const CEDULA  = 'Cédula';
    const NOTIFICACION_ELECTRONICA  = 'Notificación electrónica';
    const ACUERDO  = 'Acuerdo';
}
