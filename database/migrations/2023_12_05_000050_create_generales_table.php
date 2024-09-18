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
    public $tableName = 'generales';

    /**
     * Run the migrations.
     * @table generales
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('no_rev');
            $table->string('no_renavi')->default('Sin folio');
            $table->string('estatus_atencion');
            $table->increments('id');
            $table->integer('tipo_victimas_id');
            $table->integer('datos_completo_renavi')->default(0);
            $table->integer('created_by')->nullable()->default(null);
            $table->timestamps();

            //$table->unique(["tipo_victimas_id"], 'unique_tipo_victimas_id');
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
