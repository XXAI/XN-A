<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDatosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('datos', function (Blueprint $table) {
            $table->bigInteger('batch')->unsigned();

            $table->index(["batch"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::table('datos', function (Blueprint $table) {
            $table->dropIndex(['batch']);

            $table->dropColumn('batch');
        });
    }
}
