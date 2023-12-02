let elementoReporteRapido = d.querySelectorAll('.reporte-rapido'),
formularios = d.forms;

/** MANEJADORES DE EVENTOS */
const hanledReporteRapido = async (e) => {
    e.preventDefault();
    let config = {
        tipo: "",
        rango: {}
    },
    url = `${URL_BASE}/storeReportes`;
    log(e.target);

    switch (e.target.id) {
        case 'reporteDelDia':
            log('Reporte del dia')
            
            break;
        case 'reporteSemanal':
            log('Reporte semanal')
            
            break;
        case 'reporteDelMes':
            log('Reporte mensual')
            
            break;
    
        case 'storeReportes':
            log('storeReporte personalizado')
            config.tipo = "personalizado";
            config.rango = await validarDataDeFormulario(e.target);
            if(config.rango == false) return;
            resultadoValidacionFecha = await validarFecha(config.rango.inicio, config.rango.fin);
            if(resultadoValidacionFecha) return e.target.parentElement.children[2].textContent = `${resultadoValidacionFecha}`;
            else e.target.parentElement.children[2].textContent = "";
         
            // Obtenemos la data del reporte segun el rango
            dataReporte = await storeReportes(url, config);
            log(dataReporte);
            break;
    
        default:
            break;
    }
};

/** EVENTOS */
elementoReporteRapido.forEach(elemento => {
    elemento.addEventListener('click', hanledReporteRapido);
})

// log(formularios[0]);
// formularios[0][2].addEventListener('submit', hanledReporteRapido);
for (const formulario of formularios) {
    if(formulario.id == "storeReportes") formulario.addEventListener('submit', hanledReporteRapido);
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
            if(iterator.value == "Tipo de documento") iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            // Asignamos el valor al esquema
            esquema[iterator.name] = iterator.value;
        }
    }
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