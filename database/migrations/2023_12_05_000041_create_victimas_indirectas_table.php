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
    public $tableName = 'victimas_indirectas';

    /**
     * Run the migrations.
     * @table victimas_indirectas
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
            $table->bigIncrements('victimas_indirectas_id');//valores no nullos;
            $table->bigInteger('parentescos_id')->nullable()->default(null);//valores no nullos;
            $table->integer('generales_id');//valores no nullos
            //Agregados apartir de rev
            $table->string('curp')->nullable()->default(null);//valores no nullos
            $table->bigInteger('sexos_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('territorios_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('paises_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('entidades_federativas_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('municipios_id')->nullable()->default(null);//valores no nullos
            $table->string('poblacion')->nullable()->default(null);//valores no nullos
            $table->integer('estado_civil_id')->nullable()->default(null);//valores no nullos
            $table->date('fecha_nacimiento')->nullable()->default(null);//valores no nullos
            $table->timestamps();

           /* $table->unique(["documentos_probatorios_id"], 'unique_documentos_probatorios_id');

            $table->unique(["parentescos_id"], 'unique_parentescos_id');

            $table->unique(["identidad_probatorio_documentos_id"], 'unique_identidad_probatorio_documentos_id');*/
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
