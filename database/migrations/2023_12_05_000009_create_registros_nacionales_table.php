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
    public $tableName = 'registros_nacionales';

    /**
     * Run the migrations.
     * @table registros_nacionales
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('generales_id');
            $table->bigInteger('domicilio_notificaciones_id');
            $table->bigInteger('factores_vulnerabilidades_id');
            $table->integer('autoridades_id');
            $table->timestamps();

            $table->unique(["generales_id"], 'unique_generales_id');

            $table->unique(["domicilio_notificaciones_id"], 'unique_domicilio_notificaciones_id');

            $table->unique(["factores_vulnerabilidades_id"], 'unique_factores_vulnerabilidades_id');

            $table->unique(["autoridades_id"], 'unique_autoridades_id');
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
