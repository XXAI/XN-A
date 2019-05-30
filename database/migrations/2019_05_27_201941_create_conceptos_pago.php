<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConceptosPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conceptos_pago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tconcep')->nullable()->default(null)->comment('Tipo');
            $table->string('concepto', 2)->nullable()->default(null);
            $table->string('ptaant', 2)->nullable()->default(null)->comment('Partida antecedente\\n');
            $table->char('partida', 5)->nullable();
            $table->char('descripcion', 255);
            $table->timestamps();

            $table->index(["tconcep"]);
            $table->index(["concepto"]);
            $table->index(["ptaant"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conceptos_pago');
    }
}
