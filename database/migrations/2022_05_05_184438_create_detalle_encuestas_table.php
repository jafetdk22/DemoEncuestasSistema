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
        Schema::connection('sqlsrv')->create('detalle_encuestas', function (Blueprint $table) {
            $table->id();
            $table->string('No_Orden'); // Solo almacena el valor sin clave foránea
            $table->unsignedBigInteger('encuesta_id');
        
            // Preguntas y respuestas
            for ($i = 1; $i <= 10; $i++) {
                $table->integer("P$i")->nullable();
                $table->string("Resp_P$i")->nullable();
            }
        
            // Otros campos
            $table->text('Resp_Texto')->nullable();
            $table->unsignedBigInteger('Asesor');
            $table->integer('Year');
            $table->string('month', 15);
            $table->integer('Day');
            $table->date('Odate');
            $table->decimal('Promedio', 5, 2)->nullable();
            $table->string('concesion')->nullable();
            
            $table->timestamps();
        
            // Solo mantiene la clave foránea de encuestas
            $table->foreign('encuesta_id')->references('id')->on('encuestas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('sqlsrv')->dropIfExists('detalle_encuestas');
    }
};
