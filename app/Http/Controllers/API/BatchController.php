<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\Response as HttpResponse;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Support\Facades\Input;
use \Response, \DB;
use \App\Models\Trailer, \App\Models\Dato, \App\Models\CargaArchivo;

class BatchController extends Controller{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        ini_set('memory_limit', '-1');
        
        $parametros = $request->all();

        try{
            $batch = $parametros['batch'];

            $datos = Dato::with('desgloseDetalles')->
                            selectRaw(" datos.llave, datos.batch, datos.numcheq, datos.rfc, datos.nombre, datos.per as percepciones, datos.ded as deducciones, datos.neto as liquido, datos.nomprod, datos.numctrol, 
                                        sum(IF(trailers.tconcep = 1,trailers.importe,0)) as calculado_percepciones, 
                                        sum(IF(trailers.tconcep > 1,trailers.importe,0)) as calculado_deducciones, 
                                        abs(datos.neto - (sum(IF(trailers.tconcep = 1,trailers.importe,0)) - sum(IF(trailers.tconcep > 1,trailers.importe,0)))) as diferencia_liquido")
                                        //datos.neto - (sum(IF(trailers.tconcep = 1,IF(trailers.importe >= 0,trailers.importe,0),0)) - sum(IF(trailers.tconcep > 1,IF(trailers.importe >= 0,trailers.importe,0),0))) as diferencia_liquido")
                            ->leftjoin('trailers',function($join){
                                $join->on('trailers.rfc','=','datos.rfc')->on('trailers.numctrol','=','datos.numctrol')->on('trailers.batch','=','datos.batch')->on('trailers.numcheq','=','datos.numcheq');
                            })
                            ->where('datos.batch',$batch)
                            ->groupBy('datos.rfc','datos.numcheq')
                            ->orderBy('diferencia_liquido','DESC');
                            //->take(100)
                            //->get();

            if(isset($parametros['buscar']) && $parametros['buscar']){
                $datos = $datos->where(function($query) use ($parametros){
                    $query = $query->where('datos.curp','like','%'.$parametros['buscar'].'%')
                                    ->orWhere('datos.rfc','like','%'.$parametros['buscar'].'%')
                                    ->orWhere('datos.numemp','like','%'.$parametros['buscar'].'%')
                                    ->orWhere('datos.nombre','like','%'.$parametros['buscar'].'%')
                                    ->orWhere('datos.numcheq','like','%'.$parametros['buscar'].'%')
                                    ->orWhere('datos.clues','like','%'.$parametros['buscar'].'%');
                });
            }

            if(isset($parametros['page'])){
                $resultadosPorPagina = isset($parametros["per_page"])? $parametros["per_page"] : 25;
                $datos = $datos->paginate($resultadosPorPagina);
            } else {
                $datos = $datos->get();
            }

            return response()->json(['paginado'=>$datos,'batch'=>$batch],HttpResponse::HTTP_OK);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage(),'line'=>$e->getLine()], HttpResponse::HTTP_CONFLICT);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        //
    }
}
