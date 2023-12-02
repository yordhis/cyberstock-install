log('conectado a inventario')
/** VARIABLES */
let elementoTablaCuerpo = d.querySelector('#lista'),
elementoInputFiltroDescripcion = d.querySelector('#filtro-descripcion'),
elementoInputFiltroCodigo = d.querySelector('#filtro-codigo'),
elementoInputFiltroLimpiar = d.querySelector('#filtro-limpiar'),
elementoSpanInvalido = d.querySelectorAll('.invalido'),
elementoTablaPaginacion = d.querySelector('.paginacion');



/** COMPONENTES */
const componenteFila = (data) => {
    // log(data)
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
                <td>${data.cantidad}</td>
                <td>${data.costo}</td>
                <td>${darFormatoDeNumero(quitarFormato(data.pvp))} Bs</td>
                <td>${data.pvpUsd} $</td>
                <td>${data.marca}</td>
                <td>${data.categoria}</td>
                
                <td class="d-flex align-items-stretch ">
                    ${componenteModalVer(data)}
                    ${componenteModalEliminar(data)}
                    ${componenteModalEditar(data)}
                    <!--<button type="button" class="btn btn-success m-1" id="activarModalVer/${data.id}"><i class="bi bi-eye"></i></button>-->
                    <!--<button type="button" class="btn btn-danger m-1" id="activarModalEliminar/${data.id}"><i class="bi bi-trash"></i></button>-->
                    <!--<button type="button" class="btn btn-warning m-1" id="activarModalEditar/${data.id}"><i class="bi bi-pencil"></i></button>-->
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
                            <h4><b>Cantidad en existencia: </b>${ data.cantidad } </h4>

                            <div class="container-fluid">
                            <div class="row">
                                <hr>
                                <div class="col-md-12">
                                <h3>Mas detalles</h3>
                                </div>

                                <div class="col-md-12 label"> 
                                <span class="text-primary">Costo: REF</span> ${ data.costo } <br>
                                <span class="text-primary">Costo: Bs</span> ${ data.costoBs } <br>
                                <hr>
                                <span class="text-primary">PVP: REF</span> ${ data.pvpUsd } <br>
                                <span class="text-primary">PVP:Bs</span> ${ data.pvp } <br>
                                <hr>
                                <span class="text-primary">PVP 2: REF</span> ${ data.pvpUsd_2 } <br>
                                <span class="text-primary">PVP 2:Bs</span> ${ data.pvp_2 } <br>
                                <hr>
                                <span class="text-primary">PVP 3: REF</span> ${ data.pvpUsd_3 } <br>
                                <span class="text-primary">PVP 3:Bs</span> ${ data.pvp_3 } <br>
                                <hr>
                                </div>

                             

                                <div class="col-md-12 label"> 
                                <span class="text-primary">Categoria:</span> ${ data.categoria }<br>
                                <span class="text-primary">Marca:</span>${ data.marca } 
                                </div>

                                <div class="col-md-12 label"> 
                                <span class="text-primary">Fecha de creación:</span> ${ data.fechaEntrada }
                                
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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-danger btn-eliminar" id="${ data.id }">Si, Proceder a eleminar</button>
                    </div>
            </div>
            </div>
        </div><!-- End Vertically centered Modal-->
    `;
};  

const componenteModalEditar = (data) => {
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
            
            <form action="${URL_BASE}/editarProductoDelInventario/${data.id}" 
            method="post" 
            target="_self">
                <div class="modal-body row">

                
                    <div class="col-md-6 col-xs-12">
                        <label for="cantidad" class="form-label">Ingrese Cantidad</label>
                        <input type="number" step="any" class="form-control" id="cantidad" value="${ data.cantidad }" required>
                        <div class="text-danger validate"></div>
                    </div>
                    <div class="col-md-6 col-xs-12">
                        <label for="costo" class="form-label">Costo</label>
                        <input type="number" step="any" class="form-control" id="costo" value="${ data.costo }" required>
                        <div class="text-danger validate"></div>
                    </div>


                    <div class="col-md-6 col-xs-12">
                        <label for="pvp" class="form-label">PVP Detal</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">REF</span>
                            <input type="number" step="any" class="form-control" id="pvp" value="${ data.pvpUsd }" required>
                            <div class="text-danger validate"></div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-12">
                        <label for="pvp_2" class="form-label">PVP 2</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">REF</span>
                            <input type="number" step="any" class="form-control" id="pvp_2" value="${ data.pvpUsd_2 }" required>
                            <div class="text-danger validate"></div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xs-12">
                        <label for="pvp_3" class="form-label">PVP 3</label>
                        <div class="input-group has-validation">
                            <span class="input-group-text" id="inputGroupPrepend">REF</span>
                            <input type="number" step="any" class="form-control" id="pvp_3" value="${ data.pvpUsd_3 }" required>
                            <div class="text-danger validate"></div>
                        </div>
                    </div>
     
                  
                   
                        
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

/** MANEJADORES DE EVENTO */
const hanledLoad = async (e) => {
    await getLista();
    await cargarEventosDelBotonEliminar();
    await cargarEventosDelBotonEditar();
};

const hanledBotonEliminar = async (e) => {
    e.preventDefault();
    // log(e.target.parentElement.parentElement.parentElement.isConnected);
 
    // Asignamos el spinner
    e.target.parentElement.parentElement.children[1].innerHTML = spinner;

    // Ocultamos el boton eliminar al hacer la solicitud de eliminar
    e.target.parentElement.children[1].classList.add('d-none');

    // Hacemos la peticion a la API para eliminar el producto del inventario
    let resultado = await deleteProductoDelInventario(e.target.id);

    // retornamos una respuesta
    // e.target.parentElement.parentElement.children[1].innerHTML = respuesta( resultado.mensaje, resultado.estatus );
    
    setTimeout(() => {
        window.location.href=`${URL_BASE_APP}/inventarios?mensaje=${resultado.mensaje}&estatus=${resultado.estatus}`;
    }, 500);
   

};

const hanledPaginacion = async (e) => {
    e.preventDefault();
    if(e.target.href){
        if(e.target.href.includes('getInventariosFiltro')){
            elementoTablaPaginacion.innerHTML = '';
            elementoTablaCuerpo.innerHTML = spinner;
         
            let inventarios = await getInventariosFiltro(`${e.target.href}`,  { filtro: `${elementoInputFiltroDescripcion.value}`});
        
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

const hanledFiltro = async (e) => {
    // Limpiar filtro
    if(e.target.id == "filtro-limpiar"){
        elementoInputFiltroDescripcion.value = '';
        await getLista();
        await cargarEventosDelBotonEliminar();
        await cargarEventosDelBotonEditar();
    } 
    
    // validamos los keyup para no filtrar 
    if (e.key == "Enter" ){
        if(!e.target.value.trim().length){
            hanledLoad();
            return e.target.parentElement.children[2].textContent = 'Ingrese un dato que no sea vacio';    
        }
    
        if(e.target.id.split('-')[1] == 'limpiar'){
            elementoSpanInvalido.forEach(element => element.textContent = null);
            elementoInputFiltroDescripcion.value = null;
            elementoInputFiltroCodigo.value = null;
            await getLista();
            /** Activamos los eventos del boton eliminar */
            await cargarEventosDelBotonEliminar();
            await cargarEventosDelBotonEditar();
    
        }else{
            elementoSpanInvalido.forEach(element => element.textContent = null);
            let filtro = {
                filtro: `${e.target.value.trim()}`,
                campo: ['codigo', ' descripcion'],
            };
        
            if(e.target.value){
                elementoTablaPaginacion.innerHTML = '';
                elementoTablaCuerpo.innerHTML = spinner;
                let inventarios = await getInventariosFiltro(`${URL_BASE}/getInventariosFiltro`,  filtro);
                // log(inventarios);
                if(!inventarios.data){
                    elementoTablaCuerpo.innerHTML = componenteFila({estatus: 0})
                }else{
                    elementoTablaCuerpo.innerHTML='';
                    
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
                }
            }
        }

    }


};

const hanledFormulario = async (e) => {
    if(e.target.id != "cerrarSesion"){
        e.preventDefault();

        let esquema = {},
        banderaDeALertar = 0;
        for (const iterator of e.target) {
            if(iterator.localName == "input"){
                if(iterator.value < 0) iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent="No se permiten valoeres negativos"; 
                else iterator.classList.remove(['border-danger']), iterator.nextElementSibling.textContent="", iterator.classList.add(['border-success']);
                esquema[iterator.id] = parseFloat(iterator.value);
            }
        }
        if(banderaDeALertar) return;
    
    

        e.target.innerHTML = spinner;
    
        let resultado = await editarProductoDelInventario(e.target.action, esquema);
        
        // await getLista();
        setTimeout(() => {
            window.location.href=`${URL_BASE_APP}/inventarios?mensaje=${resultado.mensaje}&estatus=${resultado.estatus}`;
        }, 500);
    }

};

/** EVENTOS */
addEventListener('load', hanledLoad);
elementoTablaPaginacion.addEventListener('click', hanledPaginacion);
elementoInputFiltroLimpiar.addEventListener('click', hanledFiltro);
elementoInputFiltroDescripcion.addEventListener('keyup', hanledFiltro);
// elementoInputFiltroCodigo.addEventListener('keyup', hanledFiltro);





/** FUNCIONES O UTILIDADES EXTRAS */
function adaptadorDeProducto(data){
    // log(data)
    return {
        id: data.id,
        numero: data.id,
        codigo: data.codigo,
        descripcion: data.descripcion,
        cantidad: data.cantidad,
        costo: darFormatoDeNumero(quitarFormato( data.costo )),
        costoBs: darFormatoDeNumero( quitarFormato(data.costo) * data.tasa ),
        pvp: darFormatoDeNumero( (data.tasa * quitarFormato(data.pvp)) ),
        pvpUsd:  darFormatoDeNumero(quitarFormato(data.pvp)),
        pvp_2: darFormatoDeNumero( (data.tasa * quitarFormato(data.pvp_2)) ),
        pvpUsd_2:  darFormatoDeNumero( quitarFormato(data.pvp_2) ),
        pvp_3: darFormatoDeNumero( (data.tasa * quitarFormato(data.pvp_3)) ),
        pvpUsd_3:  darFormatoDeNumero(quitarFormato(data.pvp_3)),
        marca: data.id_marca.nombre,
        imagen: data.imagen,
        fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
        categoria: data.id_categoria.nombre,
    };
};

async function getLista(url = `${URL_BASE}/getInventarios`){
    elementoTablaCuerpo.innerHTML = spinner;
    let inventarios = await getInventarios(url);
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