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
    public $tableName = 'datos_solicitantes';

    /**
     * Run the migrations.
     * @table datos_solicitantes
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('nombres');
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->date('fecha_nacimiento')->nullable()->default(null);
            $table->string('poblacion')->nullable()->default(null);
            $table->bigInteger('telefonos')->nullable()->default(null);
            $table->bigIncrements('datos_solicitantes_id');
            $table->bigInteger('municipios_id')->nullable()->default(null);
            $table->bigInteger('territorios_id')->nullable()->default(null);
            $table->bigInteger('paises_id')->nullable()->default(null);
            $table->bigInteger('entidades_federativas_id')->nullable()->default(null);
            $table->bigInteger('sexos_id')->nullable()->default(null);
            $table->bigInteger('ambito_dependencias_id')->nullable()->default(null);
            $table->bigInteger('parentescos_id')->nullable()->default(null);
            $table->bigInteger('situaciones_migratorias_id')->nullable()->default(null);
            $table->bigInteger('identidad_probatorio_documentos_id')->nullable()->default(null);
            $table->bigInteger('documentos_probatorios_id')->nullable()->default(null);
            $table->string('cargo')->nullable()->default(null);
            $table->string('dependencia')->nullable()->default(null);
            $table->integer('nivel_dependencia')->nullable()->default(null);
            $table->integer('generales_id');
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
