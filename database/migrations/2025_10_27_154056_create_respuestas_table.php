<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('respuestas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluacion_id')->constrained('evaluaciones')->onDelete('cascade');
            $table->foreignId('pregunta_id')->constrained('preguntas');
            $table->text('valor_respuesta')->nullable();
            $table->integer('puntaje')->default(0);
            $table->text('comentarios')->nullable();
            $table->timestamps();

            // Índice para evitar respuestas duplicadas a la misma pregunta en una evaluación
            $table->unique(['evaluacion_id', 'pregunta_id'], 'respuesta_unica');
        });
    }

    public function down()
    {
        Schema::dropIfExists('respuestas');
    }
};
