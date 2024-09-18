<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrimerContacto extends Model
{
    use HasFactory;
    protected $table = 'pc_entrevistas';
    protected $fillable = [
        'id',
        'modalidad_atencion',
        'colaboracion',
        'institucion',
        'no_oficio',
        'es_competencia_ceeaiv',
        'orientacion_brindada',
        'actividad_ingreso',
        'cual_actividad_ingreso',
        'programa_social',
        'cual_programa_social',
        'apoyo_federacion_estado',
        'cual_apoyo_federacion_estado',
        'afectacion_salud',
        'cual_afectacion_salud',
        'servicio_atencion_medica',
        'cual_servicio_atencion_medica',
        'gestion_medica',
        'gestion_medica_necesidades',
        'gestion_medica_derivacion',
        'gestion_social',
        'gestion_social_necesidades',
        'gestion_social_derivacion',
        'gestion_social_num_seguro_social',
        'atencion_psicologica',
        'atencion_psicologica_necesidades',
        'atencion_psicologica_derivacion',
        'atencion_psicologica_es_interna',
        'atencion_psicologica_psicologo_id',
        'asesoria_juridica',
        'asesoria_juridica_necesidades',
        'asesoria_juridica_derivacion',
        'asesoria_juridica_opciones',
        'asesoria_juridica_opciones_otros',
        'asesoria_juridica_asesor_id',
        'solicitudes_ayuda',
        'solicitudes_ayuda_opciones',
        'registro_rev',
        'ejerce_custodia_rev',
        'quien_ejerce_custodia_rev',
        'observaciones_rev',
        'como_se_entero_ceeaiv',
        'factores_vulnerabilidades_id',
        'victimas_directas_id',
        'datos_solicitantes_id',
        'hechos_victimizantes_id',
        'domicilio_notificaciones_id',
        'generales_id',
        'victimas_indirectas_id',
        'url_escaner'
    ];
}
