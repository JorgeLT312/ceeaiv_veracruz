<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class EtiquetaNotificacionesEnum extends Enum
{
    const lleno_entrevista_pc = 'lleno_entrevista_pc';
    const solicito_asesoria = 'solicito_asesoria';
    const solicito_asesoria_asignado = 'solicito_asesoria_asignado';
    const solicito_psicologo = 'solicito_psicologo';
    const solicito_psicologo_asignado = 'solicito_psicologo_asignado';
    const solicito_registro_rev = 'solicito_registro_rev';
    const solicito_registro_rev_superior = 'solicito_registro_rev_superior';
    const solicito_registro_rev_operativo = 'solicito_registro_rev_operativo';
    const llena_formulario_rev = 'llena_formulario_rev';
    const llena_formulario_rev_registro = 'llena_formulario_rev_registro';
    const asigno_estatus_rev = 'asigno_estatus_rev';
    const asigno_estatus_rev_sin_pc = 'asigno_estatus_rev_sin_pc';
    const asigno_estatus_rev_sin_pc_operativo = 'asigno_estatus_rev_sin_pc_operativo';
    const asigno_estatus_rev_sin_pc_superior = 'asigno_estatus_rev_sin_pc_superior';
    const asigno_status_renavi = 'asigno_status_renavi';
}
