<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formulario_id')->constrained('formularios');
            $table->foreignId('evaluador_id')->constrained('users');
            $table->foreignId('evaluado_id')->constrained('users');
            $table->date('fecha_evaluacion');
            $table->integer('calificacion_total')->default(0);
            $table->integer('puntaje_obtenido')->default(0);
            $table->decimal('porcentaje_obtenido', 5, 2)->default(0);
            $table->timestamps();

            // Índice único para evitar evaluaciones duplicadas
            $table->unique(['formulario_id', 'evaluador_id', 'evaluado_id'], 'evaluacion_unica');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluaciones');
    }
};
