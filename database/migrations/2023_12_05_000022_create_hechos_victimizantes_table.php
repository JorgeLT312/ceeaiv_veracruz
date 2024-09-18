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
    public $tableName = 'hechos_victimizantes';

    /**
     * Run the migrations.
     * @table hechos_victimizantes
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('victimizante_hechos_id');
            $table->integer('ambito_competencia')->nullable()->default(null);
            $table->string('fecha_hecho_ini')->nullable()->default(null);
            $table->string('fecha_hecho_fin')->nullable()->default(null);
            $table->string('recomendacion')->nullable()->default(null);
            $table->text('relato')->nullable()->default(null);
            $table->string('calle')->nullable()->default(null);
            $table->string('num_exterior')->nullable()->default(null);
            $table->string('num_interior')->nullable()->default(null);
            $table->string('colonia')->nullable()->default(null);
            $table->string('codigo_postal')->nullable()->default(null);
            $table->string('localidad')->nullable()->default(null);
            $table->integer('territorios_id')->nullable()->default(null);
            $table->integer('paises_id')->nullable()->default(null);
            $table->integer('entidades_federativas_id')->nullable()->default(null);
            $table->integer('municipios_id')->nullable()->default(null);
            $table->string('poblacion')->nullable()->default(null);
            $table->integer('generales_id')->nullable()->default(null);
            $table->integer('conoces_lugar_hechos')->nullable()->default(null);
            $table->text('lo_que_conoces_lugar_hechos')->nullable()->default(null);
            $table->string('delitos_id')->nullable()->default(null);//valores no nullos
            $table->integer('violaciones_id')->nullable()->default(null);
            $table->string('tipo_danios')->nullable()->default(null);
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
