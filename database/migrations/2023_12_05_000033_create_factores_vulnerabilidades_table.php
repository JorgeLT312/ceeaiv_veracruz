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
    public $tableName = 'factores_vulnerabilidades';

    /**
     * Run the migrations.
     * @table factores_vulnerabilidades
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('es_adolescente')->nullable()->default(null);//valores no nullos
            $table->integer('es_adulto_mayor')->nullable()->default(null);//valores no nullos
            $table->integer('es_lgbttti')->nullable()->default(null);//valores no nullos
            $table->integer('es_discapacitado')->nullable()->default(null);//valores no nullos
            $table->integer('es_situacion_calle')->nullable()->default(null);//valores no nullos
            $table->integer('es_migrante')->nullable()->default(null);//valores no nullos
            $table->integer('es_desplazado_por_hecho')->nullable()->default(null);//valores no nullos
            $table->integer('es_periodista')->nullable()->default(null);//valores no nullos
            $table->integer('es_defensor_ddhh')->nullable()->default(null);//valores no nullos
            $table->integer('es_violencia_genero')->nullable()->default(null);//valores no nullos
            $table->integer('es_trastorno_mental')->nullable()->default(null);//valores no nullos
            $table->integer('es_vih')->nullable()->default(null);//valores no nullos
            $table->string('observaciones')->nullable()->default(null);//valores no nullos
            $table->integer('generales_id');
            $table->bigIncrements('factores_vulnerabilidades_id');
            $table->string('nombre_tutor')->nullable()->default(null);
            $table->string('primer_apellido_tutor')->nullable()->default(null);
            $table->string('segundo_apellido_tutor')->nullable()->default(null);
            $table->string('datos_contacto_tutor')->nullable()->default(null);
            $table->string('tipo_discapacidad')->nullable()->default(null);
            $table->string('grado_dependencia')->nullable()->default(null);
            $table->string('pais_origen')->nullable()->default(null);
            $table->string('pais_destino')->nullable()->default(null);
            $table->integer('habla_espaniol')->nullable()->default(null);
            $table->string('requiere_traductor')->nullable()->default(null);
            $table->integer('pertenece_poblacion_indigena')->nullable()->default(null);
            $table->string('cual_poblacion_indigena')->nullable()->default(null);
            $table->integer('es_refugiado')->nullable()->default(null);
            $table->integer('es_asilado_politico')->nullable()->default(null);
            $table->string('ha_iniciado_tramite_condicion')->nullable()->default(null);
            $table->string('pertenece_instutitucion')->nullable()->default(null);
            $table->string('tipo_institucion')->nullable()->default(null);
            $table->string('otra_institucion')->nullable()->default(null);
            $table->string('tipo_medio_informativo')->nullable()->default(null);
            $table->string('nombre_medio_informativo')->nullable()->default(null);
            $table->string('entidad_salida')->nullable()->default(null);
            $table->string('entidad_receptora')->nullable()->default(null);
            $table->string('hecho_victimizante_se_debio')->nullable()->default(null);
            $table->string('informacion_violencia_contra_mujeres')->nullable()->default(null);
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
