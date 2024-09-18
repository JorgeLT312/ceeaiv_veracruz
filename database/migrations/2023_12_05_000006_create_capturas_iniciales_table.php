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
    public $tableName = 'capturas_iniciales';

    /**
     * Run the migrations.
     * @table capturas_iniciales
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('contacto_principal_nombres')->nullable()->default(null);
            $table->string('contacto_principal_primer_apellido')->nullable()->default(null);
            $table->string('contacto_principal_segundo_apellido')->nullable()->default(null);
            $table->string('contacto_principal_correo')->nullable()->default(null);
            $table->string('contacto_principal_telefono')->nullable()->default(null);
            $table->string('contacto_secundario_nombres')->nullable()->default(null);
            $table->string('contacto_secundario_primer_apellido')->nullable()->default(null);
            $table->string('contacto_secundario_segundo_apellido')->nullable()->default(null);
            $table->string('contacto_secundario_correo')->nullable()->default(null);
            $table->string('contacto_secundario_telefono')->nullable()->default(null);
            $table->integer('generales_id');
            $table->integer('victimas_directas_id')->nullable()->default(null);
            $table->bigInteger('victimas_indirectas_id')->nullable()->default(null);
            $table->timestamps();

            $table->unique(["generales_id"], 'unique_generales_id');
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
