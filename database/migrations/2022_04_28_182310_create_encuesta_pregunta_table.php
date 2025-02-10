<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('sqlsrv')->create('encuesta_pregunta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encuesta_id');
            $table->unsignedBigInteger('pregunta_id');

            
            $table->foreign('encuesta_id')->references('id')->on('encuestas')->onDelete('cascade');
            $table->foreign('pregunta_id')->references('id')->on('preguntas')->onDelete('cascade');
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
        Schema::connection('sqlsrv')->dropIfExists('encuesta_pregunta');
    }
};
