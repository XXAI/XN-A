<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTrailersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        Schema::table('trailers', function (Blueprint $table) {
            $table->bigInteger('batch')->unsigned();
            $table->string('qnareal', 2);
            $table->string('anioreal', 4);

            $table->index(["batch"]);
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
        Schema::table('trailers', function (Blueprint $table) {
            $table->dropIndex(['batch']);
            $table->dropIndex(['qnareal']);
            $table->dropIndex(['anioreal']);

            $table->dropColumn('batch');
            $table->dropColumn('qnareal');
            $table->dropColumn('anioreal');
        });
    }
}
