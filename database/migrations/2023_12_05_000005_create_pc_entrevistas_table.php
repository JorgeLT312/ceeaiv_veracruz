<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'pc_entrevistas';

    /**
     * Run the migrations.
     * @table pc_entrevistas
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');//valores no nullos
            $table->string('modalidad_atencion')->nullable()->default(null);//valores no nullos
            $table->integer('colaboracion')->nullable()->default(null);//valores no nullos
            $table->string('institucion')->nullable()->default(null);
            $table->string('no_oficio')->nullable()->default(null);
            $table->integer('es_competencia_ceeaiv')->nullable()->default(null);//valores no nullos
            $table->string('orientacion_brindada')->nullable()->default(null);//valores no nullos
            $table->string('actividad_ingreso')->nullable()->default(null);//valores no nullos
            $table->string('cual_actividad_ingreso')->nullable()->default(null);
            $table->string('programa_social')->nullable()->default(null);//valores no nullos
            $table->string('cual_programa_social')->nullable()->default(null);
            $table->string('apoyo_federacion_estado')->nullable()->default(null);//valores no nullos
            $table->string('cual_apoyo_federacion_estado')->nullable()->default(null);
            $table->string('afectacion_salud')->nullable()->default(null);//valores no nullos
            $table->string('cual_afectacion_salud')->nullable()->default(null);
            $table->string('servicio_atencion_medica')->nullable()->default(null);//valores no nullos
            $table->string('cual_servicio_atencion_medica')->nullable()->default(null);
            $table->integer('gestion_medica')->nullable()->default(null);//valores no nullos
            $table->string('gestion_medica_necesidades')->nullable()->default(null);
            $table->string('gestion_medica_derivacion')->nullable()->default(null);
            $table->integer('gestion_social')->nullable()->default(null);//valores no nullos
            $table->string('gestion_social_necesidades')->nullable()->default(null);
            $table->string('gestion_social_derivacion')->nullable()->default(null);
            $table->string('gestion_social_num_seguro_social')->nullable()->default(null);
            $table->integer('atencion_psicologica')->nullable()->default(null);//valores no nullos
            $table->string('atencion_psicologica_necesidades')->nullable()->default(null);
            $table->string('atencion_psicologica_derivacion')->nullable()->default(null);
            $table->integer('atencion_psicologica_es_interna')->nullable()->default(null);//valores no nullos
            $table->integer('atencion_psicologica_psicologo_id')->nullable()->default(null);
            $table->integer('asesoria_juridica')->nullable()->default(null);//valores no nullos
            $table->string('asesoria_juridica_necesidades')->nullable()->default(null);
            $table->string('asesoria_juridica_derivacion')->nullable()->default(null);
            $table->longText('asesoria_juridica_opciones')->nullable()->default(null);
            $table->string('asesoria_juridica_opciones_otros')->nullable()->default(null);
            $table->integer('asesoria_juridica_asesor_id')->nullable()->default(null);
            $table->integer('solicitudes_ayuda')->nullable()->default(null);//valores no nullos
            $table->longText('solicitudes_ayuda_opciones')->nullable()->default(null);
            $table->integer('registro_rev')->nullable()->default(null);//valores no nullos
            $table->integer('ejerce_custodia_rev')->nullable()->default(null);
            $table->string('quien_ejerce_custodia_rev')->nullable()->default(null);
            $table->string('observaciones_rev')->nullable()->default(null);
            $table->string('familiar_observaciones')->nullable()->default(null);
            $table->string('como_se_entero_ceeaiv')->nullable()->default(null);
            $table->bigInteger('factores_vulnerabilidades_id')->nullable()->default(null);//valores no nullos
            $table->integer('victimas_directas_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('datos_solicitantes_id')->nullable()->default(null);//valores no nullos
            $table->integer('hechos_victimizantes_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('domicilio_notificaciones_id')->nullable()->default(null);//valores no nullos
            $table->integer('generales_id')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_solicitud_ingreso')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_fud')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_acta_nacimiento')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_curp')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_iden_oficial')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_comprobante_domicilio')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_calidad_victima')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_cedula')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_noti_electronica')->nullable()->default(null);//valores no nullos
            $table->string('url_escaner_acuerdo')->nullable()->default(null);//valores no nullos
            $table->bigInteger('victimas_indirectas_id')->nullable()->default(null);//valores no nullos

            $table->unique(["id"], 'unique_id');

            /*$table->unique(["factores_vulnerabilidades_id"], 'unique_factores_vulnerabilidades_id');

            $table->unique(["victimas_directas_id"], 'unique_victimas_directas_id');

            $table->unique(["datos_solicitantes_id"], 'unique_datos_solicitantes_id');

            $table->unique(["hechos_victimizantes_id"], 'unique_hechos_victimizantes_id');

            $table->unique(["domicilio_notificaciones_id"], 'unique_domicilio_notificaciones_id');

            $table->unique(["generales_id"], 'unique_generales_id');

            $table->unique(["victimas_indirectas_id"], 'unique_victimas_indirectas_id');*/
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName);
    }
};
