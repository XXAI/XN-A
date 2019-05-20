<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use \Excel;
use \Validator,\Hash, \Response, \DB;
use TRA, DAT;

use XBase\Table;

class CargarArchivosController extends Controller
{
    /**
     * Parsea la nomina enviada por el cliente, y devuelve un archivo excel con diferentes pestañas
     */
    public function cargarArchivoNomina(Request $request){
        ini_set('memory_limit', '-1');

        try{
            
            $archivo_tra = $request->file('archivo_tra');
            if($archivo_tra){
                if ($archivo_tra->isValid()){
                    
                    $datos_cargados_tra = $this->cargarDatosTRA($archivo_tra);

                    if(!$datos_cargados_tra['status']){
                        return response()->json($datos_cargados_tra, HttpResponse::HTTP_CONFLICT);
                    }
                }else{
                    return response()->json(['error'=>'Archivo TRA no valido'], HttpResponse::HTTP_CONFLICT);
                }
            }

            $archivo_dat = $request->file('archivo_dat');
            if($archivo_dat){
                if ($archivo_dat->isValid()){
                    
                    $datos_cargados_dat = $this->cargarDatosDAT($archivo_dat);

                    if(!$datos_cargados_dat['status']){
                        return response()->json($datos_cargados_dat, HttpResponse::HTTP_CONFLICT);
                    }
                }else{
                    return response()->json(['error'=>'Archivo DAT no valido'], HttpResponse::HTTP_CONFLICT);
                }
            }

            return response()->json(['archivos' => [$archivo_tra, $archivo_dat]], HttpResponse::HTTP_OK);

            //\App\TRA::where('nomina',$identificadores_nomina['clave'])->delete();
            /*$input_archivos_tra = ['archivo_tra','archivo_tra_ex'];
            for($i = 0; $i < count($input_archivos_tra); $i++){
                $archivo_tra = $request->file($input_archivos_tra[$i]);
                if($archivo_tra){
                    if ($archivo_tra->isValid()){
                        $tipo_nomina = 'ordinaria';
                        if($input_archivos_tra[$i] == 'archivo_tra_ex'){
                            $tipo_nomina = 'extraordinaria';
                        }
                        $datos_carga_tra[$input_archivos_tra[$i]] = $this->cargarDatosTRA($archivo_tra,$identificadores_nomina['clave'],$tipo_nomina);
    
                        if(!$datos_carga_tra[$input_archivos_tra[$i]]['status']){
                            return response()->json($datos_carga_tra, HttpResponse::HTTP_CONFLICT);
                        }
                    }else{
                        return response()->json(['error'=>'Archivo TRA no valido'], HttpResponse::HTTP_CONFLICT);
                    }
                }
            }*/
            
            return response()->json(['data' => 'sugoi'], HttpResponse::HTTP_OK);
            //return self::generarExcel($identificadores_nomina['clave'],$datos_archivo);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage(),'line'=>$e->getLine()], HttpResponse::HTTP_CONFLICT);
        }
    }

    public function cargarDatosTRA($archivo){
        try{
            $finfo = finfo_open(FILEINFO_MIME_TYPE); 
            
            $type = finfo_file($finfo, $archivo); 

            $fechahora = date("d").date("m").date("Y").date("H").date("i").date("s");

            $nombreArchivo = 'ARCHIVOTRA'.$fechahora;

            $idInsertado ='';
            $numeroRegistros = '';

            if($type == "text/plain"){//Si el Mime coincide con CSV
                $destinationPath = storage_path().'/archivostra/';
                $upload_success = $archivo->move($destinationPath, $nombreArchivo.".tra");
                $tra = $destinationPath . $nombreArchivo.".tra";

                DB::connection()->getPdo()->beginTransaction();

                $query = sprintf("
                    LOAD DATA local INFILE '%s' 
                    INTO TABLE trailers 
                    CHARACTER SET utf8 
                    FIELDS TERMINATED BY '|' 
                    OPTIONALLY ENCLOSED BY '\"' 
                    ESCAPED BY '\"' 
                    LINES TERMINATED BY '\\n' 
                    ", addslashes($tra));
                DB::connection()->getPdo()->exec($query);
                DB::connection()->getPdo()->commit();

                //SET nomina='%s', tipo_nomina='%s'
                //, $identificador_nomina, $tipo_nomina

                //$registros_tabla = \App\TRA::where('nomina',$identificador_nomina)->count();
                $registros_tabla = \App\TRA::count();

                return ['status'=>true, 'total_regitros_tabla'=>$registros_tabla];
            }
        }catch(\Exception $e){
            DB::connection()->getPdo()->rollback();
            return ['status'=>false, 'error' => $e->getMessage(), 'linea'=>$e->getLine()];
        }
    }

    public function cargarDatosDAT($archivo){
        try{
            $finfo = finfo_open(FILEINFO_MIME_TYPE); 
            
            $type = finfo_file($finfo, $archivo); 

            $fechahora = date("d").date("m").date("Y").date("H").date("i").date("s");

            $nombreArchivo = 'ARCHIVODAT'.$fechahora;

            $idInsertado ='';
            $numeroRegistros = '';

            if($type == "text/plain"){//Si el Mime coincide con CSV
                $destinationPath = storage_path().'/archivosdat/';
                $upload_success = $archivo->move($destinationPath, $nombreArchivo.".dat");
                $dat = $destinationPath . $nombreArchivo.".dat";

                DB::connection()->getPdo()->beginTransaction();

                $query = sprintf("
                    LOAD DATA local INFILE '%s' 
                    INTO TABLE datos 
                    CHARACTER SET utf8 
                    FIELDS TERMINATED BY '|' 
                    OPTIONALLY ENCLOSED BY '\"' 
                    ESCAPED BY '\"' 
                    LINES TERMINATED BY '\\n' 
                    ", addslashes($dat));
                DB::connection()->getPdo()->exec($query);
                DB::connection()->getPdo()->commit();

                //SET nomina='%s', tipo_nomina='%s'
                //, $identificador_nomina, $tipo_nomina

                //$registros_tabla = \App\TRA::where('nomina',$identificador_nomina)->count();
                $registros_tabla = \App\DAT::count();

                return ['status'=>true, 'total_regitros_tabla'=>$registros_tabla];
            }
        }catch(\Exception $e){
            DB::connection()->getPdo()->rollback();
            return ['status'=>false, 'error' => $e->getMessage(), 'linea'=>$e->getLine()];
        }
    }
}