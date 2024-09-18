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
    public $tableName = 'registros_estatales';

    /**
     * Run the migrations.
     * @table registros_estatales
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('lugar_atencion')->nullable()->default(null);
            $table->date('fecha_atencion')->nullable()->default(null);
            $table->string('folio_expediente')->nullable()->default(null);
            $table->bigInteger('factores_vulnerabilidades_id')->nullable()->default(null);
            $table->bigInteger('victimas_indirectas_id')->nullable()->default(null);
            $table->bigInteger('domicilio_notificaciones_id')->nullable()->default(null);
            $table->integer('tipo_solicitantes_id')->nullable()->default(null);
            $table->bigInteger('datos_solicitantes_id')->nullable()->default(null);
            $table->bigInteger('generales_id')->nullable()->default(null);
            $table->integer('autoridades_id')->nullable()->default(null);
            $table->integer('fud_completo')->default(0);
            $table->string('expediente_completo')->nullable()->default(null);//valores no nullos
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
