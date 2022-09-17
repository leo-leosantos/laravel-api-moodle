<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatricularsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matriculars', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',60)->nullable();
            $table->string('apellidos',60)->nullable();
            $table->string('email',60)->nullable();
            $table->string('nombreusuario',60)->nullable();
            $table->string('contrasenia',60)->nullable();
            $table->Integer('curso_id')->nullable()->default(-1);
            $table->string('nombrecortodelcurso',120)->nullable();
            $table->string('nombrelargodelcurso',220)->nullable();
            $table->Integer('cantidaddiassiningreso')->nullable()->default(0);
            $table->string('codigopais',60)->nullable();
            $table->string('nombrepais',60)->nullable();
            $table->boolean('yaregistrado')->nullable()->default(0);
            $table->boolean('matricula')->nullable()->default(0);

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
        Schema::dropIfExists('matriculars');
    }
}
