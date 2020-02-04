<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableConceptosPagoAgregarDatosCfdiConceptosPago extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conceptos_pago', function (Blueprint $table) {
            $table->char('clave_cfdi',3)->nullable()->after('descripcion');
            $table->boolean('gravado')->default(false)->after('clave_cfdi');

            $table->index(["clave_cfdi"]);
            $table->index(['gravado']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conceptos_pago', function (Blueprint $table) {
            $table->dropIndex(['clave_cfdi']);
            $table->dropIndex(['gravado']);

            $table->dropColumn('clave_cfdi');
            $table->dropColumn('gravado');
        });
    }
}
