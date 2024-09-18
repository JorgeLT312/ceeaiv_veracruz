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
    public $tableName = 'familiares';

    /**
     * Run the migrations.
     * @table familiares
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('nombre_completo')->nullable()->default(null);
            $table->string('edad')->nullable()->default(null);
            $table->string('parentesco_id')->nullable()->default(null);
            $table->string('dependencia_economica')->nullable()->default(null);
            $table->string('factores_vulnerabilidad')->nullable()->default(null);
            $table->increments('familiares_id');
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
