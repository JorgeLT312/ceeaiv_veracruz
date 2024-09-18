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
    public $tableName = 'victimas_directas';

    /**
     * Run the migrations.
     * @table victimas_directas
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
            $table->date('fecha_nacimiento')->nullable()->default(null);//valores no nullos
            $table->string('delitos_id')->nullable()->default(null);//valores no nullos
            $table->string('violaciones_id')->nullable()->default(null);//valores no nullos
            $table->string('no_carpeta')->nullable()->default(null);//valores no nullos
            $table->string('fiscalia')->nullable()->default(null);//valores no nullos
            $table->increments('victimas_directas_id');//valores no nullos
            $table->string('observaciones_familiares')->nullable()->default(null);//valores no nullos
            $table->integer('registros_estatales_id')->nullable()->default(null);//valores no nullos
            $table->integer('ocupaciones_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('sexos_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('territorios_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('paises_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('entidades_federativas_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('municipios_id')->nullable()->default(null);//valores no nullos
            $table->string('poblacion')->nullable()->default(null);//valores no nullos
            $table->string('curp')->nullable()->default(null);//valores no nullos
            $table->integer('estado_civil_id')->nullable()->default(null);//valores no nullos
            $table->integer('presenta_identificacion')->nullable()->default(null);//valores no nullos;
            $table->string('identidad_probatorio_documentos_id')->nullable()->default(null);//valores no nullos;
            $table->string('tipo_identificacion_id')->nullable()->default(null);//valores no nullos
            $table->bigInteger('parentescos_id')->nullable()->default(null);//valores no nullos;
            $table->integer('generales_id');//valores no nullos
            $table->timestamps();

            /*$table->unique(["registros_estatales_id"], 'unique_registros_estatales_id');

            $table->unique(["ocupaciones_id"], 'unique_ocupaciones_id');

            $table->unique(["sexos_id"], 'unique_sexos_id');

            $table->unique(["territorios_id"], 'unique_territorios_id');

            $table->unique(["paises_id"], 'unique_paises_id');

            $table->unique(["entidades_federativas_id"], 'unique_entidades_federativas_id');

            $table->unique(["municipios_id"], 'unique_municipios_id');*/
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
