log('conectado a productos');
/** VARIABLES */
let elementoTablaCuerpo = d.querySelector('#lista'),
elementoInputFiltroDescripcion = d.querySelector('#filtro-descripcion'),
elementoInputFiltroCodigo = d.querySelector('#filtro-codigo'),
elementoSpanInvalido = d.querySelectorAll('.invalido'),
elementoTablaPaginacion = d.querySelector('.paginacion'),
elementoBotonResetearFiltro = d.querySelector('#limpiarFiltro'),
config = {};



/** COMPONENTES */
const componenteFila = (data) => {
    if (data.estatus == 0) {
        return `
        <tr>
            <td colspan="10" class="text-center text-danger ">NO HAY RESULTADOS</td>
        </tr>
        `;
    } else {
        return `
            <tr>
                <td>${data.numero}</td>
                <td>${data.codigo}</td>
                <td>${data.descripcion}</td>
                <td>${data.categoria}</td>
                <td>${data.marca}</td>
                
                <td class="d-flex align-items-stretch ">
                    ${ componenteModalVer(data) }
                    ${ componenteModalEliminar(data) }
                   
                    
                    <!--<button type="button" class="btn btn-success m-1" id="activarModalVer/${data.id}"><i class="bi bi-eye"></i></button>-->
                    <!--<button type="button" class="btn btn-danger m-1" id="activarModalEliminar/${data.id}"><i class="bi bi-trash"></i></button>-->
                    <a href="formularioEditarProducto/${data.codigo}" target="_self" class="btn btn-warning m-1"><i class="bi bi-pencil"></i></button>
                </td>
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

const componenteModalVer = (data) => {
    return `
        <button type="button" class="btn btn-success m-1" data-bs-toggle="modal" data-bs-target="#modalDialogScrollable${data.id}" >
        <i class="bi bi-eye"></i>
        </button>

        <div class="modal fade" id="modalDialogScrollable${data.id}" tabindex="-1">
        <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title">Informacion de inventario del producto</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <section class="section profile">
                    <div class="row">

                    <div class="col-xl-12">

                        <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <img src="${ data.imagen }" alt="Profile" class="rounded-circle">
                            <h2>  ${ data.descripcion }</h2>
                            <h3><b>Código de barra: </b>${ data.codigo } </h3>

                            <div class="container-fluid">
                            <div class="row">
                                <hr>
                                <div class="col-md-12">
                                <h3>Mas detalles</h3>
                                </div>

                                <div class="col-md-12 label"> 
                                <span class="text-primary">Categoria:</span> ${ data.categoria }<br>
                                <span class="text-primary">Marca:</span>${ data.marca } 
                                </div>
   
                            </div>
                            </div>
                        </div>
                        </div>
            
                    </div>
                    </div>
                </section>
          

                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
            </div>
        </div><!-- End Modal Dialog Scrollable-->
    `;
};

const componenteModalEliminar = (data) => {
    return `
        <!-- Vertically centered Modal -->
      
        <button type="button" class="btn btn-danger m-1" data-bs-toggle="modal" data-bs-target="#modalEliminar_${ data.id }" id="activarModalEliminar/${data.id}"><i class="bi bi-trash"></i></button>

        <div class="modal fade" id="modalEliminar_${ data.id }" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title">Eliminar Producto del inventario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                    <div class="modal-body">
                        <p>
                        Está seguro que desea eliminar los datos de inventario del producto <b>${ data.descripcion }</b> <br>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger btn-eliminar" id="${ data.id }">Si, Proceder a eleminar</button>
                    </div>
            </div>
            </div>
        </div><!-- End Vertically centered Modal-->
    `;
};  


const componenteModalEditar = async (data) => {
    return `
        
    <button type="button" class="btn btn-warning m-1" data-bs-toggle="modal" data-bs-target="#modalEditar${data.id}" id="activarModalEditar/${data.id}" >
        <i class="bi bi-pencil"></i>
    </button>
    
    
    <div class="modal fade" id="modalEditar${data.id}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Actualizar datos de inventario del producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form action="${URL_BASE}/productos/${data.id}" 
            method="post" 
            target="_self">
                <div class="modal-body row">

                
                    <div class="col-sm-12">
                        <label for="cantidad" class="form-label">Código</label>
                        <input type="text"  class="form-control" name="codigo" id="codigo" value="${ data.codigo }" required>
                        <div class="text-danger validate"></div>
                    </div>

                    <div class="col-md-12 ">
                        <label for="costo" class="form-label">Costo</label>
                        <input type="text" class="form-control" name="descripcion" id="descripcion" value="${ data.descripcion }" required>
                        <div class="text-danger validate"></div>
                    </div>

                     ${ componenteCategorias( data ).then(selector => selector) }
                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary ">Guardar datos</button>
                </div>
                
            </form>
        </div>
        </div>
  
        </div><!-- End Vertically centered Modal-->
    `;
};

const componenteCategorias = async (data) => {
    let opcionesHtml = ``,
    categorias = await getCategorias(`${URL_BASE}/getCategorias`);

    /** Recorremos las categorias y listamos las opciones */
    categorias.data.forEach(categoria => {
        opcionesHtml += `
            <option ${categoria.nombre == data.categoria ? 'selected' : ''} value="${categoria.id}"> ${categoria.nombre} </option>
        `;
    });
  

    /** Retornamos el componente Selector  */
    return `
        <div class="form-floating mt-2">
            <select class="form-select" name="id_categoria" id="id_categoria" aria-label="Floating label select example">                
                ${opcionesHtml}
            </select>
            <label for="categorias">Categorias</label>
        </div>
    `;
};

const componenteMarcas = (data) => { 
    let opcionesHtml = ``;

    marcas.data.forEach(marca => {
        opcionesHtml += `
            <option ${marca.nombre == data.marca ? 'selected' : ''} value="${marca.id}"> ${marca.nombre} </option>
        `;
    });
   

    return `
        <div class="form-floating mt-2">
            <select class="form-select" name="id_marca" id="id_marca" aria-label="Floating label select example">                
                ${opcionesHtml}
            </select>
            <label for="marca">Marcas</label>
        </div>
    `;
};




/** MANEJADORES DE EVENTO */
const hanledLoad = async (e) => {
    await getLista();
    await cargarEventosDelBotonEliminar();
    await cargarEventosDelBotonEditar();
};

const hanledBotonEliminar = async (e) => {
    e.preventDefault();
 
    // Asignamos el spinner
    e.target.parentElement.parentElement.children[1].innerHTML = spinner;

    // Ocultamos el boton eliminar al hacer la solicitud de eliminar
    e.target.parentElement.children[1].classList.add('d-none');

    // Hacemos la peticion a la API para eliminar el producto del inventario
    let resultado = await deleteProducto(e.target.id);
   
    // retornamos una respuesta
    // e.target.parentElement.parentElement.children[1].innerHTML = respuesta( resultado.mensaje, resultado.estatus );
    
    setTimeout(() => {
        window.location.href=`${URL_BASE_APP}/productos?mensaje=${resultado.mensaje}&estatus=${resultado.estatus}`;
    }, 500);
   

};

const hanledPaginacion = async (e) => {
    e.preventDefault();
    if(e.target.href){
        if(e.target.href.includes('getProductosFiltro')){
            elementoTablaPaginacion.innerHTML = '';
            elementoTablaCuerpo.innerHTML = spinner;
         
            let inventarios = await getProductosFiltro(`${e.target.href}`,  config);
        
            if(!inventarios.data.data.length){
                elementoTablaCuerpo.innerHTML =  componenteFila({estatus: 0});
            }else{
                elementoTablaCuerpo.innerHTML='';
                await inventarios.data.data.forEach(  element => {
                    element.tasa = inventarios.tasa;
                    elementoTablaCuerpo.innerHTML +=  componenteFila(adaptadorDeProducto(element));
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
   
    if(e.target.id != "cerrarSesion" && e.target.id != 'crearProductoLaravel'){
        e.preventDefault();

        log(e.target);
        switch (e.target.id) {
            case 'formularioFiltro':
                log('filtrando')
                let banderaDeALertarConfig = 0;
                
                for (const iterator of e.target) {
                    if(iterator.localName == "input" || iterator.localName == "select"){
                        // if(iterator.value == "") iterator.classList.add(['border-danger']), banderaDeALertarConfig++; 
                        // else iterator.classList.remove(['border-danger']), iterator.classList.add(['border-success']);
                        
                        if(iterator.value == "CATEGORIAS" || iterator.value == "MARCAS")  config[iterator.name] = 0;
                        else  config[iterator.name] = iterator.value;
                    }
                }
                if(banderaDeALertarConfig) return;

                /** Se configuran los campos */
                config.campo = ['codigo', ' descripcion'];

                let inventarios = await getProductosFiltro(`${URL_BASE}/getProductosFiltro`,  config);
                log(inventarios);
                elementoTablaCuerpo.innerHTML = spinner;

                /** Si no hay datos entregamos un estatus 0 */
                if(!inventarios.data.data.length) return elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0});

                setTimeout(async ()=>{
                    elementoTablaCuerpo.innerHTML = "";
                    await inventarios.data.data.forEach( element => {
                        element.tasa = inventarios.tasa;
                        elementoTablaCuerpo.innerHTML += componenteFila(adaptadorDeProducto(element));
                    });
                    /** Activamos los eventos del boton eliminar */
                    await cargarEventosDelBotonEliminar();
                    await cargarEventosDelBotonEditar();
            
                    if(inventarios.data.links){
                        elementoTablaPaginacion.innerHTML = componentePaginacion(inventarios.data);
                    }
    
                    let elementoDePaginacion = d.querySelectorAll('.page-item');
                    log(elementoDePaginacion)
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
            default:
                break;
        }
    }
};

/** EVENTOS */
addEventListener('load', hanledLoad);
elementoTablaPaginacion.addEventListener('click', hanledPaginacion);
elementoBotonResetearFiltro.addEventListener('click', hanledFormulario);

/** FUNCIONES O UTILIDADES EXTRAS */
function adaptadorDeProducto(data){
   
    return {
        id: data.id,
        numero: data.id,
        codigo: data.codigo,
        descripcion: data.descripcion,
        categoria: data.id_categoria.nombre,
        marca: data.id_marca.nombre,
        imagen: data.imagen,
        // cantidad: data.cantidad,
        // costo: darFormatoDeNumero(quitarFormato( data.costo )),
        // costoBs: darFormatoDeNumero( quitarFormato(data.costo) * data.tasa ),
        // pvp: darFormatoDeNumero( (data.tasa * quitarFormato(data.pvp)) ),
        // pvpUsd:  darFormatoDeNumero(quitarFormato(data.pvp)),
        // pvp_2: darFormatoDeNumero( (data.tasa * quitarFormato(data.pvp_2)) ),
        // pvpUsd_2:  darFormatoDeNumero( quitarFormato(data.pvp_2) ),
        // pvp_3: darFormatoDeNumero( (data.tasa * quitarFormato(data.pvp_3)) ),
        // pvpUsd_3:  darFormatoDeNumero(quitarFormato(data.pvp_3)),
        // fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
    };
};

async function getLista(url = `${URL_BASE}/getProductos`){
    elementoTablaCuerpo.innerHTML = spinner;
    let inventarios = await getProductos(url);
    log(inventarios);

    if(typeof(inventarios.data.data) == 'undefined'){
        return elementoTablaCuerpo.innerHTML =  componenteFila({estatus: 0})
    }
    
    if(!inventarios.data.data.length){
        elementoTablaCuerpo.innerHTML =  componenteFila({estatus: 0})
    }else{
        elementoTablaCuerpo.innerHTML='';
        await inventarios.data.data.forEach( element => {
            elementoTablaCuerpo.innerHTML += componenteFila(adaptadorDeProducto(element));
        });
      
        elementoTablaPaginacion.innerHTML = componentePaginacion(inventarios.data);
      
    }
};

async function cargarEventosDelBotonEliminar(){
    elementoBotonEliminar = await d.querySelectorAll('.btn-eliminar');
    await elementoBotonEliminar.forEach( botonEliminar => {
        botonEliminar.addEventListener('click', hanledBotonEliminar);
    });
};

async function cargarEventosDelBotonEditar(){
    let formularios = d.forms;
    for (const iterator of formularios) {
        iterator.addEventListener('submit', hanledFormulario);
    }
};