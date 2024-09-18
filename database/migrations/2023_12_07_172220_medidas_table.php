<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public $tableName = 'medidas';
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('nivel');
            $table->string('clave');
            $table->string('code');
            $table->string('nivel1');
            $table->string('nivel2');
            $table->string('nivel3');
            $table->string('nivel4');
            $table->string('nivel5');
            $table->timestamps();
            $table->bigIncrements('medidas_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists($this->tableName);
    }
};
