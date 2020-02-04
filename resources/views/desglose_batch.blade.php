<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{asset('fontawesome/css/all.min.css')}}">
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
        <script src="{{asset('bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('js/config-app.js')}}"></script>
        <script src="{{asset('js/modules/desglose_batch.js')}}"></script>
    </head>
    <body>
        <div>
            <div class="card">
                <div class="card-header">
                    Datos Cargados del Batch: {{$batch}}
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <input type="text" class="form-control" id="buscar" name="buscar" placeholder="Buscar" onkeydown="buscarPalabra(event)">
                            <input type="hidden" id="batch" name="batch" value="{{$batch}}" />
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary mb-2" name="btn-buscar" onclick="buscar();"><i class="fas fa-search"></i></button>
                        </div>
                    </div>
                </div>
                <table class="table table-sm">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">Detalles</th>
                            <th scope="col">RFC</th>
                            <th scope="col">Nombre</th>
                            <th scope="col">No. Cheque</th>
                            <th scope="col">Percepciones</th>
                            <th scope="col">Deducciones</th>
                            <th scope="col">Liquido</th>
                        </tr>
                    </thead>
                    <tbody id="lista_registros">
                        @foreach($datos as $dato)
                        <tr>
                            <th scope="row">
                                @if($dato->desgloseDetalles)
                                <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#detalles_{{$dato->llave}}" aria-expanded="false" aria-controls="detalles_{{$dato->llave}}">
                                    Ver
                                </button>
                                @endif
                            </th>
                            <td>{{ $dato->rfc }}</td>
                            <td>{{ $dato->nombre }}</td>
                            <td>{{ $dato->numcheq }}</td>
                            <td class="{{ (($dato->percepciones - $dato->calculado_percepciones) != 0)?'text-danger':'' }}">{{ $dato->percepciones }} <small>{{(($dato->percepciones - $dato->calculado_percepciones) != 0)?"[".($dato->percepciones - $dato->calculado_percepciones)."]":''}}</small></td>
                            <td class="{{ (($dato->deducciones - $dato->calculado_deducciones) != 0)?'text-danger':'' }}">{{ $dato->deducciones }} <small>{{(($dato->deducciones - $dato->calculado_deducciones) != 0)?"[".($dato->deducciones - $dato->calculado_deducciones)."]":''}}</small></td>
                            <td class="{{ (($dato->diferencia_liquido) != 0)?'text-danger':'' }}">{{ $dato->liquido }} <small>{{(($dato->diferencia_liquido) != 0)?"[".($dato->diferencia_liquido)."]":''}}</small></td>
                        </tr>
                        @if($dato->desgloseDetalles)
                        <tr>
                            <td colspan="7" style="padding:0px;">
                                <div class="collapse" id="detalles_{{$dato->llave}}">
                                    <table class="table table-hover table-sm" style="font-size:small;">
                                        <thead>
                                            <tr>
                                                <th scope="col">RFC</th>
                                                <th scope="col">No. Cheque</th>
                                                <th scope="col">Concepto</th>
                                                <th scope="col">Descripción</th>
                                                <th scope="col">Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($dato->desgloseDetalles as $detalle)
                                            <tr class="{{($detalle->tconcep == 1)?'text-success':'text-danger'}}" style="{{($detalle->importe < 0)?'font-weight:bold':''}}">
                                                <td>{{ $detalle->rfc }}</td>
                                                <td>{{ $detalle->numcheq }}</td>
                                                <td>{{ '['.$detalle->tconcep.']'.(($detalle->tconcep == 1)?'P':'D') . $detalle->concepto . $detalle->ptaant }}</td>
                                                <td>{{ ($detalle->descripcion_cfdi)?$detalle->descripcion_cfdi:"-".$detalle->descripcion_concepto."-" }}</td>
                                                <td>{{ $detalle->importe }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-md-2">
                            Total: <span id="total_registros">0</span>
                        </div> 
                        <div class="offset-md-6 col-md-4">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-info boton_primero_anterior" onclick="cargarPagina('primera')"><i class="fas fa-angle-double-left"></i></button>
                                <button type="button" class="btn btn-info boton_primero_anterior" onclick="cargarPagina('anterior')"><i class="fas fa-angle-left"></i></button>
                                <input type="text" class="form-control text-center" placeholder="Página" value="1" id="pagina_actual" onkeydown="if(event.key == 'Enter'){cargarPagina();}">
                                <input type="hidden" value="1" id="total_paginas">
                                <button type="button" class="btn btn-info boton_ultimo_siguiente" onclick="cargarPagina('siguiente')"><i class="fas fa-angle-right"></i></button>
                                <button type="button" class="btn btn-info boton_ultimo_siguiente"  onclick="cargarPagina('ultima')"><i class="fas fa-angle-double-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        </div>
    </body>
</html>