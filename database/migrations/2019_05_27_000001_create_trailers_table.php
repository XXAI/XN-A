<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrailersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'trailers';

    /**
     * Run the migrations.
     * @table trailers
     *
     * @return void
     */
    public function up(){
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('rfc', 13)->nullable()->default(null);
            $table->string('numemp', 10)->nullable()->default(null)->comment('Numero de empleado');
            $table->string('numcheq', 9)->nullable()->default(null)->comment('Numero de cheque');
            $table->integer('tconcep')->nullable()->default(null)->comment('Tipo');
            $table->string('concepto', 2)->nullable()->default(null);
            $table->decimal('importe', 10, 2)->nullable()->default(null);
            $table->string('anio', 4)->nullable()->default(null)->comment('Año del vencimiento del trailer');
            $table->string('qna', 2)->nullable()->default(null)->comment('Quincena de vencimiento del trailer');
            $table->string('ptaant', 2)->nullable()->default(null)->comment('Partida antecedente\\n');
            $table->string('totpagos', 4)->nullable()->default(null)->comment('Total De Pagos (Tipo 3)');
            $table->string('pagoefec', 4)->nullable()->default(null)->comment('Pagos Efectuados (Tipo 3)');
            $table->string('nomprod', 11)->nullable()->default(null)->comment('Nombre Del Producto');
            $table->integer('numctrol')->nullable()->default(null)->comment('Número de control del Sistema');

            $table->index(["numcheq"]);
            $table->index(["anio"]);
            $table->index(["tconcep"]);
            $table->index(["rfc"]);
            $table->index(["nomprod"]);
            $table->index(["numctrol"]);
            $table->index(["qna"]);
            $table->index(["ptaant"]);
            $table->index(["numemp"]);
            $table->index(["concepto"]);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
     public function down(){
       Schema::dropIfExists($this->tableName);
     }
}
