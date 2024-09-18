<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FactoresVulnerabilidades extends Model
{
    use HasFactory;
    protected $table = 'factores_vulnerabilidades';
    protected $fillable = [
        'es_adolescente',
        'es_adulto_mayor',
        'es_lgbttti',
        'es_discapacitado',
        'es_situacion_calle',
        'es_migrante',
        'es_desplazado_por_hecho',
        'es_periodista',
        'es_defensor_ddhh',
        'es_violencia_genero',
        'es_trastorno_mental',
        'es_vih',
        'observaciones',
        'generales_id',
        'factores_vulnerabilidades_id',
        'nombre_tutor',
        'primer_apellido_tutor',
        'segundo_apellido_tutor',
        'datos_contacto_tutor',
        'tipo_discapacidad',
        'grado_dependencia',
        'pais_origen',
        'pais_destino',
        'habla_espaniol',
        'requiere_traductor',
        'pertenece_poblacion_indigena',
        'cual_poblacion_indigena',
        'es_refugiado',
        'es_asilado_politico',
        'ha_iniciado_tramite_condicion',
        'pertenece_instutitucion',
        'tipo_institucion',
        'otra_institucion',
        'tipo_medio_informativo',
        'nombre_medio_informativo',
        'entidad_salida',
        'entidad_receptora',
        'hecho_victimizante_se_debio',
        'informacion_violencia_contra_mujeres',
    ];
}
