
/** VARIABLES */
let elementoTablaCuerpo = d.querySelector('#lista'),
elementoAlert = d.querySelector('#alert'),
elementoBarraDePorcentaje = d.querySelector('#barraDePorcentaje'),
elementoInputFiltroDescripcion = d.querySelector('#filtro-descripcion'),
elementoInputFiltroCodigo = d.querySelector('#filtro-codigo'),
elementoInputFiltroLimpiar = d.querySelector('#filtro-limpiar'),
elementoSpanInvalido = d.querySelectorAll('.invalido'),
elementoTablaPaginacion = d.querySelector('.paginacion'),
elementoBotonResetearFiltro = d.querySelector('#limpiarFiltro'),
formularios = d.forms,
inventarios = {},
inventarioAdaptado= [],
config = {
    href: ""
};



/** COMPONENTES */
const componenteFila = (data) => {

    if (data.estatus == 0) {
        return `
        <tr>
            <td colspan="14" class="text-center text-danger ">NO HAY RESULTADOS</td>
        </tr>
        `;
    } else {
        return `
            <tr>
                <td>${data.numero}</td>
                <td>${data.codigo}</td>
                <td>${data.descripcion}</td>
                <td>${data.marca}</td>
                <td>${data.categoria}</td>
                
                <td class="table-danger"><b> ${darFormatoDeNumero(data.costo)} </b></td>
                <td>${darFormatoDeNumero(data.pvp)}</td>
                <td>${darFormatoDeNumero(data.pvp_2)}</td>
                <td>${darFormatoDeNumero(data.pvp_3)}</td>
                
                <!-- COSTO DESPUES -->
                <td class="table-danger" id="${data.codigo}">
                    <input type="number" step="0.01" value="${data.costo_despues ? darFormatoDeNumero(data.costo_despues) : 0 }" 
                    class="form-control form-control-lg costo_despues" id="costo_despues">
                </td>

                <!-- PVP DETAL DESPUES -->
                <td class="table-warning" id="${data.codigo}">
                    <input type="number" step="0.01" value="${data.pvp_despues ? darFormatoDeNumero(data.pvp_despues) : 0 }" 
                    class="form-control form-control-lg pvp_despues" id="pvp_despues">
                </td>

                <!-- PVP 2 DESPUES -->
                <td class="table-warning" id="${data.codigo}">
                    <input type="number" step="0.01" value="${data.pvp_2_despues ? darFormatoDeNumero(data.pvp_2_despues) : 0 }" 
                    class="form-control form-control-lg pvp_2_despues" id="pvp_2_despues">
                </td>

                <td class="table-warning"  id="${data.codigo}">
                    <input type="number" step="0.01" value="${data.pvp_3_despues ? darFormatoDeNumero(data.pvp_3_despues) : 0 }" 
                    class="form-control form-control-lg pvp_3_despues" id="pvp_3_despues">
               
                </td>
            </tr>
        `;
    }
};

const componentePaginacion = (data) => {
    let itemsLi = '';
    // console.log(data.data.links);
    if(data.data.links.length){
        data.data.links.forEach(link => {
            if (link.label.includes('Anterior')) {
                itemsLi += ` 
                    <li class="page-item ${link.url ? '' : 'disabled'}">
                        <a class="page-link" href="${link.url}" >${link.label}</a>
                    </li>
                `;
            } else if (link.label.includes('Siguiente')) {
                itemsLi += ` 
                    <li class="page-item ${link.url ? '' : 'disabled'}">
                        <a class="page-link" href="${link.url}" >${link.label}</a>
                    </li>
                `;
            }else{
                itemsLi += ` 
                    <li class="page-item ${link.active ? 'active' : ''}">
                        <a class="page-link" href="${link.url}" >${link.label}</a>
                    </li>
                `;
            }
        });
    }

    return `
        <ul class="pagination justify-content-center">
            ${itemsLi}
        </ul>
    `;
};


/** MANEJADORES DE EVENTO */
const hanledLoad = async (e) => {};

const hanledPaginacion = async (e) => {
    e.preventDefault();
    if(e.target.href){
        if(e.target.href.includes('getInventariosFiltro')){
            // elementoTablaPaginacion.innerHTML = '';
            // elementoTablaCuerpo.innerHTML = spinner();
            config.href = e.target.href;
            await getLista(config,  e.target.href);

        }else{
            console.log('no entro en la paginacion normal');
            console.log(e.target.href);
            elementoTablaPaginacion.innerHTML = '';
            await getLista(e.target.href);
        }
    }else console.log('no hay dirección a donde paginar');
};

const hanledFormulario = async (e) => {
    if(e.target.id != "cerrarSesion"){
        e.preventDefault();
        // console.log(e.target);
        switch (e.target.id) {
            case 'formularioFiltro':
                let banderaDeALertarConfig = 0;
                inventarioAdaptado = [];

                for (const iterator of e.target) {
                    if(iterator.localName == "button") iterator.disabled=true;
                    if(iterator.localName == "input" || iterator.localName == "select"){
                        if(iterator.value == "CATEGORIAS" || iterator.value == "MARCAS")  config[iterator.name] = 0;
                        else  config[iterator.name] = iterator.value;
                    }
                }

                // console.log(config);
                /** Configuramos el porcentaje a formato 1,x */
                if(parseFloat(config.porcentaje_costo)) config.porcentaje_costo = ((config.porcentaje_costo / 100) +1);
                if(parseFloat(config.porcentaje_pvp))   config.porcentaje_pvp = ((config.porcentaje_pvp / 100) +1);
                if(parseFloat(config.porcentaje_pvp_2)) config.porcentaje_pvp_2 = ((config.porcentaje_pvp_2 / 100) +1);
                if(parseFloat(config.porcentaje_pvp_3)) config.porcentaje_pvp_3 = ((config.porcentaje_pvp_3 / 100) +1);

                /** Se configuran los campos */
                config.campo = ['codigo', ' descripcion'];

                /** consultamos todo para la actualización de precios y costos */
                getInventariosFiltro(`${URL_BASE}/getInventariosFiltroAll`,  config)
                .then(async todosLosProductos => {
                    // console.log(todosLosProductos);
                    /** Se adaptan todos los productos para ser configurados */
                    await todosLosProductos.data.forEach(element => {
                        inventarioAdaptado.push(adaptadorDeProducto(element, config));
                    });
                    
                    /** Activamos el boton */
                    for (const iterator of e.target) if(iterator.localName == "button") iterator.disabled=false;
                    
                    /** consultamos para pagina y previsualizar */
                    await getLista(config);
              
                    
                });

                break;
            case 'limpiarFiltro':
                    /** mostras una precarga */
                    elementoTablaCuerpo.innerHTML = spinner();

                    /** Resetear formulario de busqueda */
                    for (const iterator of d.forms) {
                        if(iterator.id == "formularioFiltro") iterator.reset();
                    }

                    /** restauramos todo las variables configuradas */
                    inventarios = {},
                    inventarioAdaptado= [];
                    elementoTablaPaginacion.innerHTML="";
                    return elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0})

                break;
            case 'setPorcentajes':
                    let resultadoSetPorcentaje = {};
                    for (const iterator of e.target) if(iterator.localName == "button") iterator.disabled=true;
                    
                    resultadoDeConfirmacion = confirm("¿Seguro que deseas actualizar los precios y costos de los productos seleccionados?");
                    
                    if(resultadoDeConfirmacion){
                        if(!inventarioAdaptado.length){
                            for (const iterator of e.target) if(iterator.localName == "button") iterator.disabled=false;
                            elementoAlert.innerHTML = componenteAlerta("No hay productos seleccionados para actualizar precios y costos", 404);
                        } 
                        
                        
                        let contador = 0;
                        
                        inventarioAdaptado.forEach(async producto => {
                            resultadoSetPorcentaje = await editarProductoDelInventarioPorcentaje(`${URL_BASE}/porcentajes` , producto); 
                            
                            contador++;
                            elementoBarraDePorcentaje.innerHTML = barraDePorcentaje(inventarioAdaptado.length, contador);
                            // log(contador)

                            if(contador == inventarioAdaptado.length){
                                inventarioAdaptado = [];
                                config.porcentaje_costo = 0;
                                config.porcentaje_pvp = 0;
                                config.porcentaje_pvp_2 = 0;
                                config.porcentaje_pvp_3 = 0;
                                getLista(config);
                                elementoBarraDePorcentaje.innerHTML="";
                                for (const iterator of e.target) if(iterator.localName == "button") iterator.disabled=false;
                                formularios[1].reset();
                                elementoAlert.innerHTML = componenteAlerta(resultadoSetPorcentaje.mensaje, resultadoSetPorcentaje.estatus);
                            }
                            
                        });

                    }else{
                        for (const iterator of e.target) if(iterator.localName == "button") iterator.disabled=false;
                    }

                    
                break;
            default:
                break;
        }
       
    }

};

const hanledInputsTable = async (e) => {
    console.log(e.target);
    console.log(e.target.id);
    console.log(e.target.value);
    switch (e.target.id) {
        case 'costo_despues':
                console.log(e.target.id);
                console.log(e.target.parentElement.id);
                inventarioAdaptado.forEach(producto => {
                    producto.costo_despues = parseFloat(e.target.value);
                    producto.pvp_despues = parseFloat(config.porcentaje_pvp) ? parseFloat(e.target.value * config.porcentaje_pvp)       :  producto.pvp_despues;
                    producto.pvp_2_despues = parseFloat(config.porcentaje_pvp_2) ? parseFloat(e.target.value * config.porcentaje_pvp_2) :  producto.pvp_2_despues;
                    producto.pvp_3_despues = parseFloat(config.porcentaje_pvp_3) ? parseFloat(e.target.value * config.porcentaje_pvp_3) : producto.pvp_3_despues;
                })
                console.log(inventarioAdaptado);
                await getLista(config, config.href);
            break;
        case 'pvp_despues':
            console.log(e.target.id);
            console.log(e.target.parentElement.id);
            break;
        case 'pvp_2_despues':
            console.log(e.target.id);
            console.log(e.target.parentElement.id);
            break;
        case 'pvp_3_despues':
            console.log(e.target.id);
            console.log(e.target.parentElement.id);
            break;
    
        default:
            break;
    }
};

/** EVENTOS */
addEventListener('load', hanledLoad);

for (const formulario of formularios) {
    if(formulario.id != "cerrarSesion") formulario.addEventListener('submit', hanledFormulario);
}

elementoTablaPaginacion.addEventListener('click', hanledPaginacion);
elementoBotonResetearFiltro.addEventListener('click', hanledFormulario);

/** FUNCIONES O UTILIDADES EXTRAS */

/** CARGAR EVENTOS DE CAMBIO DE PRECIO Y COSTOS EN LOS INPUTS  */
async function cargarEventosDeInputsDeTable(){
    let inputsCosto = document.querySelectorAll('.costo_despues'),
    inputsPvp = document.querySelectorAll('.pvp_despues'),
    inputsPvp2 = document.querySelectorAll('.pvp_2_despues'),
    inputsPvp3 = document.querySelectorAll('.pvp_3_despues');

    inputsCosto.forEach(inpCost => inpCost.addEventListener('change', hanledInputsTable));
  
    inputsPvp.forEach(inpPvp => inpPvp.addEventListener('change', hanledInputsTable));
   
    inputsPvp2.forEach(inpPvp2 => inpPvp2.addEventListener('change', hanledInputsTable));
  
    inputsPvp3.forEach(inpPvp3 => inpPvp3.addEventListener('change', hanledInputsTable));
};

/** Validar formulario reportes y retorna la data si pasa la validación */
async function validarDataDeFormulario(formulario){
    let esquema = {},
    nameCampo ="",
    banderaDeALertar = 0;
    for (const iterator of formulario) {
        if(iterator.localName == "input" || iterator.localName == "select"){
            if(iterator.name.includes('id_')) nameCampo = iterator.name.substring(3);
            else nameCampo = iterator.name;
            if(!iterator.value.length) iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${nameCampo} es obligatorio`; 
            else iterator.classList.remove(['border-danger']), iterator.nextElementSibling.textContent=``, iterator.classList.add(['border-success']);
            if(iterator.value == "") iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${nameCampo} es obligatorio`; 
            // Asignamos el valor al esquema
            esquema[iterator.name] = iterator.value;
        }
    }
    // log(esquema);
    if(banderaDeALertar) return false;
    else return esquema;
}

/** Adaptar los datos del producto a la vista */
function adaptadorDeProducto(data, config){
    let nuevoCosto = parseFloat(config.porcentaje_costo) ? parseFloat(data.costo * config.porcentaje_costo) : parseFloat(data.costo);
    // console.log(nuevoCosto);

        return {
            id: data.id,
            numero: data.id,
            codigo: data.codigo,
            descripcion: data.descripcion,
            cantidad: parseFloat(data.cantidad),
    
            costo:  parseFloat(data.costo),
            pvp: parseFloat(data.pvp),
            pvp_2:  parseFloat(data.pvp_2),
            pvp_3: parseFloat(data.pvp_3),
            
            costo_despues: parseFloat(config.porcentaje_costo) ? nuevoCosto : data.costo,
            pvp_despues:  parseFloat(config.porcentaje_pvp) ?  parseFloat(nuevoCosto * config.porcentaje_pvp)        : data.pvp,
            pvp_2_despues:  parseFloat(config.porcentaje_pvp_2) ?  parseFloat(nuevoCosto * config.porcentaje_pvp_2)  : data.pvp_2,
            pvp_3_despues:  parseFloat(config.porcentaje_pvp_3) ?  parseFloat(nuevoCosto * config.porcentaje_pvp_3)  : data.pvp_3,
        
    
    
            imagen: data.imagen,
            fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
            marca: data.id_marca.nombre,
            categoria: data.id_categoria.nombre,
        };
    

};

/** Retorna la lista de inventario */
async function getLista(config, url = `${URL_BASE}/getInventariosFiltro`){

    /** Mostramos una precarga */
    elementoTablaCuerpo.innerHTML = spinner();

    /** Realizamos la consulta a la API */
    inventarios = await getInventariosFiltro(url,  config);

    /** Validamos si la respuesta es indefinda */
    if(typeof(inventarios.data.data) == 'undefined'){
        return elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0})
    }

    /** Validamos si la repuesta no tiene elementos */
    if(!inventarios.data.data.length){
        elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0})
    }else{
        /** Vaciamos el cuerpo de la tabla */
        elementoTablaCuerpo.innerHTML='';

        /** Si tiene elementos adaptamos los datos para imprimir en la tabla */
        await inventarios.data.data.forEach( producto => {
            producto.tasa = inventarios.tasa;
            elementoTablaCuerpo.innerHTML += componenteFila(adaptadorDeProducto(producto, config));
        });

        /** Cargamos la paginación de la tabla*/
        elementoTablaPaginacion.innerHTML = componentePaginacion(inventarios);

        let elementoDePaginacion = d.querySelectorAll('.page-item');
        elementoDePaginacion.forEach( btnPaginacion => {
            btnPaginacion.addEventListener('click', hanledPaginacion);
        });

        /** Cargamos los eventos de los inputs de los precios y costos */
        await cargarEventosDeInputsDeTable();
    }
}

