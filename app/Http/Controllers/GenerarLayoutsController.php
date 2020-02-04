<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use \Excel;
use \Validator,\Hash, \Response, \DB, \DateTime;
use \App\Models\Trailer, \App\Models\Dato;
use \App\Models\CargaArchivo;

use XBase\Table;

class GenerarLayoutsController extends Controller{
    /**
     * Parsea la nomina enviada por el cliente, y devuelve un archivo excel con diferentes pestañas
     */
    public function generarPorBatch(Request $request, $batch){
        ini_set('memory_limit', '-1');
        include(app_path() . '\Includes\DirectoriesFunctions.php');

        try{
            $datos = Dato::with('desgloseDetalles')->
                            selectRaw(" datos.*,
                                        sum(IF(trailers.tconcep = 1,trailers.importe,0)) as calculado_percepciones, 
                                        sum(IF(trailers.tconcep > 1,trailers.importe,0)) as calculado_deducciones, 
                                        abs(datos.neto - (sum(IF(trailers.tconcep = 1,trailers.importe,0)) - sum(IF(trailers.tconcep > 1,trailers.importe,0)))) as diferencia_liquido")
                                        //datos.neto - (sum(IF(trailers.tconcep = 1,IF(trailers.importe >= 0,trailers.importe,0),0)) - sum(IF(trailers.tconcep > 1,IF(trailers.importe >= 0,trailers.importe,0),0))) as diferencia_liquido")
                            ->leftjoin('trailers',function($join){
                                $join->on('trailers.rfc','=','datos.rfc')->on('trailers.numctrol','=','datos.numctrol')->on('trailers.batch','=','datos.batch')->on('trailers.numcheq','=','datos.numcheq');
                            })
                            ->where('datos.batch',$batch)
                            ->groupBy('datos.rfc','datos.numcheq')
                            ->orderBy('diferencia_liquido','DESC')
                            ->take(100)
                            ->get();
            //return response()->json(['datos'=>$datos,'batch'=>$batch],HttpResponse::HTTP_OK);

            $path_layouts = storage_path().'/layouts/qna_prueba_layouts/';
            if(is_dir($path_layouts)){
                delete_files($path_layouts);
            }
            mkdir($path_layouts,0777, true);

            //$filepath = $Carpeta."/".$row_srcSQL["mmFolio"]."_".$row_srcSQL["CURP"].".txt";
            $folio_layout = 1;
            $serie = 'PRUEBA2019';
            $fecha_hora_generacion = date("Y-m-d")."T".date("H:i:s");
            
            for ($i=0; $i < count($datos); $i++) { 
                $dato = $datos[$i];

                $dato->nss = '000000000'; //Obtener número de seguro social

                $total_percepciones = 0; //$dato->calculado_percepciones;
                $total_otros_pagos = 0;
                $total_deducciones = 0; //$dato->calculado_deducciones;
                $ISR = 0;
                $percepcion_gravada = 0;
                $percepcion_no_gravada = 0;
                $tipo_nomina = (substr($dato->nomprod,0,4) == 'PRDO')?'O':'E';
                $periodicidad = ($tipo_nomina == 'O')?'04':'99';
                $dias = 15;
                //12:50
                /*
                    ##################################################----- Inicio: Se obtienen listado de percepciones y deducciones, asi como totales -----##################################################
                */
                $NPD=1;
                $lineas_perceptiones_npd = '';
                $lineas_deducciones_ndd  = '';
                $cfdi_tipo_perception = '---';  //Se necesita enlazar los catalogos
                $cfdi_tipo_deduccion = '---'; //Se necesita enlazar los catalogos
                foreach($dato->desgloseDetalles as $detalle){
                    if($detalle->tconcep == 1){
                        $lineas_perceptiones_npd .= "NPD|".($NPD++)."|".$cfdi_tipo_perception."|P" . $detalle->concepto . $detalle->ptaant."|".$detalle->descripcion_concepto."|".number_format($detalle->importe,2,".","")."|0"."\r".PHP_EOL;
                        $total_percepciones += $detalle->importe;
                    }else{
                        $lineas_deducciones_ndd .= "NDD|".$cfdi_tipo_deduccion."|D" . $detalle->concepto . $detalle->ptaant."|".$detalle->descripcion_concepto."|".number_format($detalle->importe,2,".","")."\r".PHP_EOL;
                        $total_deducciones += $detalle->importe;
                    }
                }
                
                $filepath = $path_layouts."/".$folio_layout."_".$dato->curp.".txt";
                $archivo_layout = fopen($filepath,"w");

                /* ##################################################----- Inicio: Construccion del layout -----##################################################  */
                
                $linea = "DC|3.3|$serie|$folio_layout|$fecha_hora_generacion|99|".number_format($total_percepciones + $total_otros_pagos,2,'.','')."|".number_format($total_deducciones,2,'.','')."|MXN||".number_format(($total_percepciones + $total_otros_pagos)-$total_deducciones,2,'.','')."|N|PUE|29010";
                fwrite($archivo_layout,$linea.PHP_EOL);

                $linea = "EM|ISA961203QN5|INSTITUTO DE SALUD";
                fwrite($archivo_layout,$linea.PHP_EOL);
                
                if($dato->parte_estatal > 0){ //No esta, obtener al igual que origen recurso no parecen estar en el dat
                    $linea="CNE|603||0||".$dato->origen_recurso."|".number_format($dato->parte_estatal,2,'.',''); //IF Federal, IP Propios, IM Mixtos
                    fwrite($archivo_layout,$linea.PHP_EOL);
                }else{
                    $linea="CNE|603||0||".$dato->origen_recurso."|";   //IF Federal, IP Propios, IM Mixtos
                    fwrite($archivo_layout,$linea.PHP_EOL);	
                }

                $linea = "RC|".$dato->rfc."|".$dato->nombre."|P01";
                fwrite($archivo_layout,$linea.PHP_EOL);
                
                $fecha_pago = substr($dato->fpagof,0,4)."-".substr($dato->fpagof,4,2)."-".substr($dato->fpagof,6,2);
                $fecha_ingreso = substr($dato->fissa,0,4)."-".substr($dato->fissa,4,2)."-".substr($dato->fissa,6,2);

                //Calcular semanas laboradas para antigüedad
                $datetime1 = new DateTime($fecha_ingreso);
                $datetime2 = new DateTime($fecha_pago);
                $interval = $datetime1->diff($datetime2);
                $antiguedad = "P".floor(($interval->format('%a') / 7)) . 'W';
                $dato->puesto_descripcion = $dato->puesto; //Obtener descripcion del puesto
                $dato->puesto_nivel_riesgo = '99'; //Obtener nivel de riesgo del puesto

                $linea = "CNR|$tipo_nomina|$fecha_pago|".(substr($dato->fpagoi,0,4)."-".substr($dato->fpagoi,4,2)."-".substr($dato->fpagoi,6,2))."|$fecha_pago|$dias|".($total_percepciones ? number_format($total_percepciones,2,'.','') : '')."|".number_format($total_deducciones,2,".","")."|"
                        .number_format($total_otros_pagos,2,".","")."|".$dato->curp."|".$dato->nss."|".$fecha_ingreso."|".$antiguedad."|01||".$dato->jornada."|02|".$dato->numemp."||".$dato->puesto_descripcion."|".$dato->puesto_nivel_riesgo."|".$periodicidad."||||".number_format($total_percepciones/15,2,'.','')."|CHP"."\r";
                fwrite($archivo_layout,$linea.PHP_EOL);
                
                if($total_deducciones>0){
                    $linea="CN|84111505|1|ACT|Pago de nómina|".number_format($total_percepciones+$total_otros_pagos,2,'.','')."|".number_format($total_percepciones+$total_otros_pagos,2,'.','')."|".number_format($total_deducciones,2,".","")."\r";
                    fwrite($archivo_layout,$linea.PHP_EOL);
                }else{
                    $linea="CN|84111505|1|ACT|Pago de nómina|".number_format($total_percepciones+$total_otros_pagos,2,'.','')."|".number_format($total_percepciones+$total_otros_pagos,2,'.','')."|\r";
                    fwrite($archivo_layout,$linea.PHP_EOL);
                }

                if($dato->observaciones && trim($dato->observaciones) != '' ){
                    $linea="OP|".$dato->observaciones."\r";
                    fwrite($archivo_layout,$linea.PHP_EOL);
                }

                if(($percepcion_gravada+$percepcion_no_gravada) >0){
                    $linea="CNP|".number_format($percepcion_gravada+$percepcion_no_gravada,2,'.','')."|||".number_format($percepcion_gravada,2,'.','')."|".number_format($percepcion_no_gravada,2,'.','')."|||||||||||\r";  //Pendiente
                    fwrite($archivo_layout,$linea.PHP_EOL);
                }

                //Datos de percecpiones generadas al princpio
                fwrite($archivo_layout,$lineas_perceptiones_npd);


                if($total_deducciones > 0)
                    $total_deducciones_menos_ISR = number_format($total_deducciones - $ISR,2,'.','');
                else
                    $total_deducciones_menos_ISR="";

                if(is_numeric($total_deducciones_menos_ISR)){
                    if($ISR > 0){
                        $linea="CND|".$total_deducciones_menos_ISR."|".number_format($ISR,2,'.','')."\r";
                        fwrite($archivo_layout,$linea.PHP_EOL);
                    }else{
                        $linea="CND|".$total_deducciones_menos_ISR."|\r";
                        fwrite($archivo_layout,$linea.PHP_EOL);
                    }
                }

                //Datos de percecpiones generadas al princpio
                fwrite($archivo_layout,$lineas_deducciones_ndd);
                
                /* ##################################################----- Fin: Construccion del layout -----##################################################  */
                fclose($archivo_layout);
                $folio_layout++;
            }
            
            return view('desglose_batch',['datos'=>$datos]);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage(),'line'=>$e->getLine()], HttpResponse::HTTP_CONFLICT);
        }
    }
}