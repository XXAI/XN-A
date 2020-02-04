function buscar(event = null){
    if(event){
        event.preventDefault();
    }
    $('#pagina_actual').val('1');
    actualizarRegistros();
}

function actualizarRegistros(){
    var parametros = {
        'buscar': $('#buscar').val(),
        'batch': $('#batch').val(),
        'page': $('#pagina_actual').val()
    };

    $.get(api_url+'batch', parametros, function(data){
        console.log(data);
        var registros = "";
        for(var i in data['paginado'].data){
            var elemento = data['paginado'].data[i];
            
            registros += "<tr><th scope='row'>";
            if(elemento.desglose_detalles){
                registros += "<button class='btn btn-primary' type='button' data-toggle='collapse' data-target='#detalles_"+elemento.llave+"' aria-expanded='false' aria-controls='detalles_"+elemento.llave+"'>Ver</button>";
            }
            registros += "</th><td>"+elemento.rfc+"</td><td>"+elemento.nombre+"</td><td>"+elemento.numcheq+"</td>";

            var diferencia_percepciones = elemento.percepciones - elemento.calculado_percepciones;
            var diferencia_deducciones = elemento.deducciones - elemento.calculado_deducciones;

            registros += "<td class='"+((diferencia_percepciones != 0)?'text-danger':'')+"'>"+elemento.percepciones+" <small>"+((diferencia_percepciones != 0)?'['+diferencia_percepciones+']':'')+"</small></td>";
            registros += "<td class='"+((diferencia_deducciones != 0)?'text-danger':'')+"'>"+elemento.deducciones+" <small>"+((diferencia_deducciones != 0)?'['+diferencia_deducciones+']':'')+"</small></td>";
            registros += "<td class='"+((elemento.diferencia_liquido != 0)?'text-danger':'')+"'>"+elemento.liquido+" <small>"+((elemento.diferencia_liquido != 0)?'['+(elemento.diferencia_liquido)+']':'')+"</small></td></tr>";

            if(elemento.desglose_detalles){
                registros += "<tr><td colspan='7' style='padding:0px;'><div class='collapse' id='detalles_"+elemento.llave+"'>";
                registros += "<table class='table table-hover table-sm' style='font-size:small;'><thead><tr><th scope='col'>RFC</th><th scope='col'>No. Cheque</th><th scope='col'>Concepto</th><th scope='col'>Descripción</th><th scope='col'>Importe</th></tr></thead><tbody>";

                for(var i in elemento.desglose_detalles){
                    var detalle = elemento.desglose_detalles[i];

                    registros += "<tr class='"+((detalle.tconcep == 1)?'text-success':'text-danger')+"' style='"+((detalle.importe < 0)?'font-weight:bold':'')+"'>";
                    registros += "<td>"+detalle.rfc+"</td><td>"+detalle.numcheq+"</td>"
                    registros += "<td>["+detalle.tconcep+"]"+((detalle.tconcep == 1)?'P':'D')+detalle.concepto+detalle.ptaant+"</td>"
                    registros += "<td>"+((detalle.descripcion_cfdi)?detalle.descripcion_cfdi:"-"+detalle.descripcion_concepto+"-")+"</td>"
                    registros += "<td>"+detalle.importe +"</td>"
                    registros += "</tr>";
                }
                
                registros += "</tbody></table></div></td></tr>";
            }
        }
        $('#lista_registros').html(registros);

        $('#total_paginas').val(data['paginado'].last_page);
        $('#total_registros').text(data['paginado'].total);

        actualizarPaginador();
    });
}

function buscarPalabra(event){
    if(event.key == 'Enter'){
        event.preventDefault();
        buscar();
    }
}

function actualizarPaginador(){
    var pag_actual = $('#pagina_actual').val();
    var total_paginas = $('#total_paginas').val();

    if(pag_actual == 1){
        $('.boton_primero_anterior').attr('disabled',true);
        $('.boton_primero_anterior').addClass('disabled');
    }else{
        $('.boton_primero_anterior').attr('disabled',false);
        $('.boton_primero_anterior').removeClass('disabled');
    }

    if(pag_actual == total_paginas){
        $('.boton_ultimo_siguiente').attr('disabled',true);
        $('.boton_ultimo_siguiente').addClass('disabled');
    }else{
        $('.boton_ultimo_siguiente').attr('disabled',false);
        $('.boton_ultimo_siguiente').removeClass('disabled');
    }
}

function cargarPagina(pagina=''){
    var cargar_pagina = $('#pagina_actual').val();
    var total_paginas = $('#total_paginas').val();
    switch (pagina) {
        case 'siguiente':
            if(cargar_pagina < total_paginas){
                cargar_pagina++;
            }
            break;
        case 'anterior':
            if(cargar_pagina > 1){
                cargar_pagina--;
            }
            break;
        case 'primera':
            cargar_pagina = 1;
                break;
        case 'ultima':
            cargar_pagina = total_paginas;
            break;
        default:
            if(cargar_pagina > total_paginas){
                cargar_pagina = total_paginas;
            }else if(cargar_pagina < 0){
                cargar_pagina = 1;
            }
            break;
    }
    $('#pagina_actual').val(cargar_pagina);
    actualizarRegistros();
}

window.onload = function () { 
    buscar();
}