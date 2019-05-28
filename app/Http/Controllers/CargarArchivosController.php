<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

use Illuminate\Support\Facades\Storage;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use \Excel;
use \Validator,\Hash, \Response, \DB;
use \App\TRA, \App\DAT;
use \App\Models\CargaArchivo;

use XBase\Table;

class CargarArchivosController extends Controller
{
    /**
     * Parsea la nomina enviada por el cliente, y devuelve un archivo excel con diferentes pestañas
     */
    public function cargarArchivoNomina(Request $request){
        ini_set('memory_limit', '-1');

        try{
            $contador_archivos = 0;
            $destinationPath = storage_path().'/archivos_subidos/';
            $nombre_archivo_tra = '';
            $nombre_archivo_dat = '';
            
            $archivo_tra = $request->file('archivo_tra');
            if($archivo_tra){
                $contador_archivos++;
                if (!$archivo_tra->isValid()){
                    return response()->json(['error'=>'Archivo TRA no valido'], HttpResponse::HTTP_CONFLICT);
                }
            }

            $archivo_dat = $request->file('archivo_dat');
            if($archivo_dat){
                $contador_archivos++;
                if (!$archivo_dat->isValid()){
                    return response()->json(['error'=>'Archivo DAT no valido'], HttpResponse::HTTP_CONFLICT);
                }
            }

            if($contador_archivos == 0){
                return response()->json(['error'=>'No se encontro ningun archivo'], HttpResponse::HTTP_CONFLICT);
            }
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fechahora = date("d").date("m").date("Y").date("H").date("i").date("s");

            DB::connection()->getPdo()->beginTransaction();
            $carga_archivos = CargaArchivo::create(['nombre_archivo_tra'=>$archivo_tra->getClientOriginalName(),'nombre_archivo_dat'=>$archivo_dat->getClientOriginalName(),'qnareal'=>$request->get('qna'),'anioreal'=>$request->get('anio'),'nomprod'=>$request->get('nom_prod')]);
            
            /*===================================================================  Comienza carga del archivo TRA  ==============================================================================*/
            $type = finfo_file($finfo, $archivo_tra); 
            $nombre_archivo_tra = 'ARCHIVOTRA'.$fechahora;

            if($type == "text/plain"){//Si el Mime coincide con CSV
                $upload_success = $archivo_tra->move($destinationPath, $nombre_archivo_tra.".tra");
                $tra = $destinationPath . $nombre_archivo_tra.".tra";

                $query = sprintf("
                    LOAD DATA local INFILE '%s' 
                    INTO TABLE trailers 
                    CHARACTER SET latin1 
                    FIELDS TERMINATED BY '|' 
                    OPTIONALLY ENCLOSED BY '\"' 
                    ESCAPED BY '\"' 
                    LINES TERMINATED BY '\\n' 
                    SET batch = %d, qnareal = '%s', anioreal = '%s'
                    ", addslashes($tra),$carga_archivos->batch, $carga_archivos->qnareal, $carga_archivos->anioreal);
                DB::connection()->getPdo()->exec($query);

                $total_datos_tra = TRA::where('batch',$carga_archivos->batch)->count();
            }else{
                throw new \Exception("El archivo TRA no es texto plano", 1);
            }
            unlink($destinationPath . $nombre_archivo_tra.".tra");
            /*===================================================================  Termina carga del archivo TRA  ==============================================================================*/

            /*===================================================================  Comienza carga del archivo DAT  ==============================================================================*/
            $type = finfo_file($finfo, $archivo_dat); 
            $nombre_archivo_dat = 'ARCHIVODAT'.$fechahora;

            if($type == "text/plain"){//Si el Mime coincide con CSV
                $upload_success = $archivo_dat->move($destinationPath, $nombre_archivo_dat.".dat");
                $dat = $destinationPath . $nombre_archivo_dat.".dat";

                $query = sprintf("
                    LOAD DATA local INFILE '%s' 
                    INTO TABLE datos 
                    CHARACTER SET latin1 
                    FIELDS TERMINATED BY '|' 
                    OPTIONALLY ENCLOSED BY '\"' 
                    ESCAPED BY '\"' 
                    LINES TERMINATED BY '\\n' 
                    SET batch = %d
                    ", addslashes($dat),$carga_archivos->batch);
                DB::connection()->getPdo()->exec($query);

                $total_datos_dat = DAT::where('batch',$carga_archivos->batch)->count();
            }else{
                throw new \Exception("El archivo DAT no es texto plano", 1);
            }
            unlink($destinationPath . $nombre_archivo_dat.".dat");
            /*===================================================================  Termina carga del archivo DAT  ==============================================================================*/

            DB::connection()->getPdo()->commit();
            //return response()->json(['archivos' => [$archivo_tra, $archivo_dat]], HttpResponse::HTTP_OK);
            return response()->json(['data' => 'sugoi', 'total_tra'=>$total_datos_tra, 'total_dat'=>$total_datos_dat, 'batch'=>$carga_archivos->batch], HttpResponse::HTTP_OK);
            //return self::generarExcel($identificadores_nomina['clave'],$datos_archivo);
        }catch(\Exception $e){
            DB::connection()->getPdo()->rollback();
            if($nombre_archivo_tra != ''){
                unlink($destinationPath . $nombre_archivo_tra.".tra");
            }
            if($nombre_archivo_dat != ''){
                unlink($destinationPath . $nombre_archivo_dat.".dat");
            }
            return response()->json(['error' => $e->getMessage(),'line'=>$e->getLine()], HttpResponse::HTTP_CONFLICT);
        }
    }
    /*
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
                    CHARACTER SET latin1 
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
                    CHARACTER SET latin1 
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
    */
}