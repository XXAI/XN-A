<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCfdiTiposPercepcion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cfdi_tipos_concepto_pago', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('tipo_concepto',1)->nullable()->default(null)->comment('Tipo: P->percepción o D->deducción');
            $table->char('clave',3);
            $table->string('descripcion', 255);
            $table->date('fecha_inicio_vigencia');
            $table->date('fecha_fin_vigencia')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(["tipo_concepto"]);
            $table->index(["clave"]);
            $table->index(["descripcion"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cfdi_tipos_concepto_pago');
    }
}
