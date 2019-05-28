<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>
        <!-- Fonts -->
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
    </head>
    <body>
        <div>
            <div class="jumbotron">
                <h1 class="display-8">Cargar - Archivos</h1>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col">
                        <form id="formulario_nomina" action="{{url('api/cargar_archivos')}}" target="_blank" method="post" enctype="multipart/form-data" >
                            <div class="form-row">
                                <div class="form-group col-2"> <label>Quincena</label> <input class="form-control" type="text" name="qna" value="01"/> </div>
                                <div class="form-group col-2"> <label>Año</label> <input class="form-control" type="text" name="anio" value="{{date('Y')}}"/> </div>
                                <!--div class="form-group col-4"> 
                                    <label>Nomina</label> 
                                    <select name="identificador_nomina" class="form-control">
                                        <option value='sin_id' selected="selected">Seleccione una opcion </option>
                                        <option value='federalizados' >     Federalizados       </option>
                                        <option value='unidades_medicas' >  Unidades Medicas    </option>
                                        <option value='homologados' >       Homologados         </option>
                                        <option value='mandos_medios' >     Mandos Medios       </option>
                                        <option value='pac' >               PAC                 </option>
                                        <option value='san_agustin' >       San Agustin         </option>
                                    </select>
                                </div-->
                                <div class="form-group col-4"> 
                                    <label>Tipo</label> 
                                    <select name="nom_prod" class="form-control">
                                        <option value='sin_id' selected="selected">Seleccione una opción </option>
                                        <option value='PRDO' >          Ordinario                       </option>
                                        <option value='PRDP' >          Pensión                         </option>
                                        <option value='PRDE' >          Extraordinario                  </option>
                                        <option value='PRDR' >          Retroactivo                     </option>
                                        <option value='PRDDR' >         Dia de Reyes                    </option>
                                        <option value='PRDPDR' >        Pensión Dia de Reyes            </option>
                                        <option value='PRDDM' >         Dia de las Madres               </option>
                                        <option value='PRDPDM' >        Pensión Dia de las Madres       </option>
                                        <option value='PRDPV' >         Prima Vacacional                </option>
                                        <option value='PRDPPV' >        Pensión Prima Vacacional        </option>
                                        <option value='PRDPD' >         Prima Dominical                 </option>
                                        <option value='PRDPPD' >        Pensión Prima Dominical         </option>
                                        <option value='PRDAG' >         Aguinaldo                       </option>
                                        <option value='PRDPAG' >        Pensión Aguinaldo               </option>
                                        <option value='PRDTS' >         Trabajador de la Salud          </option>
                                        <option value='PRDPTS' >        Pensión Trabajador de la Salud  </option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-6"> <label>Archivo TRA</label>         <input class="form-control-file" type="file" name="archivo_tra" accept=".tra"/>       </div>
                                <div class="form-group col-6"> <label>Archivo DAT</label>         <input class="form-control-file" type="file" name="archivo_dat" accept=".dat"/>       </div>
                            </div>
                            <hr/>
                            <div class="form-row">
                                <div class="col-8"></div>
                                <div class="col-2">
                                    <button class="btn btn-default" type="reset">Limpiar Formulario</button>
                                </div>
                                <div class="col-2">
                                    <button class="btn btn-primary" type="submit">Cargar Archivo</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <br>
        </div>
        <script type="text/javascript">
            /*var myForm = document.getElementById('formulario_nomina');
            myForm.onsubmit = function() {
                var w = window.open('about:blank','Popup_Window','toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=400,height=300,left = 312,top = 234');
                this.target = 'Popup_Window';
            };*/
        </script>
    </body>
</html>