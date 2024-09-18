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
    public $tableName = 'notificaciones';

    /**
     * Run the migrations.
     * @table notificaciones
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('descripcion');
            $table->string('tipo');
            $table->string('rol');
            $table->string('area');
            $table->string('etiqueta');
            $table->increments('notificaciones_id');

            $table->unique(["notificaciones_id"], 'unique_id');
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
