/** ELEMENTOS */
let elementoTarjetaCliente = d.querySelector('#tarjetaCliente'),
elementoBuscarProducto = d.querySelector('#buscarProducto'),
elementoTablaBuscarProducto = d.querySelector('#tablaBuscarProducto'),
elementoTotalProductos = d.querySelector('#totalProductosFiltrados'),
codigoFactura = d.querySelector('#codigoFactura'),
elementoAlertas = d.querySelector('#alertas'), 
listaDeProductosEnFactura = d.querySelector('#listaDeProductosEnFactura'), 
elementoFactura = d.querySelector('#componenteFactura'), 
elementoMetodoDePagoModal= d.querySelector('#elementoMetodoDePagoModal'), 
factura = {
        codigo:'',
        codigo_factura:'',
        razon_social:'', // nombre de cliente o proveedor
        identificacion:'', // numero de documento
        subtotal:'', // se guarda en divisas
        total:'',
        tasa:'', // tasa en el momento que se hizo la transaccion
        iva:'', // impuesto
        tipo:'', // fiscal o no fialcal
        concepto:'', // venta, compra ...
        descuento:'', // descuento
        fecha:'', // fecha venta, compra ...
        metodos:''
},
metodosPagos = [{
    id: 1,
    tipoDePago: null,
    montoDelPago: 0,
}];


/** COMPONENTES */
const componenteTarjetaCliente = (cliente, mensaje) => {
    if(cliente.length){
        cliente = cliente[0];
        return `
        <div class="card-body">
            <h5 class="card-title text-danger">Cliente N°: ${cliente.id}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Datos del cliente</h6>
            <p class="card-text">
                <b>Nombre y Apellido:</b> ${cliente.nombre} <br><br>
                <b>Rif o ID:</b> ${cliente.tipo}-${cliente.identificacion} <br><br>
                
            </p>
            <a href="#" class="card-link me-3 acciones-cliente" id="activarInputBuscarCliente">
                <i class="bi bi-search fs-4"></i>
            </a>
            <a href="${cliente.identificacion}" class="card-link me-3 acciones-cliente" id="activarFormEditarCliente">
                <i class="bi bi-pencil-fill fs-4"></i>
            </a>
            <a href="#" class="card-link me-3 acciones-cliente" id="activarFormCrearCliente">
                <i class="bi bi-person-add fs-4"></i>
            </a>
        </div>
        `;
    }else{
        return `
        <div class="card-body">
            <h5 class="card-title text-danger">Ingrese RIF o Número de Cédula</h5>
            
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Rif o ID" id="buscarCliente">
            </div>

            <h6 class="card-subtitle mb-2 text-danger">${mensaje}</h6>

            <a href="#" class="card-link me-3 acciones-cliente" id="activarInputBuscarCliente">
                <i class="bi bi-search fs-4"></i>
            </a>
         
            <a href="#" class="card-link me-3 acciones-cliente" id="activarFormCrearCliente">
                <i class="bi bi-person-add fs-4"></i>
            </a>
        
        </div>
        `;
    }
    
    
};

const componenteFormularioAgregarCliente = () => {
    return `
    <div class="card-header p-0">
    <p class="text-danger  card-title text-center">
        <i class="bi bi-person-add"></i>
        Registrar cliente
    </p>
    </div>
    <div class="card-body p-0">
        <form action="#" method="post" id="formCrearCliente">
            <div class="form-floating m-2">
                <input type="text" class="form-control" name="nombre" id="floatingInput" placeholder="Ingrese nombre y apellido">
                <label for="floatingInput">Nombre y apelldio</label>
                <div class="text-danger validate"></div>
            </div>
            <div class="form-floating m-2">
                <select class="form-select" id="floatingSelect" name="tipo" aria-label="Floating label select example">
                <option selected>Tipo de documento</option>
                <option value="V">V</option>
                <option value="E">E</option>
                <option value="J">J</option>
                </select>
                <label for="floatingSelect">Seleccione tipo de documento</label>
                <div class="text-danger validate"></div>
            </div>
            <div class="form-floating m-2">
                <input type="number" class="form-control" name="identificacion" id="floatingInput" placeholder="Ingrese número de identificación.">
                <label for="floatingInput">RIF O ID</label>
                <div class="text-danger validate"></div>
            </div>
            <div class="form-floating m-2">
                <div id="respuesta-de-validacion"></div>                
            </div>
            <div class="form-floating m-2">
                <button type="submit" class="btn btn-success w-100 ">Guardar datos</button>
                
            </div>

          

            </form>
            <a href="#" class="card-link ms-3 acciones-cliente" id="activarInputBuscarCliente">
                <i class="bi bi-search fs-4"></i>
            </a>
    
    </div>
    `;
};

const componenteFormularioEditarCliente = (cliente) => {
    if(cliente.length){
        cliente = cliente[0];
    
        return `
        <div class="card-header p-0">
        <p class="text-danger  card-title text-center">
            <i class="bi bi-person-add"></i>
            Editar cliente
        </p>
        </div>
        <div class="card-body p-0">
            <form action="${URL_BASE}/updateCliente/${cliente.id}" method="post" id="formEditarCliente">
            
                <div class="form-floating m-2">
                    <input type="text" class="form-control" name="nombre" value="${cliente.nombre}" id="floatingInput" placeholder="Ingrese nombre y apellido">
                    <label for="floatingInput">Nombre y apelldio</label>
                    <div class="text-danger validate"></div>
                </div>
                <div class="form-floating m-2">
                    <select class="form-select" id="floatingSelect" name="tipo" aria-label="Floating label select example">
                    value="${cliente.tipo ? `<option value="${cliente.tipo}">${cliente.tipo}</option>` : `<option selected>Tipo de documento</option>`}"
                    <option value="V">V</option>
                    <option value="E">E</option>
                    <option value="J">J</option>
                    </select>
                    <label for="floatingSelect">Seleccione tipo de documento</label>
                    <div class="text-danger validate"></div>
                </div>
                <div class="form-floating m-2">
                    <input type="number" class="form-control" name="identificacion" value="${cliente.identificacion}" id="floatingInput" placeholder="Ingrese número de identificación.">
                    <label for="floatingInput">RIF O ID</label>
                    <div class="text-danger validate"></div>
                </div>
                <div class="form-floating m-2">
                    <button type="submit" class="btn btn-success w-100 ">Guardar datos</button>
                    
                </div>
    
    
                </form>
                <a href="#" class="card-link ms-3 acciones-cliente" id="activarInputBuscarCliente">
                    <i class="bi bi-search fs-4"></i>
                </a>
        
        </div>
        `;
    }else{
        return componenteTarjetaCliente([], 'No se pudo obtener la data del cliente.')
    }
};

/** LISTA DEL FILTRO */
const componenteListaDeProductoFiltrados = (producto) => {
   
    if (producto.estatus == 0) {
        return  `
        <tr>
            <td colspan="5" class="text-center text-danger ">NO HAY RESULTADOS</td>
        </tr>
        `;
    } else {
        return  `
        <tr>
            <td>
              
                <i class="bi bi-plus-square fs-5 hero__cta"></i>
                
                ${ componenteAgregarCantidadDeProductoModal(producto) }
            </td>
            <td>${producto.codigo}</td>
            <td>${producto.descripcion}</td>
            <td>
                ${producto.pvpBs} <br>
                REF: ${producto.pvp}
            </td>
        
            <td>${producto.cantidad}</td>
        
        </tr>
        `;
    }
};

const componenteAgregarCantidadDeProductoModal = (producto) =>{
    return `
        <!-- Modal -->
        <section class="modal__custom ">
            <div class="modal__container">
                
                <h2 class="modal__title">Agregar cantidad</h2>
                <p class="modal__paragraph">${producto.descripcion}</p>

                <div class="form-floating mb-3">
                    <input type="number" class="form-control ${producto.codigo}_data" name="cantidad" value="1" >
                    <label for="floatingInput">Cantidad</label>
                    <span class="text-danger  w-90"></span>
                </div>

                <div class="form-floating mb-3">
                    <input type="number" class="form-control ${producto.codigo}_data" name="precio" value="${producto.pvp}" >
                    <label for="floatingInput">Precio</label>
                    <span class="text-danger  w-90"></span>
                </div>
                

                <div class="form-floating mb-3 acciones-pvp">
                    <p class="fs-6"> Costo: ${producto.costo} </p>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pvp" id="pvp" value="${producto.pvp}" >
                        <label class="form-check-label" for="pvp">Detal | ${producto.pvp} USD</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pvp_2" id="pvp_2" value="${producto.pvp_2}" ${producto.pvp_2 ? producto.pvp_2 : 'disabled'}>
                        <label class="form-check-label" for="pvp_2"> PVP 2 | ${producto.pvp_2} USD</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="pvp_3" id="pvp_3" value="${producto.pvp_3}" ${producto.pvp_3 ? producto.pvp_3 : 'disabled'}>
                        <label class="form-check-label" for="pvp_3"> PVP 3 |  ${producto.pvp_3} USD</label>
                    </div>
                </div>

                <div class="form-floating mb-3">
                    <input type="submit" class="btn btn-primary agregar-producto" id="agregarProductoAlCarrito" name="${producto.codigo}" value="Agregar a factura" />
                    <p class="btn btn-danger m-1 cerrar__ModalCustom" id="cerrarModalCustom"> SALIR </p>
                </div>


               
            </div>
        </section>
    `;
};

const componenteListaDeProductoEnFactura = (producto) => {
    if (producto.estatus == 0) {
        return `
        <tr>
            <td colspan="5" class="text-center text-danger ">NO HAY RESULTADOS</td>
        </tr>
        `;
    } else {
        return `
        <tr>
                <td>${producto.codigo_producto}</td>
                <td>${producto.descripcion}</td>
                <td>${producto.cantidad}</td>
                <td>${producto.costo}</td>
                <td>${producto.subtotal}</td>
                <td class="d-flex d-inline">
                    <button class="btn btn-none acciones-factura" id="editarCantidadFactura" name="${producto.codigo_producto}">
                        <i class="bi bi-pencil fs-4"></i>
                    </button>
                    <button class="btn btn-none acciones-factura" id="eliminarProductoFactura" name="${producto.codigo_producto}">
                        <i class="bi bi-trash fs-4"></i>
                    </button>
                </td>
            </tr>
        `;
    }
};

const componenteNumeroDeFactura = (data, nombre) =>{
    // log(data)
    return `<i class="bi bi-back"></i> N° ${nombre}: ${data.data}`;
};

const componenteFactura = async (factura) => {
    return `
        <div class="col-sm-6 form-floating mb-3">
            <input type="number" class="form-control acciones-factura" id="editarDescuento" >
            <label for="floatingInput">Descuento %</label>
        </div>
    
        <!--<div class="col-sm-3 form-floating mb-3">
            <input type="number" class="form-control bg-secondary-light" readonly id="floatingInput" placeholder="16" value="16">
            <label for="floatingInput">IVA %</label>
        </div>-->
        <div class="col-sm-6 ">
            <label for="">F. Fiscal</label> <br>
            <div class="form-check form-check-inline">
                <input class="form-check-input acciones-factura" type="radio" name="inlineRadioOptions" id="activarFacturaFiscal" value="si" ${factura.iva > 0 ? 'checked': ''}>
                <label class="form-check-label" for="inlineRadio1">SI</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input acciones-factura" type="radio" name="inlineRadioOptions" id="desactivarFacturaFiscal" value="no" ${factura.iva == 0 ? 'checked': ''}>
                <label class="form-check-label" for="inlineRadio2">NO</label>
            </div>
            <!--<label for="">Fiscal</label>
            <input type="checkbox" class="form-check acciones-factura" id="desactivaIva" value="1" ${factura.iva > 0 ? '': 'checked'}>-->
        </div>

        <div class="col-sm-2 text-black">
            <p>SUBTOTAL</p>
            <p>IVA ${ factura.iva == "" ? 16 : (factura.iva * 100).toFixed(2) }%</p>
            <p>DESCT.  ${factura.descuento == "" ? 0 : factura.descuento}%</p>
        </div>
        <div class="col-sm-4 ">
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero( factura.subtotal ) } USD </p>
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero( factura.subtotal * factura.iva ) } USD</p>
            <p>${ factura.descuento == "" ? 0 : darFormatoDeNumero( factura.subtotal * (factura.descuento/100) ) } USD</p>
        </div>
        <div class="col-sm-6">
            <p>TOTAL</p>
            <p class="fs-1 text-success">${ factura.total == "" ? 0 : (  darFormatoDeNumero(parseFloat(factura.total))) } USD</p>
            
        </div>

        <div class="col-sm-6">
            <button class="btn btn-success  w-100 fs-3 acciones-factura" data-bs-toggle="modal" data-bs-target="#staticBackdrop" id="cargarModalMetodoPago"  >
                <i class='bx bx-cart '></i> VENDER
            </button>
        </div>
        <div class="col-sm-6">
            <button class="btn btn-danger  w-100 fs-3 acciones-factura"  id="eliminarFactura">
                <i class='bx bx-trash '></i> ELIMINAR
            </button>
        </div>
    `;
};


const componenteVueltoForm = () => {
    return `
        <div class="metodoAdd mt-2 row g-3">
            <div class="col-md-6">
                <select class="form-select acciones-pagos" id="tipoVuelto" >
                    <option selected>Método de pago</option>
                    <option value="EFECTIVO">EFECTIVO</option>
                    <option value="DIVISAS">DIVISAS</option>
                    <option value="PAGO MOVIL">PAGO MOVIL</option>
                    <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                </select>
            </div>

            <div class="col-md-4">
                <input type="number" step="any" class="form-control metodoPago acciones-pagos" id="vuelto" >
            </div>

            <div class="col-md-2 ">
                <i class='bx bx-trash text-danger fs-3 acciones-pagos' id="eliminarMetodo"></i>
            </div>
        </div>
    `; 
};

const componenteVuelto = async (metodos, factura) => {
    let abonado = 0,
    mensajeVuelto = "",
    estilos = "",
    vuelto = 0;
  
    metodos.forEach(elementoAbono => {
        if(elementoAbono.tipoDePago == "DIVISAS" ) abonado += quitarFormato(darFormatoDeNumero(elementoAbono.montoDelPago));
        else abonado = ( abonado + ( quitarFormato(darFormatoDeNumero(elementoAbono.montoDelPago)) / quitarFormato(darFormatoDeNumero(factura.tasa)) ) );
     
    });
   

    vuelto = quitarFormato(darFormatoDeNumero(factura.total)) - quitarFormato(darFormatoDeNumero(abonado));


    if(vuelto > 0){
        estilos = "text-danger";
        mensajeVuelto = "PENDIENTE";
    }else if(vuelto < 0){
        estilos = "text-success";
        mensajeVuelto = "VUELTO O CAMBIO";
    }else{
        estilos = "text-success";
        mensajeVuelto = "PAGO COMPLETADO";
    }

    return `
        <div class="card m-2" style="">
            <div class="card-header fs-2 text-center">${mensajeVuelto}</div>
            <div class="card-body ${estilos}">
                <p class="card-text fs-1 text-center">REF ${ darFormatoDeNumero(vuelto) } <br> BS ${darFormatoDeNumero(vuelto * factura.tasa) }  </p>
            </div>
        </div>
    `;
};

const componenteMetodosForm = async (metodosPagos, factura) => {

    let listaMetodos = '',
    metodoSeleccionado = "",
    total=0;

    if(factura.total){
        total =  quitarFormato( darFormatoDeNumero( factura.total * factura.tasa ) ) ;
    }

  
        metodosPagos.forEach(elementoPago => {
            metodoSeleccionado = elementoPago.tipoDePago ? `<option value="${elementoPago.tipoDePago}" selected>${elementoPago.tipoDePago}</option>`
            : `<option selected>Método de pago</option>`; 

            if( metodosPagos.length == 1 || elementoPago.tipoDePago == null ){
                listaMetodos += `
                    <div class="metodoAdd mt-2 row g-3">
                        <div class="col-md-6">
                            <select class="form-select acciones-pagos" id="tipoDePago" >
                                <option selected>Método de pago</option>
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="DIVISAS">DIVISAS</option>
                                <option value="PAGO MOVIL">PAGO MOVIL</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="TD">TD | PUNTO</option>
                                <option value="TC">TC | PUNTO</option>
                            </select>
                            
                        </div>
        
                        <div class="col-md-4">
                            <input type="number" step="any" class="form-control metodoPago acciones-pagos" 
                            value="${ elementoPago.montoDelPago ? elementoPago.montoDelPago : 0 }" id="montoDelPago" >
                        </div>
        
                        <div class="col-md-2" id="${elementoPago.id}">
                            <i class='bx bx-plus-circle text-success fs-3 acciones-pagos' id="agregarMetodo"></i>
                        </div>
                    </div>
                `;
            }else{
    
                listaMetodos += `
                    <div class="metodoAdd mt-2 row g-3">
                        <div class="col-md-6">
                            <select class="form-select acciones-pagos" id="tipoDePago" >
                                ${ metodoSeleccionado }
                                <option value="EFECTIVO">EFECTIVO</option>
                                <option value="DIVISAS">DIVISAS</option>
                                <option value="PAGO MOVIL">PAGO MOVIL</option>
                                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                <option value="TD">TD | PUNTO</option>
                                <option value="TC">TC | PUNTO</option>
                            </select>
                        </div>
        
                        <div class="col-md-4">
                            <input type="number" step="any" class="form-control metodoPago acciones-pagos" 
                            value="${elementoPago.montoDelPago}" id="montoDelPago" >
                        </div>
        
                        <div class="col-md-2 " id="${elementoPago.id}">
                            <i class='bx bx-trash text-danger fs-3 acciones-pagos' id="eliminarMetodo"></i>
                        </div>
                    </div>
                `;
            }
        });
    

    return listaMetodos; 
};


const componenteMetodoDePago  = async (factura) => {
    return `
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Agregar metodos de pago</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body" >
                        <p class="fs-3 text-center text-success">Monto a pagar</p>
                        <p class="fs-1 text-center por-cobrar"> ${darFormatoDeNumero(factura.total * factura.tasa)} BS </p>
                        <p class="fs-3 text-center por-cobrar"> REF: ${darFormatoDeNumero(factura.total)} </p>

                        <div id="elementoMetodoDePago">
                        
                        </div>

                        <div id="elementoVuelto"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary acciones-factura" id="vender">Facturar</button>
                    </div>

                </div>
            </div>
        </div>
    `;
};


/** MANEJADORES DE EVENTOS */
const hanledLoad = async (e) => {
    let resultadoCliente = 0;
    /** CLIENTE */
    elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([], "");
    cargarEventosAccionesDelCliente();
    
    /** FILTRO PRODUCTOS */
    elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
    elementoTablaBuscarProducto.innerHTML = componenteListaDeProductoFiltrados({estatus:0});
    
    /** Numero de MOVIMIENTO DE INVENTARIO */ 
    let numeroMovimiento =  await getCodigoFactura(`${URL_BASE}/getCodigoFactura/factura_inventarios`);
    codigoFactura.innerHTML = componenteNumeroDeFactura(numeroMovimiento, 'Movimiento');

    /** Numero de factura */ 
    let resultadoNumeroDeFactura =  await getCodigoFactura(`${URL_BASE}/getCodigoFactura/facturas`);
    codigoFactura.innerHTML += '<br>';
    codigoFactura.innerHTML += componenteNumeroDeFactura(resultadoNumeroDeFactura, 'Factura');

    // log(JSON.parse(localStorage.getItem('factura')))
    facturaStorage = JSON.parse(localStorage.getItem('facturaSalida'))

    if(facturaStorage){

        factura = facturaStorage;

        /** CLIENTE */
        elementoTarjetaCliente.innerHTML = spinner;
        listaDeProductosEnFactura.innerHTML = spinner;
        if(factura.identificacion.length != 0){
            resultadoCliente = await getCliente(factura.identificacion);
        }
        
        /** Validamos que existe el cliente */
        if(resultadoCliente == 0)  elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([], "");
        else elementoTarjetaCliente.innerHTML = componenteTarjetaCliente(resultadoCliente.data, "");
        cargarEventosAccionesDelCliente();
        
    }else{
        
        /** ALMACENAMOS LA FACTURA */
        factura.codigo = numeroMovimiento.data;
        factura.codigo_factura = resultadoNumeroDeFactura.data;
        factura.concepto = VENTA.name;
        factura.tipo = 'SALIDA';
        factura.iva = 0.16;
        let fecha = new Date();
        factura.fecha = `${fecha.getFullYear()}-${fecha.getMonth()}-${fecha.getDate()}`;
        localStorage.setItem( 'facturaSalida', JSON.stringify(factura) );
    }

    /** Cargamos el componente factura */
    elementoFactura.innerHTML = spinner;

    /** Validamos si el carrito tiene productos para cargarlos a la factura */
    carritoStorage =  localStorage.getItem('carritoSalida')  ? JSON.parse(localStorage.getItem('carritoSalida')) : [];
   
    if( carritoStorage.length ){
        /** Se carga la lista de productos */
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoStorage.reverse());
        
        /** Cargamos los datos de la factura */
        await cargarDatosDeFactura(carritoStorage, factura, factura.iva, factura.descuento);

    }else{
        /** Cargamos la lista vacia */
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito([]);
        localStorage.setItem('carritoSalida', JSON.stringify(carritoStorage));

        /** Cargamos los datos de la factura */
        log(factura)
        await cargarDatosDeFactura(carritoStorage, factura, factura.iva, factura.descuento);
    }

    let elementoVuelto = d.querySelector('#elementoVuelto');
}; /** HANLEDLOAD */

const hanledAccionesCliente = async (e) => {
    e.preventDefault();

    switch (e.target.parentElement.id) {
        case 'activarInputBuscarCliente':
            /** vaciamos los datos del cliente */
            vaciarDatosDelClienteDeLaFactura(factura);
            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([],'');
            cargarEventosAccionesDelCliente();
            break;
        case 'activarFormCrearCliente':
            /** vaciamos los datos del cliente */
            vaciarDatosDelClienteDeLaFactura(factura);

            /** Mostramos el componente de formulario para agregar un nuevo usuario */
            elementoTarjetaCliente.innerHTML = componenteFormularioAgregarCliente();
            cargarEventosAccionesDelCliente();
            cargarEventosDeFormularios();
            break;
        case 'activarFormEditarCliente':
            elementoTarjetaCliente.innerHTML = spinner;
            // log(e.target.parentElement.pathname.substring(1))
            let cliente = await getCliente(e.target.parentElement.pathname.substring(1));
            elementoTarjetaCliente.innerHTML = componenteFormularioEditarCliente(cliente.data);
            cargarEventosAccionesDelCliente();
            cargarEventosDeFormularios();
            break;
    
        default:
            break;
    }


};

const hanledBuscarCliente = async (e) => {
    if(e.key == "Enter" && e.target.id == "buscarCliente"){
       
        /** Validando los datos ingresados */
        if(!e.target.value.trim().length) return elementoTarjetaCliente.innerHTML = componenteTarjetaCliente({estatus: 0}, "El campo es obligatorio!");
        else if(!parseInt(e.target.value)) return elementoTarjetaCliente.innerHTML = componenteTarjetaCliente({estatus: 0}, "El campo solo acepta números!");
  
        
        // log(e.target.value)
        /** Se cargar el spinner para mostrar que esta procesando */
        elementoTarjetaCliente.innerHTML = spinner;
        let cliente = await getCliente( parseInt(e.target.value) );

        /** Validamos si no hay data del cliente */
        if(!cliente.data.length){
            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente({estatus: 0}, cliente.mensaje);
            cargarEventosAccionesDelCliente()
        }else{
            /** Cargamos la dat del cliente en el componentes tarjeta cliente */
            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente(cliente.data, cliente.mensaje);
            
            /** Seteamos el cliente en la factura de local storage */
            factura.identificacion = cliente.data[0].identificacion;
            factura.tipoDocumento = cliente.data[0].tipo;
            factura.razon_social = cliente.data[0].nombre;
            localStorage.setItem('facturaSalida', JSON.stringify(factura));
          
            // utilidad de cargar eventos de las acciones del cliente
            cargarEventosAccionesDelCliente()
        }
    }
};

const hanledFormulario = async (e) => {
    e.preventDefault();
    let resultado = '', 
    cliente = '';
    switch (e.target.id) {
        case 'formCrearCliente':
                    resultado = await validarDataDeFormularioCliente(e.target)
                    log(resultado)
                    if(!resultado) return;
                    e.target.innerHTML = spinner;
                    
                    cliente = await storeCliente(resultado);

                    log(cliente)
                    if(cliente.estatus == 401){
                        elementoTarjetaCliente.innerHTML = componenteFormularioAgregarCliente();
                        let elementoValidarFormCrearCliente = d.querySelector('#respuesta-de-validacion');
                        elementoValidarFormCrearCliente.innerHTML = componenteAlerta(cliente.mensaje, cliente.estatus)
                        cargarEventosAccionesDelCliente();
                        cargarEventosDeFormularios();
                        return setTimeout(()=>{
                            elementoValidarFormCrearCliente.innerHTML = '';
                        }, 1500);
                    }else{
                         /** Seteamos el cliente en la factura de local storage */
                            factura.identificacion = cliente.data.identificacion;
                            factura.tipoDocumento = cliente.data.tipo;
                            factura.razon_social = cliente.data.nombre;
                            localStorage.setItem('facturaSalida', JSON.stringify(factura));
                            
                        elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([cliente.data], cliente.mensaje);
                        cargarEventosAccionesDelCliente();
                        cargarEventosDeFormularios();  
                    }

        break;
        case 'formEditarCliente':
            resultado = await validarDataDeFormularioCliente(e.target)
            if(!resultado) return;
            e.target.innerHTML = spinner;
            log(resultado)
            cliente = await updateCliente(e.target.action, resultado);
            log(cliente)
               /** Seteamos el cliente en la factura de local storage */
               factura.identificacion = cliente.data[0].identificacion;
               factura.tipoDocumento = cliente.data[0].tipo;
               factura.razon_social = cliente.data[0].nombre;
               localStorage.setItem('facturaSalida', JSON.stringify(factura));

            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente(cliente.data, cliente.mensaje);
            cargarEventosAccionesDelCliente();
            cargarEventosDeFormularios();  

        break
    
        default:
            break;
    }


};

const hanledAgregarAFactura = async (e) => {
    e.preventDefault();

    switch (e.target.id) {
        case "cerrarModalCustom":
                /** CERRAMOS EL MODAL */
                e.target.parentElement.parentElement.parentElement.classList.remove('modal--show');
            break;
        case "pvp":
        case "pvp_2":
        case "pvp_3":
            /** Cambiamos el precio */
            e.target.parentElement.parentElement.parentElement.children[3].children[0].value = e.target.value;
            break;
    
        case "agregarProductoAlCarrito":
            let carritoActualizado = [],
            carritoActual = localStorage.getItem('carritoSalida') != 'undefined' ? JSON.parse(localStorage.getItem('carritoSalida')) : [],
            banderaDeAlertar = 0,
            banderaDeProductoNuevo = 0;
    
            /** Configuramos el filtro de productos Inventario */
            let filtro = {
                filtro: e.target.name,
                campo: ["codigo"]
            },
            resultado = await getInventariosFiltro(`${URL_BASE}/getInventariosFiltro`, filtro);

            if(resultado.estatus == 200){
                log('Producto Obtenido');
                /** Configuramos la clase y seleccionamos los datos de entrada del formulario */
                let  classIdentificadora = resultado.data.data[0].codigo + "_data",
                datosDeSalida = await d.getElementsByClassName(classIdentificadora),
                esquemaDeDatosDeSalida = {}; // tipo de dato OBJECT

                /** Validamos que la cantida sea agreagada y el costo y pvp detal */
                log(datosDeSalida)
                for (const datosSalida of datosDeSalida) {
                    if(datosSalida.name == "cantidad" || datosSalida.name == "precio" ){
        
                        if(datosSalida.value == "") banderaDeAlertar++, datosSalida.parentElement.children[2].textContent = `El campo ${datosSalida.name.toUpperCase()} es obligatorio.`;
                        else if(datosSalida.name == "precio" && parseFloat(datosSalida.value) < resultado.data.data[0].costo) banderaDeAlertar++, datosSalida.parentElement.children[2].textContent = `El ${datosSalida.name.toUpperCase()} debe ser mayor al costo.`;
                        else if(datosSalida.value <= 0) banderaDeAlertar++, datosSalida.parentElement.children[2].textContent = `El campo ${datosSalida.name.toUpperCase()} debe poseer un número mayor a cero.`;
                        else datosSalida.parentElement.children[2].textContent = "";
                    }
                }

                /** Si en el formulario de entrada hay algo mal paramos la ejecucion */
                if(banderaDeAlertar) return;

                /** Creamos el esquema de datos de entrada */
                for (const data of datosDeSalida) {
                    esquemaDeDatosDeSalida[data.name] = data.value;
                }

                log(esquemaDeDatosDeSalida);
                
                /** AÑADIMOS LA TASA DE VENTA A LA FACTURA */
                factura.tasa = resultado.tasa;
                localStorage.setItem('facturaSalida', JSON.stringify(factura));

                /** Validamos que la cantidad ingresada no sobrepase la del inventario */
                if(parseFloat(esquemaDeDatosDeSalida.cantidad) > parseFloat(resultado.data.data[0].cantidad) ){
                    elementoAlertas.innerHTML = componenteAlerta('No tiene SUFICIENTE STOCK para suplir el pedido, intente de nuevo.', 401); 
                    return setTimeout(()=>{
                        elementoAlertas.innerHTML="";
                    }, 3500)
                }

                /** Adaptamos el producto para añadirlo al carrito */
                productoAdaptado = adaptadorDeProductoACarrito(resultado.data.data[0], esquemaDeDatosDeSalida, factura);
             

                /** Si ya existe un carrito añadimos a ese carrito */
                if(carritoActual.length){
                    /** Recorremos el carrito para verificar si se añade un producto nuevo o se suma al existente */
                    carritoActualizado = carritoActual.map(producto => {
                        if(productoAdaptado.codigo_producto == producto.codigo_producto){
                            if(parseFloat(productoAdaptado.cantidad) + parseFloat(producto.cantidad) > parseFloat(producto.stock)) banderaDeAlertar++;
                            else {
                                producto.cantidad = parseFloat(productoAdaptado.cantidad) + parseFloat(producto.cantidad);
                                producto.subtotal =  darFormatoDeNumero( productoAdaptado.cantidad * quitarFormato( productoAdaptado.costo ) );
                                producto.subtotalBs = darFormatoDeNumero( productoAdaptado.cantidad * quitarFormato( productoAdaptado.costoBs ) );
                            }; 
                        }else if(productoAdaptado.codigo_producto != producto.codigo_producto){
                            banderaDeProductoNuevo++;
                        }
                        return producto;
                    });
    
                    /** Si se detecta que hay un producto nuevo se añade */
                    if(banderaDeProductoNuevo == carritoActual.length) carritoActualizado.push(productoAdaptado);
                    
                    /** Si hay un error damos respuesta */
                    if(banderaDeAlertar){
                        elementoAlertas.innerHTML = componenteAlerta('El prodcuto NO se agregó a la factura STOCK INSUFICIENTE', 404);
                        return setTimeout(()=>{
                            elementoAlertas.innerHTML="";
                        }, 2500);
                    }
                }else{
                    carritoActualizado.push(productoAdaptado);
                }
    
                /** Guardamos en localStorage */
                localStorage.setItem('carritoSalida', JSON.stringify(carritoActualizado.reverse()));
    
                /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
                carritoActual = JSON.parse(localStorage.getItem('carritoSalida'));
                elementoAlertas.innerHTML = componenteAlerta('El prodcuto se agregó a la factura', 200);
                
                /** CERRAMOS EL MODAL */
                e.target.parentElement.parentElement.parentElement.classList.remove('modal--show');
                setTimeout(()=>{
                    elementoAlertas.innerHTML="";
                }, 2500);
            
                /** Actualizamos la lista de productos en la factura */
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito( JSON.parse(localStorage.getItem('carritoSalida')) );
    
                /** Cargamos la factura y sus eventos de acciones del carrito de factura */
                await cargarDatosDeFactura(carritoActual, factura);
            }else{
                log(resultado.mensaje);
            }
            break;
    
        default:
            break;
    }

 

    if(e.target.id == "agregarProductoAlCarrito"){
       

    }
};

const hanledBuscarProducto = async (e) => {    
    
    if(e.key == "Enter"){
        let filtro = {
            filtro: `${e.target.value.trim()}`,
            campo: ['codigo', 'descripcion', 'default'],
        };

        if(filtro.filtro == "") return elementoTablaBuscarProducto.innerHTML = componenteListaDeProductoFiltrados({estatus:0}), elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
        
        elementoTotalProductos.innerHTML = spinner;
        elementoTablaBuscarProducto.innerHTML = '';
    
        let resultado = await getInventariosFiltro(`${URL_BASE}/getInventariosFiltro`, filtro),
        lista='';
    
        log(resultado);
        if(!resultado.data.data.length) return elementoTablaBuscarProducto.innerHTML += componenteListaDeProductoFiltrados({estatus:0}), elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
    
         resultado.data.data.forEach( async (producto) => {
            
            producto.tasa = resultado.tasa;
            lista += `${componenteListaDeProductoFiltrados(adaptadorDeProducto(producto))}`;
        });
   
        elementoTablaBuscarProducto.innerHTML = lista;
        elementoTotalProductos.innerHTML = `<p>Total resultados: ${resultado.data.total}</p>`;
        await cargarAccionesDelCustomModal();
        await cargarEventosDeAgregarProductoAFactura();
    }
    
};

/** Esta funcion maneja los eventos de la FACTURA */
const hanledAccionesDeCarritoFactura = async (e) => {
    e.preventDefault();
    /** Declaracion de variables */
    let cantidad = 0,
    carritoActualizado = [],
    carritoActual = JSON.parse(localStorage.getItem('carritoSalida')),
    accion='',
    banderaDeError = 0,
    facturaActual = '',
    acumuladorSubtotal = 0;
  
    if(e.target.localName == 'button'){
        codigoProducto = e.target.name;
        accion = e.target.id;
    }else if(e.target.localName == 'i'){
        codigoProducto = e.target.parentElement.name;
        accion = e.target.parentElement.id;
    }else if(e.target.localName == 'input'){
        accion = e.target.id;
    }

    switch (accion) {
        case 'editarCantidadFactura':
                cantidad = prompt('Ingrese nueva cantidad:');
            
                /** Validamos que la cantidad no este vacia */
                if(cantidad.trim().length == 0){
                    elementoAlertas.innerHTML = componenteAlerta('El campo cantidad es obligatorio, intente de nuevo.', 404); 
                    return setTimeout(()=>{
                        elementoAlertas.innerHTML="";
                    }, 3500);
                }else if(!parseInt(cantidad)){
                    elementoAlertas.innerHTML = componenteAlerta('El campo cantidad solo acepta números, intente de nuevo.', 401); 
                    return setTimeout(()=>{
                        elementoAlertas.innerHTML="";
                    }, 3500);
                }

                /** actualizamos el carrito */
                carritoActualizado = carritoActual.map(producto => {
                    if( parseFloat(cantidad) > parseFloat(producto.stock) ){
                        banderaDeError++;
                        return producto;
                    }
                    if(producto.codigo_producto == codigoProducto ) {
                        log(parseFloat(producto.costo));
                        producto.cantidad = parseFloat(cantidad);
                        producto.subtotal = darFormatoDeNumero( quitarFormato(producto.costo) * cantidad );
                        producto.subtotalBs = darFormatoDeNumero( cantidad * quitarFormato(producto.costoBs) );
                    };
                    return producto;
                });
            
                if(banderaDeError){
                    elementoAlertas.innerHTML = componenteAlerta('No hay SUFUCIENTE STOCK, intente de nuevo.', 401); 
                    return setTimeout(()=>{
                        elementoAlertas.innerHTML="";
                    }, 3500);
                }

               /** Guardamos en local el nuevo carrito */
                localStorage.setItem('carritoSalida', JSON.stringify(carritoActualizado.reverse()));
                /** Cargamos la lista del carrito de compra */
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoActualizado.reverse());
            

                 /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
                carritoActual = JSON.parse(localStorage.getItem('carritoSalida'));

                await cargarDatosDeFactura(carritoActual, factura);


            break;
        case 'eliminarProductoFactura':
                carritoActualizado = carritoActual.filter(producto => producto.codigo_producto != codigoProducto );
                log(carritoActualizado)
                localStorage.setItem('carritoSalida', JSON.stringify(carritoActualizado.reverse()));
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoActualizado.reverse());

                /** Actualizamos la factura */
                await cargarDatosDeFactura(carritoActualizado, factura);
            break;
        case 'eliminarFactura':
            /** validamos si hay factura para eliminar */
            facturaActual = localStorage.getItem('facturaSalida');
       
            if(facturaActual){
                /** Eliminamos la factura y el carrito del almacen local */
                    localStorage.removeItem('carritoSalida');
                    localStorage.removeItem('facturaSalida');
    
                elementoAlertas.innerHTML = componenteAlerta('La factura se elemino correctamente', 200);
                setTimeout(()=>{
                    elementoAlertas.innerHTML=spinner;
                    window.location.href = `${URL_BASE_APP}/inventarios/crearSalida`;
                }, 2500);
            }else{
                elementoAlertas.innerHTML = componenteAlerta('No hay factura creada para eliminar', 404);
                setTimeout(()=>{
                    elementoAlertas.innerHTML="";
                }, 2500);
            }

            break;
        case 'salirDelPos':
            /** validamos si hay factura para eliminar */
            facturaActual = localStorage.getItem('facturaSalida');
       
            if(facturaActual){
                /** Eliminamos la factura y el carrito del almacen local */
                    localStorage.removeItem('carritoSalida');
                    localStorage.removeItem('facturaSalida');
    
                elementoAlertas.innerHTML = componenteAlerta('La factura se elemino correctamente', 200);
                setTimeout(()=>{
                    elementoAlertas.innerHTML=spinner;
                    window.location.href = `${URL_BASE_APP}/inventarios`;
                }, 1500);
            }

            break;
        case 'cargarModalMetodoPago':
                if(factura.identificacion == ""){
                    
                  
                    return alert("Debes Ingresar un cliente para poder vender");
                    log(elementoMetodoDePagoModal)
                }
               
                log(elementoMetodoDePagoModal.children[0])
               await cargarEventosAccionesDeFactura()

            break;
        case 'vender':
            /** declaracion de variables */
            let abonado = 0,
            resultadoDefacturarCarrito = '';

            /** validamos si el cliente esta gregado a la factura  */
            if(factura.identificacion == "") {
                e.target.parentElement.innerHTML += componenteAlerta('Debe agregar un cliente para esta factura.', 401);
                let elementoAlertaVender = d.querySelectorAll('.alertaGlobal');
                return setTimeout( () => {
                    elementoAlertaVender.forEach(element => {
                        element.classList.add('d-none')
                    });
                    cargarEventosAccionesDeFactura();
                }, 2500);
            } 
            
            /** Previsualizacion de los datos */
            log(factura)
            log(factura.total);
            log(metodosPagos);

         
            /** Sumamos todos los metodos de pago */
            metodosPagos.forEach(elementoAbono => {
                if(elementoAbono.tipoDePago == "DIVISAS" ) abonado += quitarFormato(darFormatoDeNumero(elementoAbono.montoDelPago));
                else abonado += ( quitarFormato(darFormatoDeNumero(elementoAbono.montoDelPago)) / quitarFormato(darFormatoDeNumero(factura.tasa)) ) ;
            });

            /** Previsualizamos el monto abonado */
            log(abonado)

                /** Validamos si el monto es mayor o igual al total a pagar */
                if( quitarFormato(darFormatoDeNumero(abonado)) >= quitarFormato(darFormatoDeNumero(factura.total)) ){
                    
                    /** Agregamos los metodos en formato JSON a la factura */
                    factura.metodos = JSON.stringify(metodosPagos);
                  
                    /** Guardamos los cambios en localStorage */
                    localStorage.setItem('facturaSalida', JSON.stringify(factura));
    
                    /** Obtenemos el carrito y la factura actualizados */
                    let facturaVender = JSON.parse(localStorage.getItem('facturaSalida')),
                    carritoVender = JSON.parse(localStorage.getItem('carritoSalida'));

                    /** Al procesar la facturacion del carrito descontamos del inventario las cantidades */
                    carritoVender.forEach(async producto => {
                        producto.identificacion = facturaVender.identificacion;
                        producto.subtotal = quitarFormato(producto.subtotal);
                        await facturarCarrito(`${URL_BASE}/facturarCarritoSalida`, producto);
                    });
                  
                    /** Procesamos la factura y generamos el ticket */
                    resultadoDeFacturar = await setFactura(`${URL_BASE}/setFacturaSalida`, facturaVender);
                    log(resultadoDeFacturar);

                    /** Mostramos el dialogo de facturar */
                     if (resultadoDeFacturar.estatus == 201) {
                        log('entro aqui en la impresion de la factura')
                        resultado = confirm("Factura procesada correctamente, ¿Deseas imprimir el comprobante?");
                        if (resultado) {

                            imprimirElemento(htmlTicketSalidaV1(resultadoDeFacturar.data));
                            
                            resultadoOtraCapia = confirm("¿Deseas imprimir otra copia del comprobante?");
                            if (resultadoOtraCapia) {
                                imprimirElemento(htmlTicketSalidaV1(resultadoDeFacturar.data));
                            }
                            /** Eliminamos la factura del Storagr */
                            localStorage.removeItem('carritoSalida');
                            localStorage.removeItem('facturaSalida');
                            window.location.href = "http://cyberstock.com/inventarios/crearSalida";

                        } else {
                             /** Eliminamos la factura del Storagr */
                             localStorage.removeItem('carritoSalida');
                             localStorage.removeItem('facturaSalida');
                            window.location.href = "http://cyberstock.com/inventarios/crearSalida";
                        }
                    } else {
                        alert(resultadoDeFacturar.mensaje)
                    }

                }else{
                    e.target.parentElement.innerHTML += componenteAlerta('Debe cumplir con el pago para procesar la factura.', 401);
                    let elementoAlertaVender = d.querySelectorAll('.alertaGlobal');
                    return setTimeout( () => {
                        elementoAlertaVender.forEach(element => {
                            element.classList.add('d-none')
                        });
                        cargarEventosAccionesDeFactura();
                    }, 2500);
                }


            break;
        case 'desactivarFacturaFiscal':
            log(e.target.value)
            cargarDatosDeFactura(carritoActual, factura, 0, factura.descuento);
            break;
        case 'activarFacturaFiscal':
            log(e.target.value)
            cargarDatosDeFactura(carritoActual, factura, 0.16, factura.descuento);
            break;
        case 'editarDescuento':
            log(e.target.value)
            cargarDatosDeFactura(carritoActual, factura, factura.iva, e.target.value);
            break;
        default:
            break;
    } 
};

const hanledAccionesDeMetodoDePago = async (e) => {
    log(e.target.id)
    let accion = e.target.id,
    elementoMetodoDePago = d.querySelector('#elementoMetodoDePago'),
    metodosActuales = d.querySelectorAll('.metodoAdd'),
    arregloDeMetodosDePago =[],
    contadorID = 1,
    banderaDeError = 0,
    pendiente = 0;

    switch (accion) {
        case 'agregarMetodo':
            if(metodosActuales.length >= 4) return alert('El limite de metodos de pago son 4.')

                metodosActuales.forEach((element, index) => {
                    arregloDeMetodosDePago.push({
                        id: contadorID,
                        tipoDePago: element.children[0].lastElementChild.value,
                        montoDelPago: element.children[1].lastElementChild.value,
                    });
                    if(arregloDeMetodosDePago[index].tipoDePago == "Método de pago" || arregloDeMetodosDePago[0].tipoDePago == null){
                        banderaDeError++;
                    } 
                    contadorID++;
                });

                if(banderaDeError) return alert('Debe seleccionar un metodo de pago, si desea agregar otro.');

                arregloDeMetodosDePago.push({
                    id: contadorID + 1,
                    tipoDePago: null,
                    montoDelPago: 0,
                });
                log(arregloDeMetodosDePago)
                metodosPagos = arregloDeMetodosDePago;
                log(metodosPagos)
                elementoMetodoDePago.innerHTML = await componenteMetodosForm(arregloDeMetodosDePago.reverse(), factura);
                await cargarEventosAccionesDeFactura();
            break;
        case 'eliminarMetodo':
                // elementoMetodoDePago.innerHTML += componenteMetodosForm();
                log(e.target.parentElement.id);
                metodosActuales.forEach(element => {
                    if(element.children[2].id  != e.target.parentElement.id){
                        arregloDeMetodosDePago.push({
                            id: contadorID,
                            tipoDePago: element.children[0].lastElementChild.value,
                            montoDelPago: element.children[1].lastElementChild.value,
                        });
                    }
                    contadorID++;
                });
                metodosPagos = arregloDeMetodosDePago; 
                elementoMetodoDePago.innerHTML = await componenteMetodosForm(arregloDeMetodosDePago.reverse(), factura);
                elementoVuelto.innerHTML = await componenteVuelto(metodosPagos.reverse(), factura);
                await cargarEventosAccionesDeFactura();
            break;
        case 'tipoDePago':
            
            if(metodosActuales.length == 1){
                if(e.target.value == "DIVISAS" ) e.target.parentElement.parentElement.children[1].lastElementChild.value = quitarFormato(darFormatoDeNumero(factura.total));
                else e.target.parentElement.parentElement.children[1].lastElementChild.value = quitarFormato(darFormatoDeNumero(factura.total * factura.tasa));
            }
           
            metodosActuales.forEach(element => {
                arregloDeMetodosDePago.push({
                    id: contadorID,
                    tipoDePago: element.children[0].lastElementChild.value,
                    montoDelPago: element.children[1].lastElementChild.value,
                });
                contadorID++;
            });

            metodosPagos = arregloDeMetodosDePago; 
            elementoVuelto.innerHTML = await componenteVuelto(metodosPagos.reverse(), factura);
            break;
        case 'montoDelPago':
            /** obtener el ID del elemento tipo de pago para actualizar el monto ingresado  */
            // log(e.target.parentElement.parentElement.children[2].lastElementChild.id)
            metodosActuales.forEach(element => {
                if(e.target.parentElement.parentElement.children[2].lastElementChild.id == element.id){
                    arregloDeMetodosDePago.push({
                        id: contadorID,
                        tipoDePago: element.children[0].lastElementChild.value,
                        montoDelPago:e.target.value,
                    });
                    
                }else{
                    arregloDeMetodosDePago.push({
                        id: contadorID,
                        tipoDePago: element.children[0].lastElementChild.value,
                        montoDelPago: element.children[1].lastElementChild.value,
                    });
                }
                
                contadorID++;
            });
            metodosPagos = arregloDeMetodosDePago;
            elementoVuelto.innerHTML = await componenteVuelto(metodosPagos.reverse(), factura);

            break;
        
        default:
            break;
    }


};

/** EVENTOS */
addEventListener('load', hanledLoad);

elementoTarjetaCliente.addEventListener('keyup', hanledBuscarCliente);
elementoBuscarProducto.addEventListener('keyup', hanledBuscarProducto);


/** ULTILIDADES */

function cargarEventosAccionesDelCliente(){
    let accionesDelCliente = d.querySelectorAll('.acciones-cliente');
    accionesDelCliente.forEach(accionesCliente => {
        accionesCliente.addEventListener('click', hanledAccionesCliente);
    });
};

/** Esta funcion obtiene todos los formularios de la vista */
async function cargarEventosDeFormularios(){
    let formularios = d.forms;
    for (const iterator of formularios) {
        iterator.addEventListener('submit', hanledFormulario);
    }
};

/** Validar formulario de cliente y retornar data */
async function validarDataDeFormularioCliente(formulario){
    let esquema = {},
    banderaDeAlertar = 0;
    for (const iterator of formulario) {
        if(iterator.localName == "input" || iterator.localName == "select"){
            if(!iterator.value.length) iterator.classList.add(['border-danger']), banderaDeAlertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            else iterator.classList.remove(['border-danger']), iterator.nextElementSibling.textContent=`${iterator.name}`, iterator.classList.add(['border-success']);
            if(iterator.value == "Tipo de documento") iterator.classList.add(['border-danger']), banderaDeAlertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            // Asignamos el valor al esquema
            esquema[iterator.name] = iterator.value;
        }
    }
    if(banderaDeAlertar) return false;
    else return esquema;
}

function adaptadorDeProducto(data){
    return {
        id: data.id,
        numero: data.id,
        codigo: data.codigo,
        descripcion: data.descripcion,
        cantidad: data.cantidad,
        costo: darFormatoDeNumero(parseFloat( data.costo )),
        costoBs: darFormatoDeNumero(parseFloat( data.costo * data.tasa )),
        pvpBs: darFormatoDeNumero(parseFloat( (data.tasa * data.pvp) )),
        pvp:  darFormatoDeNumero(parseFloat(data.pvp)),
        pvpBs_2: darFormatoDeNumero(parseFloat( (data.tasa * data.pvp_2) )),
        pvp_2:  darFormatoDeNumero(parseFloat(data.pvp_2)),
        pvpBs_3: darFormatoDeNumero(parseFloat( (data.tasa * data.pvp_3) )),
        pvp_3:  darFormatoDeNumero(parseFloat(data.pvp_3)),
        marca: data.id_marca.nombre,
        imagen: data.imagen,
        fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
        categoria: data.id_categoria.nombre,
    };
};

async function cargarEventosDeAgregarProductoAFactura(){
    let elementoAgregarAFactura = d.querySelectorAll('.agregar-producto'),
    elementoCerrarModalCustom = d.querySelectorAll('.cerrar__ModalCustom'),
    elementoInputPrecioRadio = d.querySelectorAll('.acciones-pvp');
    log(elementoInputPrecioRadio);
    /** Cargamos los eventos de cerrar modal */
    elementoCerrarModalCustom.forEach(btnCerrarModal => {
        btnCerrarModal.addEventListener('click', hanledAgregarAFactura);
    });

    /** Cargamos los eventos de agregar al carrito */
    elementoAgregarAFactura.forEach(inputAgregarCantidad => {
        inputAgregarCantidad.addEventListener('click', hanledAgregarAFactura);
    });

    /** Cargamos los eventos de Cambiar precio */
    elementoInputPrecioRadio.forEach(elementoPrecio => {
        elementoPrecio.addEventListener('click', hanledAgregarAFactura);
    });
};

async function cargarEventosAccionesDeFactura(){
    let elementoAccionesCarritoFactura = d.querySelectorAll('.acciones-factura'),
    elementoMetodoDePagoAcciones = d.querySelectorAll('.acciones-pagos');
    elementoAccionesCarritoFactura.forEach(acciones => {
        if(acciones.localName == "input") acciones.addEventListener('change', hanledAccionesDeCarritoFactura);
        else acciones.addEventListener('click', hanledAccionesDeCarritoFactura);
    });

    elementoMetodoDePagoAcciones.forEach(element => {
        if(element.localName == "input") element.addEventListener('change', hanledAccionesDeMetodoDePago);
        else if(element.localName == "select") element.addEventListener('change', hanledAccionesDeMetodoDePago);
        else element.addEventListener('click', hanledAccionesDeMetodoDePago);
    });
};

function removerEstilosDelElemento(elementos, estilo){
    elementos.forEach(elemento => {
        elemento.classList.remove(estilo);
    });
};

function adaptadorDeProductoACarrito(producto, dataSalida, factura){
    return {
        codigo: factura.codigo, // Codigo de movimiento
        codigo_factura: factura.codigo_factura, // Codigo de movimiento
        codigo_producto: producto.codigo,
        descripcion: producto.descripcion,
        identificacion: factura.identificacion,
        cantidad: dataSalida.cantidad,
        stock: producto.cantidad,
        costo: dataSalida.precio, // costo/pvp en dolares 
        costoBs: darFormatoDeNumero(parseFloat(dataSalida.precio  * factura.tasa)), // costo/pvp en bolivares
        subtotal: darFormatoDeNumero(parseFloat(dataSalida.precio  * dataSalida.cantidad)), // subtotal en dolares
        subtotalBs: darFormatoDeNumero(parseFloat(dataSalida.precio  * dataSalida.cantidad * factura.tasa)), // subtotal en bolivares
    };

};

async function cargarListaDeProductoDelCarrito(carrito){
    let lista = '';
 
    if(carrito.length){
        carrito.forEach(producto => {
            lista += componenteListaDeProductoEnFactura(producto)
        });
        return lista;
    }else{
        return componenteListaDeProductoEnFactura({estatus:0});
    }
};

async function cargarDatosDeFactura(carritoActual, factura, iva = 0.16, descuento = 0){
    let acumuladorSubtotal = 0;
    carritoActual.forEach(producto => {
        acumuladorSubtotal = parseFloat(acumuladorSubtotal) + quitarFormato(producto.subtotal); 
    });
    log(acumuladorSubtotal)
    factura.iva = iva; 
    factura.subtotal = acumuladorSubtotal;
    factura.descuento = descuento;
    factura.total = (parseFloat( (acumuladorSubtotal * factura.iva) + acumuladorSubtotal) - parseFloat(acumuladorSubtotal * (factura.descuento/100)));
    
    localStorage.setItem('facturaSalida', JSON.stringify(factura));

    /** Recargamos el componente factura */
    elementoFactura.innerHTML = spinner;
    elementoFactura.innerHTML = await componenteFactura(factura);

       /** Cargamos el modal de metodos de pago */
       elementoMetodoDePagoModal.innerHTML = await componenteMetodoDePago(factura);

       /** Obtenemos el elemento del componente M-pagos */
       let elementoMetodoDePago = d.querySelector('#elementoMetodoDePago');
       console.log(metodosPagos);
       elementoMetodoDePago.innerHTML = await componenteMetodosForm(metodosPagos, factura);

    await cargarEventosAccionesDeFactura()

};

function vaciarDatosDelClienteDeLaFactura(factura){
    factura.identificacion = "";
    factura.razon_social = "";
    factura.tipo = "";
    localStorage.setItem('facturaSalida', JSON.stringify(factura));
};