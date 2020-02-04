<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableCargasArchivosAgregarLlaveGrupoNomina extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cargas_archivos', function (Blueprint $table) {
            $table->string('llave_grupo_nomina',10)->after('nomprod');
            
            $table->index(['llave_grupo_nomina']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cargas_archivos', function (Blueprint $table) {
            $table->dropIndex(['llave_grupo_nomina']);

            $table->dropColumn('llave_grupo_nomina');
        });
    }
}
