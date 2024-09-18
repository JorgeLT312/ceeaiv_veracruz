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
    public $tableName = 'domicilio_notificaciones';

    /**
     * Run the migrations.
     * @table domicilio_notificaciones
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('calle')->nullable()->default(null);
            $table->string('no_exterior')->nullable()->default(null);
            $table->string('no_interior')->nullable()->default(null);
            $table->string('colonia')->nullable()->default(null);
            $table->string('codigo_postal')->nullable()->default(null);
            $table->string('localidad')->nullable()->default(null);
            $table->string('estado_civil')->nullable()->default(null);
            $table->string('escolaridad')->nullable()->default(null);
            $table->string('ocupacion_id')->nullable()->default(null);
            $table->integer('organizacion_pertenece')->nullable()->default(null);
            $table->string('cual_organizacion_pertenece')->nullable()->default(null);
            $table->bigIncrements('domicilio_notificaciones_id');
            $table->string('telefono')->nullable()->default(null);
            $table->string('lada')->nullable()->default(null);
            $table->string('extension')->nullable()->default(null);
            $table->bigInteger('territorios_id')->nullable()->default(null);
            $table->bigInteger('paises_id')->nullable()->default(null);
            $table->bigInteger('entidades_federativas_id')->nullable()->default(null);
            $table->bigInteger('municipios_id')->nullable()->default(null);
            $table->integer('ocupaciones_id')->nullable()->default(null);
            $table->bigInteger('tipos_telefonos_id')->nullable()->default(null);
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
