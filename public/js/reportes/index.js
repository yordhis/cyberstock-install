let elementoReporteRapido = d.querySelectorAll('.reporte-rapido'),
formularios = d.forms;

/** MANEJADORES DE EVENTOS */
const hanledReporte = async (e) => {
    e.preventDefault();
    let config = {
        tipo: "",
        rango: {}
    },
    url = `${URL_BASE}/storeReportes`,
    fechaPersonalizada;
    log(e.target);

    switch (e.target.id) {
        case 'reporteDelDia':
                log('Reporte del dia GENERAL');
                config.tipo = e.target.id;
                fechaPersonalizada = new Date();
                config.rango = {
                    inicio: `${fechaPersonalizada.getFullYear()}-${fechaPersonalizada.getMonth()+1}-${fechaPersonalizada.getDate()}T${fechaPersonalizada.getHours()}:${fechaPersonalizada.getMinutes()}:${fechaPersonalizada.getSeconds()}`
                };

                /** ocultamos el boton y cargamos el spinner */
                e.target.style = "display:none;";
                e.target.parentElement.children[ e.target.parentElement.children.length - 1].innerHTML= spinner;

                /** Obtenemos la data del reporte segun el rango */
                dataReporte = await storeReportes(url, config);
                empresa = await getEmpresa();

                /** validamos si hay datos */
                if(empresa.estatus == 401){
                    e.target.parentElement.children[2].innerHTML= componenteAlerta(empresa.mensaje, empresa.estatus);
                    return setTimeout(()=>{
                        e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= "";
                        e.target.style = "display:greed;";
                    }, 5000);
                }
                if(dataReporte.data.length){
                    imprimirElemento(reporteHtmlGeneral(dataReporte.data, empresa.data, config))

                    e.target.style = "display:greed;";
                    e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= '';
                }else{
                    e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= componenteAlerta("No hay ventas registradas", 404);
                    setTimeout(()=>{
                        e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= "";
                        e.target.style = "display:greed;";
                    }, 2500);
                }
            break;
        case 'reporteDelDiaDetallado':
                log('Reporte del dia detallado');
                config.tipo = e.target.id;
                fechaPersonalizada = new Date();
                config.rango = {
                    inicio: `${fechaPersonalizada.getFullYear()}-${fechaPersonalizada.getMonth()+1}-${fechaPersonalizada.getDate()}T${fechaPersonalizada.getHours()}:${fechaPersonalizada.getMinutes()}:${fechaPersonalizada.getSeconds()}`
                };

                /** ocultamos el boton y cargamos el spinner */
                e.target.style = "display:none;";
                e.target.parentElement.children[ e.target.parentElement.children.length - 1].innerHTML= spinner;

                /** Obtenemos la data del reporte segun el rango */
                dataReporte = await storeReportes(url, config);
                empresa = await getEmpresa();

                /** validamos si hay datos */
                if(empresa.estatus == 401){
                    e.target.parentElement.children[2].innerHTML= componenteAlerta(empresa.mensaje, empresa.estatus);
                    return setTimeout(()=>{
                        e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= "";
                        e.target.style = "display:greed;";
                    }, 5000);
                }
                if(dataReporte.data.length){
                    imprimirElemento(reporteHtml(dataReporte.data, empresa.data, config))

                    e.target.style = "display:greed;";
                    e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= '';
                }else{
                    e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= componenteAlerta("No hay ventas registradas", 404);
                    setTimeout(()=>{
                        e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= "";
                        e.target.style = "display:greed;";
                    }, 2500);
                }
            break;
        case 'storeReportes':
            log('storeReporte personalizado')
            /** Validación del formulario */
            config.rango = await validarDataDeFormulario(e.target);

            /** verificación de tipo de reporte a generar */
            if(config.rango.tipoDeReporte == "DETALLADO") config.tipo = 'storeReportesDetallado';
            else config.tipo = e.target.id;
            
            /** Detenemos la ejecución por falta de rango de fecha */
            if(config.rango == false) return;

            /** validacion de fecha si la de inicio es menor que la de fin */
            resultadoValidacionFecha = await validarFecha(config.rango.inicio, config.rango.fin);
            if(resultadoValidacionFecha) return e.target.parentElement.children[2].textContent = `${resultadoValidacionFecha}`;
            else e.target.parentElement.children[2].textContent = "";
            
            /** Obtenemos la data del reporte segun el rango */
            dataReporte = await storeReportes(url, config);
            empresa = await getEmpresa();
            log(dataReporte);

            /** ocultamos el boton y cargamos el spinner */
            e.target.style = "display:none;";
            e.target.parentElement.children[ e.target.parentElement.children.length - 1].innerHTML= spinner;

              /** validamos si hay datos */
              if(empresa.estatus == 401){
                  e.target.parentElement.children[2].innerHTML= componenteAlerta(empresa.mensaje, empresa.estatus);
                  return setTimeout(()=>{
                      e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= "";
                      e.target.style = "display:greed;";
                  }, 5000);
              }
              if(dataReporte.data.length){
                if(config.rango.tipoDeReporte == "DETALLADO") imprimirElemento(reportePorRangoDetalladolHtml(dataReporte.data, empresa.data, config));
                else imprimirElemento(reportePorRangoGeneralHtml(dataReporte.data, empresa.data, config));;
                
                  e.target.style = "display:greed;";
                  e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= '';
              }else{
                  e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= componenteAlerta("No hay ventas registradas", 404);
                  setTimeout(()=>{
                      e.target.parentElement.children[ e.target.parentElement.children.length - 1 ].innerHTML= "";
                      e.target.style = "display:greed;";
                  }, 2500);
              }
            break;
    
        default:
            break;
    }
};

/** EVENTOS */
elementoReporteRapido.forEach(elemento => {
    elemento.addEventListener('click', hanledReporte);
})

// log(formularios[0]);
// formularios[0][2].addEventListener('submit', hanledReporte);
for (const formulario of formularios) {
    if(formulario.id == "storeReportes") formulario.addEventListener('submit', hanledReporte);
}


/** UTILIDADES Y ADAPTADORES */

/** Validar formulario reportes y retorna la data si pasa la validación */
async function validarDataDeFormulario(formulario){
    let esquema = {},
    banderaDeALertar = 0;
    for (const iterator of formulario) {
        if(iterator.localName == "input" || iterator.localName == "select"){
            if(!iterator.value.length) iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            else iterator.classList.remove(['border-danger']), iterator.nextElementSibling.textContent=``, iterator.classList.add(['border-success']);
            if(iterator.value == "") iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            // Asignamos el valor al esquema
            esquema[iterator.name] = iterator.value;
        }
    }
    log(esquema);
    if(banderaDeALertar) return false;
    else return esquema;
}

async function validarFecha(inicio, fin){
    inicio = inicio.replaceAll('-', ',');
    fin = fin.replaceAll('-', ',');
    f1 = new Date(inicio);
    f2 = new Date(fin);
    f3 = new Date();
    if(f1 > f2) return "La fecha de | Inicio | no puede sobrepasar la de | Cierre |.";
    if(f2 > f3) return "La fecha de | Cierre | no puede mayor a la fecha | Actual |.";
    return false;
};