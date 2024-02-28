
log('conectado a porcentajes')
/** VARIABLES */
let elementoTablaCuerpo = d.querySelector('#lista'),
elementoAlert = d.querySelector('#alert'),
elementoInputFiltroDescripcion = d.querySelector('#filtro-descripcion'),
elementoInputFiltroCodigo = d.querySelector('#filtro-codigo'),
elementoInputFiltroLimpiar = d.querySelector('#filtro-limpiar'),
elementoSpanInvalido = d.querySelectorAll('.invalido'),
elementoTablaPaginacion = d.querySelector('.paginacion'),
elementoBotonResetearFiltro = d.querySelector('#limpiarFiltro'),
formularios = d.forms,
inventarios = {},
inventarioAdaptado= [];


console.log(elementoTablaCuerpo);
config = {};



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
                
                <td>${darFormatoDeNumero(data.costo)}</td>
                <td>${darFormatoDeNumero(data.pvp)}</td>
                <td>${darFormatoDeNumero(data.pvp_2)}</td>
                <td>${darFormatoDeNumero(data.pvp_3)}</td>
                
                <td class="table-warning">${data.costo_despues ? darFormatoDeNumero(data.costo_despues) : 0 }</td>
                <td class="table-warning">${data.pvp_despues ? darFormatoDeNumero(data.pvp_despues) : 0 }</td>
                <td class="table-warning">${data.pvp_2_despues ? darFormatoDeNumero(data.pvp_2_despues) : 0 }</td>
                <td class="table-warning">${data.pvp_3_despues ? darFormatoDeNumero(data.pvp_3_despues) : 0 }</td>
            </tr>
        `;
    }
};

const componentePaginacion = (data) => {
    let itemsLi = '';
    data.links.forEach(link => {
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
                <li class="page-item ${link.label == data.current_page ? 'active' : ''}">
                    <a class="page-link" href="${link.url}" >${link.label}</a>
                </li>
            `;
        }
    });

    return `
        <ul class="pagination justify-content-center">
            ${itemsLi}
        </ul>
    `;
};


/** MANEJADORES DE EVENTO */
const hanledLoad = async (e) => {

};


const hanledPaginacion = async (e) => {
    e.preventDefault();
    if(e.target.href){
        if(e.target.href.includes('getInventariosFiltro')){
            elementoTablaPaginacion.innerHTML = '';
            elementoTablaCuerpo.innerHTML = spinner();

            let inventarios = await getInventariosFiltro(`${e.target.href}`,  config);
        
            if(!inventarios.data.data.length){
                elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0});
            }else{
                elementoTablaCuerpo.innerHTML='';
                await inventarios.data.data.forEach( element => {
                    element.tasa = inventarios.tasa;
                    elementoTablaCuerpo.innerHTML += componenteFila(adaptadorDeProducto(element));
                });
        
                elementoTablaPaginacion.innerHTML = componentePaginacion(inventarios.data);

                /** Activamos los eventos del boton eliminar */
                await cargarEventosDelBotonEliminar();
                await cargarEventosDelBotonEditar();
            }
        }else{
            elementoTablaPaginacion.innerHTML = '';
            await getLista(e.target.href);
            
            /** Activamos los eventos del boton eliminar */
            await cargarEventosDelBotonEliminar();
            await cargarEventosDelBotonEditar();
        }
    }
};

const hanledFormulario = async (e) => {
    if(e.target.id != "cerrarSesion"){
        e.preventDefault();
        console.log(e.target);
        switch (e.target.id) {
            case 'formularioFiltro':
                let banderaDeALertarConfig = 0;

                for (const iterator of e.target) {
                    if(iterator.localName == "input" || iterator.localName == "select"){
                        if(iterator.value == "CATEGORIAS" || iterator.value == "MARCAS")  config[iterator.name] = 0;
                        else  config[iterator.name] = iterator.value;
                    }
                }

                console.log(config);
                config.porcentaje = ((config.porcentaje / 100) +1);
                /** Se configuran los campos */
                config.campo = ['codigo', ' descripcion'];

                inventarios = await getInventariosFiltro(`${URL_BASE}/getInventariosFiltro`,  config);
                log(inventarios);
                elementoTablaCuerpo.innerHTML = spinner();

                /** Si no hay datos entregamos un estatus 0 */
                if(!inventarios.data.data.length) return elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0});

                setTimeout(async ()=>{
                    elementoTablaCuerpo.innerHTML = "";
                    await inventarios.data.data.forEach( element => {
                        element.tasa = inventarios.tasa;
                        inventarioAdaptado.push(adaptadorDeProducto(element, config.porcentaje));
                        elementoTablaCuerpo.innerHTML += componenteFila(adaptadorDeProducto(element, config.porcentaje));
                    });
          
            
                    if(inventarios.data.links){
                        elementoTablaPaginacion.innerHTML = componentePaginacion(inventarios.data);
                    }
    
                    let elementoDePaginacion = d.querySelectorAll('.page-item');
                    elementoDePaginacion.forEach( btnPaginacion => {
                        btnPaginacion.addEventListener('click', hanledPaginacion);
                    });
                }, 1500)


                
                break;
            case 'limpiarFiltro':
                    for (const iterator of d.forms) {
                        if(iterator.id == "formularioFiltro") iterator.reset();
                    }
                    hanledLoad();
                break;
            case 'setPorcentajes':
                    let resultadoSetPorcentaje = {};
                    
                    resultadoDeConfirmacion = confirm("¿Seguro que deseas actualizar los precios y costos de los productos seleccionados?");
                    
                    if(resultadoDeConfirmacion){
                        if(!inventarioAdaptado.length) elementoAlert.innerHTML = componenteAlerta("No hay productos seleccionados para actualizar precios y costos", 404);
                        
                        elementoTablaCuerpo.innerHTML=spinner();
                        
                        inventarioAdaptado.forEach(producto => {
                            resultadoSetPorcentaje = editarProductoDelInventarioPorcentaje(`${URL_BASE}/porcentajes` , producto); 
                        });

                        resultadoSetPorcentaje.then(res => {
                            config.porcentaje = 0;
                            getLista(config);
                            elementoAlert.innerHTML = componenteAlerta(res.mensaje, res.estatus);
                        });
                    }

                    
                break;
            default:
                break;
        }
       
    }

};

/** EVENTOS */
addEventListener('load', hanledLoad);

for (const formulario of formularios) {
    if(formulario.id != "cerrarSesion") formulario.addEventListener('submit', hanledFormulario);
}

// elementoTablaPaginacion.addEventListener('click', hanledPaginacion);
elementoBotonResetearFiltro.addEventListener('click', hanledFormulario);





/** FUNCIONES O UTILIDADES EXTRAS */
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
    log(esquema);
    if(banderaDeALertar) return false;
    else return esquema;
}

/** Adaptar los datos del producto a la vista */
function adaptadorDeProducto(data, porcentaje){
    // log(data)
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
        
        costo_despues: parseFloat(data.costo * porcentaje),
        pvp_despues:  parseFloat(data.pvp * porcentaje),
        pvp_2_despues:  parseFloat(data.pvp_2 * porcentaje),
        pvp_3_despues:  parseFloat(data.pvp_3 * porcentaje),


        imagen: data.imagen,
        fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
        marca: data.id_marca.nombre,
        categoria: data.id_categoria.nombre,
    };
};

/** Retorna la lista de inventario */
async function getLista(config, url = `${URL_BASE}/getInventariosFiltro`){
    elementoTablaCuerpo.innerHTML = spinner();
    let inventarios = await getInventariosFiltro(url,  config);

    if(typeof(inventarios.data.data) == 'undefined'){
        return elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0})
    }

    if(!inventarios.data.data.length){
        elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0})
    }else{
        elementoTablaCuerpo.innerHTML='';
        await inventarios.data.data.forEach( element => {
            element.tasa = inventarios.tasa;
            elementoTablaCuerpo.innerHTML += componenteFila(adaptadorDeProducto(element));
        });
        elementoTablaPaginacion.innerHTML = componentePaginacion(inventarios.data);
    }
}

