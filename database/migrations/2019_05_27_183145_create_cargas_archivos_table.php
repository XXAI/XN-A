<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCargasArchivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::create('cargas_archivos', function (Blueprint $table) {
            $table->bigIncrements('batch');
            $table->string('nombre_archivo_tra',255)->nullable();
            $table->string('nombre_archivo_dat',255)->nullable();
            $table->string('qnareal', 2);
            $table->string('anioreal', 4);
            $table->string('nomprod',11);
            //$table->timestamp('created_at')->useCurrent();
            $table->timestamps();
            $table->softDeletes();

            $table->index(["nomprod"]);
            $table->index(["qnareal"]);
            $table->index(["anioreal"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists('cargas_archivos');
    }
}
