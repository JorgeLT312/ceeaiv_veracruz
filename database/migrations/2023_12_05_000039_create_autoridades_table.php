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
    public $tableName = 'autoridades';

    /**
     * Run the migrations.
     * @table autoridades
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('autoridades_id');
            $table->string('observaciones')->nullable()->default(null);
            $table->string('tipo_danio_id')->nullable()->default(null);
            $table->integer('generales_id');
            $table->string('nombre_autoridad')->nullable()->default(null);
            $table->integer('denuncio_ante_ministerio')->nullable()->default(null);
            $table->string('fecha')->nullable()->default(null);
            $table->string('competencia')->nullable()->default(null);
            $table->integer('entidades_federativas_id')->nullable()->default(null);
            $table->integer('delitos_id')->nullable()->default(null);
            $table->integer('violaciones_id')->nullable()->default(null);
            $table->string('agencia_mp')->nullable()->default(null);
            $table->string('ap_ci_ac')->nullable()->default(null);
            $table->string('estado_investigacion')->nullable()->default(null);
            $table->string('num_juzgado')->nullable()->default(null);
            $table->string('num_proceso')->nullable()->default(null);
            $table->string('estado_proceso_judicial')->nullable()->default(null);
            $table->string('organismo')->nullable()->default(null);
            $table->string('autoridad_responsable')->nullable()->default(null);
            $table->string('tipo_resolucion')->nullable()->default(null);
            $table->string('folio')->nullable()->default(null);
            $table->string('estado_actual')->nullable()->default(null);
            $table->integer('presento_queja_ddhh')->nullable()->default(null);
            $table->string('nombre_otra_autoridad')->nullable()->default(null);
            $table->string('nombres')->nullable()->default(null);
            $table->string('primer_apellido')->nullable()->default(null);
            $table->string('segundo_apellido')->nullable()->default(null);
            $table->string('institucion')->nullable()->default(null);
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
