<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatosTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $tableName = 'datos';

    /**
     * Run the migrations.
     * @table datos
     *
     * @return void
     */
    public function up(){
        Schema::create($this->tableName, function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('numemp', 10)->nullable()->default(null)->comment('Número De Empleado');
            $table->string('rfc', 13)->nullable()->default(null);
            $table->string('curp', 18)->nullable()->default(null);
            $table->string('nombre', 55)->nullable()->default(null);
            $table->string('sar', 20)->nullable()->default(null);
            $table->string('bancoan', 2)->nullable()->default(null)->comment('Banco Anterior');
            $table->string('bancon', 5)->nullable()->default(null)->comment('Banco Nuevo');
            $table->string('numcta', 12)->nullable()->default(null)->comment('Numero De Cuenta');
            $table->string('clabe', 20)->nullable()->default(null);
            $table->string('funcion', 2)->nullable()->default(null)->comment('Funcion del trabajador');
            $table->string('cp', 5)->nullable()->default(null)->comment('Codigo Postal');
            $table->string('calle', 254)->nullable()->default(null);
            $table->string('colonia', 254)->nullable()->default(null);
            $table->string('deleg', 254)->nullable()->default(null);
            $table->string('ur', 3)->nullable()->default(null)->comment('Unidad Responsable');
            $table->string('gf', 1)->nullable()->default(null)->comment('Grupo Funcional');
            $table->string('fn', 1)->nullable()->default(null)->comment('Funcion');
            $table->string('sf', 2)->nullable()->default(null)->comment('Subfuncion');
            $table->string('pg', 2)->nullable()->default(null)->comment('Programa General');
            $table->string('ai', 3)->nullable()->default(null)->comment('Actividad Institucional');
            $table->string('pp', 4)->nullable()->default(null)->comment('Proyecto Proceso');
            $table->string('partida', 5)->nullable()->default(null)->comment('Partida Presupuestal');
            $table->text('puesto')->nullable()->default(null);
            $table->string('numpto', 4)->nullable()->default(null)->comment('Numero De Puesto');
            $table->string('edo', 2)->nullable()->default(null)->comment('Estado');
            $table->string('mun', 3)->nullable()->default(null)->comment('Municipio');
            $table->string('cr', 10)->nullable()->default(null)->comment('Centro De Responsabilidad');
            $table->string('ci', 15)->nullable()->default(null)->comment('Cedula De Identidad');
            $table->string('pagad', 5)->nullable()->default(null)->comment('Pagaduria');
            $table->string('financiamiento', 2)->nullable()->default(null)->comment('Fuente De Financiamiento');
            $table->string('tabpto', 3)->nullable()->default(null)->comment('Tabulador');
            $table->string('nivel', 2)->nullable()->default(null);
            $table->string('rango', 1)->nullable()->default(null);
            $table->string('indmando', 2)->nullable()->default(null)->comment('Indicador De Mando');
            $table->string('horas', 1)->nullable()->default(null)->comment('Horario');
            $table->string('porcent', 8)->nullable()->default(null)->comment('Porcentaje');
            $table->string('tipotrab', 2)->nullable()->default(null)->comment('Tipo De Trabajador');
            $table->string('nivelpto', 4)->nullable()->default(null)->comment('Nivel De Puesto');
            $table->string('indemp', 1)->nullable()->default(null)->comment('Indicador Del Empleado');
            $table->string('figf', 8)->nullable()->default(null)->comment('Fecha Ingreso Gobierno Federal');
            $table->string('fissa', 8)->nullable()->default(null)->comment('Fecha Ingreso Secretaria De Salud');
            $table->string('freing', 8)->nullable()->default(null)->comment('Fecha De Reingreso');
            $table->string('tipomov', 4)->nullable()->default(null)->comment('Tipo De Movimiento');
            $table->string('fpago', 10)->nullable()->default(null)->comment('Fecha De Pago');
            $table->string('fpagoi', 8)->nullable()->default(null)->comment('Periodo Inicial Del Pago Del Producto');
            $table->string('fpagof', 8)->nullable()->default(null)->comment('Periodo Final De Pago Del Producto');
            $table->string('pqnai', 8)->nullable()->default(null)->comment('Periodo Inicial Del Pago De La Quincena');
            $table->string('pqnaf', 8)->nullable()->default(null)->comment('Periodo Final Del Pago De La Quincena');
            $table->string('qnareal', 2)->nullable()->default(null)->comment('Quincena Real Del Pago');
            $table->string('anioreal', 4)->nullable()->default(null)->comment('Año Real Del Pago');
            $table->integer('tipopago')->nullable()->default(null);
            $table->string('instrua', 2)->nullable()->default(null)->comment('Instrumeto De Pago Anterior');
            $table->string('instrun', 2)->nullable()->default(null)->comment('Instrumeto De Pago Nuevo');
            $table->decimal('per', 10, 2)->nullable()->default(null)->comment('Percepciones');
            $table->decimal('ded', 10, 2)->nullable()->default(null)->comment('Deducciones');
            $table->decimal('neto', 10, 2)->nullable()->default(null);
            $table->integer('notrail')->nullable()->default(null)->comment('Numero De Trailers');
            $table->integer('diaslab')->nullable()->default(null)->comment('Dias Laborados En La Quincena');
            $table->string('nomprod', 11)->nullable()->default(null)->comment('Nombre Del Producto');
            $table->integer('numctrol')->nullable()->default(null)->comment('Número De Control Del Sistema');
            $table->string('numcheq', 9)->nullable()->default(null)->comment('Numero De Cheque');
            $table->string('digver', 1)->nullable()->default(null)->comment('Digito Verificador');
            $table->integer('jornada')->nullable()->default(null)->comment('Jornada De Trabajo');
            $table->string('diasp', 2)->nullable()->default(null)->comment('Número De Dias De Prima Dominical');
            $table->string('ciclof', 5)->nullable()->default(null)->comment('Ciclo Del Fonac');
            $table->string('numaport', 2)->nullable()->default(null)->comment('Numero De Aportacionesl Fonac');
            $table->decimal('acumf', 10, 2)->nullable()->default(null)->comment('Acumulado De Fonac');
            $table->integer('faltas')->nullable()->default(null)->comment('Faltas En La Quincena');
            $table->string('clues', 12)->nullable()->default(null)->comment('Clave Unica De Establecimiento De Salud');
            $table->integer('porpen01')->nullable()->default(null)->comment('Porcentaje De Pension 01');
            $table->integer('porpen02')->nullable()->default(null)->comment('Porcentaje De Pension 02');
            $table->integer('porpen03')->nullable()->default(null)->comment('Porcentaje De Pension 03');
            $table->integer('porpen04')->nullable()->default(null)->comment('Porcentaje De Pension 04');
            $table->integer('porpen05')->nullable()->default(null)->comment('Porcentaje De Pension 05');
            $table->integer('issste')->nullable()->default(null)->comment('Tipo De Regimen De Pensión');
            $table->integer('tipouni')->nullable()->default(null)->comment('Tipo De Unidad');
            $table->string('crespdes', 100)->nullable()->default(null)->comment('Descripcion Del Centro De Responsabilidad');

            $table->index(["rfc"]);
            $table->index(["curp"]);
            $table->index(["nombre"]);
            $table->index(["ur"]);
            $table->index(["cr"]);
            $table->index(["fpago"]);
            $table->index(["fpagoi"]);
            $table->index(["fpagof"]);
            $table->index(["pqnai"]);
            $table->index(["pqnaf"]);
            $table->index(["qnareal"]);
            $table->index(["anioreal"]);
            $table->index(["per"]);
            $table->index(["ded"]);
            $table->index(["neto"]);
            $table->index(["notrail"]);
            $table->index(["clues"]);
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
