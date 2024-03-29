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
        codigo:'', // codigo del movimiento
        codigo_factura:'', // codigo de la factura adel proveedor
        razon_social:'', // nombre de cliente o proveedor
        identificacion:'', // numero de documento
        subtotal:'', // se guarda en divisas
        total:'',
        tasa:'', // tasa en el momento que se hizo la transaccion
        iva:'', // impuesto
        tipo:'ENTRADA', // 
        concepto:'', // venta, compra ...
        descuento:'', // descuento
        fecha:'', // fecha venta, compra ...
        observacion:'', // fecha venta, compra ...
        tipoDocumento:'', // fecha venta, compra ...
        metodos:'' // metodos alamacenados como JSON
},
metodosPagos = [{
    id: 1,
    tipoDePago: null,
    montoDelPago: 0,
}];

/** COMPONENTES */
/** CLIENTE - PROVEEDOR */
const componenteTarjetaCliente = (proveedor, mensaje) => {
    if(proveedor.length){
        proveedor = proveedor[0];
        return `
        <div class="card-body">
            <h5 class="card-title text-danger">Proveedor N°: ${proveedor.id}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Datos del proveedor</h6>
            <p class="card-text">
                <b>Empresa:</b> ${proveedor.empresa} <br><br>
                <b>Rif o ID:</b> ${proveedor.tipo_documento}-${proveedor.codigo} <br><br>
            </p>
            <a href="#" class="card-link me-3 acciones-cliente" id="activarInputBuscarCliente">
                <i class="bi bi-search fs-4"></i>
            </a>
            <a href="${proveedor.codigo}" class="card-link me-3 acciones-cliente" id="activarFormEditarCliente">
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
            <h5 class="card-title text-danger">
                <i class='bx bx-buildings'></i>
                Proveedor
            </h5>
            
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
                <input type="text" class="form-control" name="empresa" id="floatingInput" placeholder="Ingrese nombre y apellido">
                <label for="floatingInput">Nombre y apelldio</label>
                <div class="text-danger validate"></div>
            </div>
            <div class="form-floating m-2">
                <select class="form-select" id="floatingSelect" name="tipo_documento" aria-label="Floating label select example">
                <option selected>Tipo de documento</option>
                <option value="V">V</option>
                <option value="E">E</option>
                <option value="J">J</option>
                </select>
                <label for="floatingSelect">Seleccione tipo de documento</label>
                <div class="text-danger validate"></div>
            </div>
            <div class="form-floating m-2">
                <input type="number" class="form-control" name="codigo" id="floatingInput" placeholder="Ingrese número de identificación.">
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

const componenteFormularioEditarCliente = (proveedor) => {
    if(proveedor.length){
        proveedor = proveedor[0];
    
        return `
        <div class="card-header p-0">
        <p class="text-danger  card-title text-center">
            <i class="bi bi-person-add"></i>
            Editar proveedor
        </p>
        </div>
        <div class="card-body p-0">
            <form action="${URL_BASE}/updateProveedor/${proveedor.id}" method="post" id="formEditarCliente">
            
                <div class="form-floating m-2">
                    <input type="text" class="form-control" name="empresa" value="${proveedor.empresa}" id="floatingInput" placeholder="Ingrese nombre y apellido">
                    <label for="floatingInput">Nombre y apelldio</label>
                    <div class="text-danger validate"></div>
                </div>
                <div class="form-floating m-2">
                    <select class="form-select" id="floatingSelect" name="tipo_documento" aria-label="Floating label select example">
                    value="${proveedor.tipo_documento ? `<option value="${proveedor.tipo_documento}">${proveedor.tipo_documento}</option>` : `<option selected>Tipo de documento</option>`}"
                    <option value="V">V</option>
                    <option value="E">E</option>
                    <option value="J">J</option>
                    </select>
                    <label for="floatingSelect">Seleccione tipo de documento</label>
                    <div class="text-danger validate"></div>
                </div>
                <div class="form-floating m-2">
                    <input type="number" class="form-control" name="codigo" value="${proveedor.codigo}" id="floatingInput" placeholder="Ingrese número de identificación.">
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
}; /** CIERRE CLIENTE - PROVEEDOR */

/** FILTRO DE PRODUCTOS - AGREGAR AL CARRITO */
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
            <td>${producto.marca}</td>    
            <td>${producto.categoria}</td>
        
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
                <p class="modal__paragraph">En existencia: ${producto.stock}</p>

                <div class="form-floating mb-1">
                    <input type="number" step="any" class="form-control ${producto.codigo}_data" name="cantidad" value="1" >
                    <label for="floatingInput">Cantidad de ingreso</label>
                    <span class="text-danger"></span>
                </div>

                <div class="form-floating mb-0">
                    <input type="number" step="any" class="form-control ${producto.codigo}_data" name="costo" value="${producto.costo}" >
                    <label for="floatingInput">costo</label>
                    <span class="text-danger  w-90"></span>
                </div>

                <div class="form-floating mb-0">
                    <input type="number" step="any" class="form-control ${producto.codigo}_data" name="pvp" value="${producto.pvp}" >
                    <label for="floatingInput">PVP Detal</label>
                    <span class="text-danger w-90"></span>
                </div>

                <div class="form-floating mb-1">
                    <input type="number" step="any" class="form-control ${producto.codigo}_data" name="pvp_2" value="${producto.pvp_2}" >
                    <label for="floatingInput">PVP 2</label>
                </div>

                <div class="form-floating mb-1">
                    <input type="number" step="any" class="form-control ${producto.codigo}_data" name="pvp_3" value="${producto.pvp_3}" >
                    <label for="floatingInput">PVP 3</label>
                </div>

                <div class="form-floating ">
                    <input type="submit" class="btn btn-primary agregar-producto" id="agregarProductoAlCarrito" name="${producto.codigo}" value="Agregar a factura" />
                    <p class="btn btn-danger m-1 cerrar__ModalCustom" id="cerrarModalCustom"> SALIR </p>
                </div>
                
            </div>
        </section>
    `;
}; /** CIERRE FILTRO DE PRODUCTOS - AGREGAR AL CARRITO */


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
                <td>${darFormatoDeNumero( producto.costo )}</td>
                <td>${darFormatoDeNumero( producto.subtotal )}</td>
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

const componenteNumeroDeFactura = (data) =>{
    return `<i class="bi bi-back"></i> N° Movimiento: ${data.data}`;
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
        <div class="col-sm-3 ">
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

        <div class="col-sm-3 ">
            <div class="form-floating">
                <select class="form-select acciones-factura" name="concepto_entrada" id="concepto_entrada" aria-label="Tipo de entrada">
                    <option value="${factura.concepto}" selected>${factura.concepto}</option>
                    <option value="COMPRA">COMPRA</option>
                    <option value="CREDITO">CREDITO</option>0
                    <option value="DEVOLUCION">DEVOLUCIÓN</option>0
                </select>
                <label for="concepto_entrada">Concepto de entrada</label>
            </div>
        </div>

        <div class="col-sm-2 text-black">
            <p>SUBTOTAL</p>
            <p>IVA ${ factura.iva == "" ? 16 : (factura.iva * 100).toFixed(2) }%</p>
            <p>DESCT.  ${factura.descuento == "" ? 0 : factura.descuento}%</p>
        </div>
        <div class="col-sm-4 ">
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero( factura.subtotal )} USD</p>
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero( factura.subtotal * factura.iva )} USD</p>
            <p>${ factura.descuento == "" ? 0 :  darFormatoDeNumero( factura.subtotal * (factura.descuento/100) )} USD</p>
        </div>
        <div class="col-sm-6">
            <p>TOTAL</p>
            <p class="fs-1 text-success">${ factura.total == "" ? 0 : (  darFormatoDeNumero(factura.total) ) } USD</p>
            <!--<p class="fs-5 text-success">REF ${ factura.total == "" ? 0 : factura.total }</p>-->
            
        </div>

        <div class="col-sm-6">
            <button class="btn btn-success  w-100 fs-3 acciones-factura" id="vender"  >
                <i class='bx bxl-shopify'></i> COMPRAR
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
                    <option value="BIO PAGO">BIO PAGO</option>
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
   
    vuelto = (Math.round(factura.total * 100)/100) - (Math.round(abonado*100)/100);

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
                                <option value="BIO PAGO">BIO PAGO</option>
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
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary acciones-factura" id="vender">Facturar Entrada</button>
                    </div>

                </div>
            </div>
        </div>
    `;
};

/** MANEJADORES DE EVENTOS */
const hanledLoad = async (e) => {
    let resultadoProveedor = 0;
    /** CLIENTE */
    elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([], "");
    cargarEventosAccionesDelCliente();
    
    /** FILTRO PRODUCTOS */
    elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
    elementoTablaBuscarProducto.innerHTML = componenteListaDeProductoFiltrados({estatus:0});
    
    /** Numero de factura */ 
    let resultado =  await getCodigoFactura(`${URL_BASE}/getCodigoFactura/factura_inventarios`);
    codigoFactura.innerHTML = componenteNumeroDeFactura(resultado);
    
    facturaStorage = JSON.parse(localStorage.getItem('facturaInventario'))

    if(facturaStorage){

        factura = facturaStorage;
        factura.codigo = resultado.data;
        factura.tipo = 'ENTRADA';
        let fecha = new Date();
        factura.fecha = `${fecha.getFullYear()}-${fecha.getMonth()+1}-${fecha.getDate()}T${fecha.getHours()}:${fecha.getMinutes()}:${fecha.getSeconds()}`;
        localStorage.setItem('facturaInventario', JSON.stringify(factura));

        /** CLIENTE */
        elementoTarjetaCliente.innerHTML = spinner();
        listaDeProductosEnFactura.innerHTML = spinner();
        if(factura.identificacion.length != 0){
            resultadoProveedor = await getProveedor(factura.identificacion);
        }
        
            /** Validamos que existe el cliente */
            if(resultadoProveedor == 0)  elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([], "");
            else elementoTarjetaCliente.innerHTML = componenteTarjetaCliente(resultadoProveedor.data, "");
            cargarEventosAccionesDelCliente();
        
            localStorage.setItem('facturaInventario', JSON.stringify(factura));
        
    }else{
        
        /** ALMACENAMOS LA FACTURA */
        factura.codigo = resultado.data;
        factura.concepto = 'COMPRA';
        factura.tipo = 'ENTRADA';
        factura.iva = 0.16;
        let fecha = new Date();
        factura.fecha = `${fecha.getFullYear()}-${fecha.getMonth()+1}-${fecha.getDate()}T${fecha.getHours()}:${fecha.getMinutes()}:${fecha.getSeconds()}`;
        localStorage.setItem('facturaInventario', JSON.stringify(factura));
    }

    /** Cargamos el componente factura */
    elementoFactura.innerHTML = spinner();

    /** Validamos si el carrito tiene productos para cargarlos a la factura */
    carritoStorage =  localStorage.getItem('carritoInventario')  ? JSON.parse(localStorage.getItem('carritoInventario')) : [];
   
    if( carritoStorage.length ){
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoStorage.reverse());

        /** Cargamos los datos de la factura */
        await cargarDatosDeFactura(carritoStorage, factura, factura.iva, factura.descuento);

    }else{
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito([]);
        localStorage.setItem('carritoInventario', JSON.stringify(carritoStorage));

        /** Cargamos los datos de la factura */
        await cargarDatosDeFactura(carritoStorage, factura, factura.iva, factura.descuento);
    }

    /** Volvelmos a asignar el codigo de factura proveedor */
    let elementoCodigoFacturaProveedor = d.querySelector('#codigoFacturaProveedor');
    if(factura.codigo_factura != "") elementoCodigoFacturaProveedor.value = factura.codigo_factura;

    let elementoVuelto = d.querySelector('#elementoVuelto');
}; /** HANLEDLOAD */

/** PROVEEDOR */
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
            elementoTarjetaCliente.innerHTML = spinner();

            let proveedor = await getProveedor(e.target.parentElement.pathname.substring(1));
            elementoTarjetaCliente.innerHTML = componenteFormularioEditarCliente(proveedor.data);
            cargarEventosAccionesDelCliente();
            cargarEventosDeFormularios();
            break;
    
        default:
            break;
    }


};

const hanledBuscarProveedor = async (e) => {
    if(e.key == "Enter" && e.target.id == "buscarCliente"){
       
        /** Validando los datos ingresados */
        if(!e.target.value.trim().length) return elementoTarjetaCliente.innerHTML = componenteTarjetaCliente({estatus: 0}, "El campo es obligatorio!");
        else if(!parseInt(e.target.value)) return elementoTarjetaCliente.innerHTML = componenteTarjetaCliente({estatus: 0}, "El campo solo acepta números!");
  
        /** Se cargar el spinner() para mostrar que esta procesando */
        elementoTarjetaCliente.innerHTML = spinner();
        let cliente = await getProveedor( e.target.value );

        /** Validamos si no hay data del cliente */
        if(!cliente.data.length){
            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente({estatus: 0}, cliente.mensaje);
            cargarEventosAccionesDelCliente()
        }else{
            /** Cargamos la dat del cliente en el componentes tarjeta cliente */
            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente(cliente.data, cliente.mensaje);
            
            /** Seteamos el cliente en la factura de local storage */
            factura.identificacion = cliente.data[0].codigo;
            factura.tipoDocumento = cliente.data[0].tipo_documento;
            factura.razon_social = cliente.data[0].empresa;
            localStorage.setItem('facturaInventario', JSON.stringify(factura));
            // utilidad de cargar eventos de las acciones del cliente
            cargarEventosAccionesDelCliente()
        }
    }
};

/** MANEJO DE LOS FORMULARIOS PROVEEDOR */
const hanledFormulario = async (e) => {
    e.preventDefault();
    let resultado = '', 
    cliente = '';
 
    switch (e.target.id) {
        case 'formCrearCliente':
                    resultado = await validarDataDeFormularioCliente(e.target)
                    if(!resultado) return;
                    e.target.innerHTML = spinner();
                    
                    proveedor = await storeProveedor(resultado);

                    if(proveedor.estatus == 401){
                        elementoTarjetaCliente.innerHTML = componenteFormularioAgregarCliente();
                        let elementoValidarFormCrearCliente = d.querySelector('#respuesta-de-validacion');
                        elementoValidarFormCrearCliente.innerHTML = componenteAlerta(proveedor.mensaje, proveedor.estatus)
                        cargarEventosAccionesDelCliente();
                        cargarEventosDeFormularios();
                        return setTimeout(()=>{
                            elementoValidarFormCrearCliente.innerHTML = '';
                        }, 1500);
                    }else{
                         /** Seteamos el proveedor en la factura de local storage */
                            factura.identificacion = proveedor.data.codigo;
                            factura.tipoDocumento = proveedor.data.tipo_documento;
                            factura.razon_social = proveedor.data.empresa;
                            localStorage.setItem('facturaInventario', JSON.stringify(factura));
                            
                        elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([proveedor.data], proveedor.mensaje);
                        cargarEventosAccionesDelCliente();
                        cargarEventosDeFormularios();  
                    }

        break;
        case 'formEditarCliente':
            resultado = await validarDataDeFormularioCliente(e.target)
            if(!resultado) return;
            e.target.innerHTML = spinner();
            proveedor = await updateProveedor(e.target.action, resultado);
               /** Seteamos el proveedor en la factura de local storage */
               factura.identificacion = proveedor.data[0].codigo;
               factura.tipoDocumento = proveedor.data[0].tipo_documento;
               factura.razon_social = proveedor.data[0].empresa;
               localStorage.setItem('facturaInventario', JSON.stringify(factura));

            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente(proveedor.data, proveedor.mensaje);
            cargarEventosAccionesDelCliente();
            cargarEventosDeFormularios();  

        break
    
        default:
            break;
    }


};
/** CIERRE PROVEEDOR */

const hanledAgregarAFactura = async (e) => {
    e.preventDefault();

    if(e.target.id == "cerrarModalCustom"){
           /** CERRAMOS EL MODAL */
           e.target.parentElement.parentElement.parentElement.classList.remove('modal--show');
    }

    if(e.target.id == "agregarProductoAlCarrito"){
        let carritoActualizado = [],
        carritoActual = localStorage.getItem('carritoInventario') != 'undefined' ? JSON.parse(localStorage.getItem('carritoInventario')) : [],
        banderaDeALertar = 0,
        banderaDeProductoNuevo = 0,
        acumuladorSubtotal = 0; 

        /** Configuramos el filtro de productos Inventario */
        let filtro = {
            filtro: e.target.name,
            campo: ["codigo"]
        },
        resultado = await getProductosFiltro(`${URL_BASE}/getProductosFiltro`, filtro);
       
        if(resultado.estatus == 200){
            /** Configuramos la clase y seleccionamos los datos de entrada del formulario */
            let  classIdentificadora = resultado.data.data[0].codigo + "_data",
            datosDeEntrada = await d.getElementsByClassName(classIdentificadora),
            esquemaDeDatosDeEntrada = {}; // tipo de dato OBJECT

           /** Validamos que la cantida sea agreagada y el costo y pvp detal */
            for (const datosEntrantes of datosDeEntrada) {
               if(datosEntrantes.name == "cantidad" || datosEntrantes.name == "costo" || datosEntrantes.name == "pvp"){
                    if(datosEntrantes.value == "") banderaDeALertar++, datosEntrantes.parentElement.children[2].textContent = `El campo ${datosEntrantes.name.toUpperCase()} es obligatorio.`;
                    else if(datosEntrantes.value <= 0) banderaDeALertar++, datosEntrantes.parentElement.children[2].textContent = `El campo ${datosEntrantes.name.toUpperCase()} debe poseer un número mayor a cero.`;
                    else datosEntrantes.parentElement.children[2].textContent = "";
                }
            }
            /** Si en el formulario de entrada hay algo mal paramos la ejecucion */
            if(banderaDeALertar) return;

            /** Creamos el esquema de datos de entrada */
            for (const data of datosDeEntrada) {
                esquemaDeDatosDeEntrada[data.name] = parseFloat(data.value);
            }

            /** AÑADIMOS LA TASA DE VENTA A LA FACTURA */
            factura.tasa = resultado.tasa;
            localStorage.setItem('facturaInventario', JSON.stringify(factura));
    
            /** Adaptamos el producto para añadirlo al carrito */
            productoAdaptado = adaptadorDeProductoACarrito(resultado.data.data[0], esquemaDeDatosDeEntrada, factura);
        
             /** Si ya existe un carrito añadimos a ese carrito */
             if(carritoActual.length){
                /** Recorremos el carrito para verificar si se añade un producto nuevo o se suma al existente */
                carritoActualizado = carritoActual.map(producto => {
                    if(productoAdaptado.codigo_producto == producto.codigo_producto){
                        if(parseFloat(productoAdaptado.cantidad) > 0) banderaDeALertar++;
                        else {
                            producto.cantidad = parseFloat(productoAdaptado.cantidad) + parseFloat(producto.cantidad);
                            producto.subtotal =  darFormatoDeNumero( producto.cantidad * quitarFormato(producto.costo) );
                            // producto.subtotalBs = darFormatoDeNumero( producto.cantidad * quitarFormato(producto.costoBs) );
                        }; 
                    }else if(productoAdaptado.codigo_producto != producto.codigo_producto){
                        banderaDeProductoNuevo++;
                    }
                    return producto;
                });

                /** Si se detecta que hay un producto nuevo se añade */
                if(banderaDeProductoNuevo == carritoActual.length) carritoActualizado.push(productoAdaptado);
                
            }else{
                /** si no hay nada en el carro solo agregamos */
                carritoActualizado.push(productoAdaptado)
            }

            
            /** Guardamos en localStorage */
            localStorage.setItem('carritoInventario', JSON.stringify(carritoActualizado.reverse()));

            /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
            carritoActual = JSON.parse(localStorage.getItem('carritoInventario'));
            elementoAlertas.innerHTML = componenteAlerta('El prodcuto se agregó a la factura', 200);
            
            /** CERRAMOS EL MODAL */
            e.target.parentElement.parentElement.parentElement.classList.remove('modal--show');
            setTimeout(()=>{
                elementoAlertas.innerHTML="";
            }, 2500);
        
            /** Actualizamos la lista de productos en la factura */
            listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito( JSON.parse(localStorage.getItem('carritoInventario')) );

            /** Cargamos la factura y sus eventos de acciones del carrito de factura */
            await cargarDatosDeFactura(carritoActual, factura, factura.iva, factura.descuento);
        }
    } // CIERRE agregarProductoAlCarrito
};

const hanledBuscarProducto = async (e) => {    
   
    if(e.key == "Enter"){
        let filtro = {
            filtro: `${e.target.value.trim()}`,
            campo: ['codigo', 'descripcion', 'default'],
            numeroDePagina: 100
        };
 

        if(filtro.filtro == "") return elementoTablaBuscarProducto.innerHTML = componenteListaDeProductoFiltrados({estatus:0}), elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
        
        elementoTotalProductos.innerHTML = spinner();
        elementoTablaBuscarProducto.innerHTML = '';
    
        let resultado = await getProductosFiltro(`${URL_BASE}/getProductosFiltro`, filtro),
        lista='';
        log(resultado)
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
    carritoActual = JSON.parse(localStorage.getItem('carritoInventario')),
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
    }else if(e.target.localName == 'option'){
        accion = e.target.parentElement.id;
    }else if(e.target.localName == 'select'){
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
                let carritoActualizadoB = carritoActual.map(producto => {
                    if( parseFloat(cantidad) <= 0 ){
                        banderaDeError++;
                        return producto;
                    }
                    if(producto.codigo_producto == codigoProducto ) {
                        producto.cantidad = parseFloat( cantidad );
                        producto.subtotal = parseFloat( producto.costo * cantidad );
                        // producto.subtotalBs = darFormatoDeNumero( cantidad * quitarFormato(producto.costoBs) );
                    };
                    return producto;
                });
            
                if(banderaDeError){
                    elementoAlertas.innerHTML = componenteAlerta('La cantidad debe ser mayor a cero.', 401); 
                    return setTimeout(()=>{
                        elementoAlertas.innerHTML="";
                    }, 3500);
                }

               /** Guardamos en local el nuevo carrito */
                localStorage.setItem('carritoInventario', JSON.stringify(carritoActualizadoB.reverse()));
                /** Cargamos la lista del carrito de compra */
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoActualizadoB.reverse());
            

                 /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
                carritoActual = JSON.parse(localStorage.getItem('carritoInventario'));
                await cargarDatosDeFactura(carritoActual, factura);


            break;
        case 'eliminarProductoFactura':
                let carritoActualizadoC = carritoActual.filter(producto => producto.codigo_producto != codigoProducto );
                localStorage.setItem('carritoInventario', JSON.stringify(carritoActualizadoC.reverse()));
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoActualizadoC.reverse());

                /** Actualizamos la factura */
                await cargarDatosDeFactura(carritoActualizadoC, factura);
            break;
        case 'eliminarFactura':
            /** validamos si hay factura para eliminar */
            facturaActual = localStorage.getItem('facturaInventario');
       
            if(facturaActual){
                /** Eliminamos la factura y el carrito del almacen local */
                    localStorage.removeItem('carritoInventario');
                    localStorage.removeItem('facturaInventario');
    
                elementoAlertas.innerHTML = componenteAlerta('La factura se elemino correctamente', 200);
                setTimeout(()=>{
                    elementoAlertas.innerHTML=spinner();
                    window.location.href = `${URL_BASE_APP}/inventarios/crearEntrada`;
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
            facturaActual = localStorage.getItem('facturaInventario');
       
            if(facturaActual){
                /** Eliminamos la factura y el carrito del almacen local */
                    localStorage.removeItem('carritoInventario');
                    localStorage.removeItem('facturaInventario');
                    e.target.innerHTML = spinner('text-white');
                    window.location.href = `${URL_BASE_APP}/inventarios`;
            }

            break;
        case 'cargarModalMetodoPago':
                if(factura.identificacion == ""){
                    return alert("Debes Ingresar un cliente para poder vender");
                }
               
               await cargarEventosAccionesDeFactura()

            break;
        case 'vender':
            /** declaracion de variables */
            let abonado = 0,
            resultadoDefacturarCarritoZ = '',
            resultadoDeFacturar = '',
            carritoActualizadoZ = [],
            confirmarProcesoDeEntrada = false,
            contador = 0;

            /** Obtenemos el codigo de factura del proveedor */
            let elementoCodigoFacturaProveedor = d.querySelector('#codigoFacturaProveedor');
            if(factura.codigo_factura != "") elementoCodigoFacturaProveedor.value = factura.codigo_factura;
            elementoCodigoFacturaProveedor.style=""

            /** Validamos el codigo de factura proveedor que no este vacio */
            if(elementoCodigoFacturaProveedor.value == "") return elementoAlertas.innerHTML= componenteAlerta(`El campo ${elementoCodigoFacturaProveedor.name} es obligatorio.`, 401), setTimeout(()=>{elementoAlertas.innerHTML = ''}, 2500), elementoCodigoFacturaProveedor.style="border: 1px solid red";

            
            /** Asignamos el codigo de la factura proveedor */
            factura.codigo_factura = elementoCodigoFacturaProveedor.value;

            /** Validamos si el codigo de la factura del proveedor no se repite */
            let yaExisteElCodigoFacturaEntrada = await getFacturaES(`${URL_BASE}/getFacturaES`, factura );  
            if(yaExisteElCodigoFacturaEntrada.estatus == 409) return elementoAlertas.innerHTML = componenteAlerta(yaExisteElCodigoFacturaEntrada.mensaje, 401), elementoCodigoFacturaProveedor.value = "", factura.codigo_factura = "", setTimeout(()=>{elementoAlertas.innerHTML = ''},2500);
            
            /** validamos si el cliente esta agregado a la factura  */
            if(factura.identificacion == "") {
                e.target.parentElement.innerHTML += componenteAlerta('Debe agregar un PROVEEDOR para esta factura.', 401);
                let elementoAlertaVender = d.querySelectorAll('.alertaGlobal');
                return setTimeout( () => {
                    elementoAlertaVender.forEach(element => {
                        element.classList.add('d-none')
                    });
                    cargarEventosAccionesDeFactura();
                }, 2500);
            } 

            /** VALIDAMOS QUE HALLA PRODUCTOS EN LA FACTURA O CARRITO */
            if(!carritoActual.length) return  elementoAlertas.innerHTML = componenteAlerta("Debes ingresar productos a la factura, para continuar.", 401), setTimeout(()=>{elementoAlertas.innerHTML=""},2500);
            
            localStorage.setItem( 'facturaInventario', JSON.stringify(factura) );
            carritoActualizadoZ = carritoActual.map(productoEnCarrito => {
                productoEnCarrito.identificacion = factura.identificacion;
                productoEnCarrito.codigo_factura =  elementoCodigoFacturaProveedor.value;
                return productoEnCarrito;
            })
            localStorage.setItem('carritoInventario', JSON.stringify(carritoActualizadoZ));

            /** Preguntamos si desea seguir con la acción */
            confirmarProcesoDeEntrada = confirm('¿Desea Procesar esta ENTRADA al inventario? Click en aceptar para continuar.');

            /** Validamos la respuesta del usuario */
            if(confirmarProcesoDeEntrada){
        
                    /** Obtenemos el carrito y la factura actualizados */
                    let facturaVender = JSON.parse(localStorage.getItem('facturaInventario')),
                    carritoVender = JSON.parse(localStorage.getItem('carritoInventario'));
                   
                    /** Al procesar la facturacion del carrito agregamos al inventario las cantidades */
                    carritoVender.forEach(async producto => {
                        facturarCarrito(`${URL_BASE}/facturarCarritoEntrada`, producto);
                    });

                    /** MOSTRAR QUE ESTA CARGANDO  */
                    e.target.parentElement.parentElement.children[1].innerHTML = spinner();

                    /** FACTURAR LA ENTRADA */
                    setTimeout(async ()=>{
                        
                        /** Procesamos la factura y generamos el ticket */
                        facturaVender.tipo="ENTRADA";
                        resultadoDeFacturar = await setFactura( `${URL_BASE}/setFacturaEntrada`, facturaVender );

                        /** Mostramos el dialogo de facturar */
                         if ( resultadoDeFacturar.estatus == 201 ) {
                            resultadoConfirm = confirm("Compra procesada correctamente, ¿Deseas imprimir el comprobante?");
                            if (resultadoConfirm) {
                                let facturaDeCompra = htmlTicketEntrada(resultadoDeFacturar.data)
                                setTimeout(()=>imprimirElementoPos(facturaDeCompra),1000);
                               
                                /** Eliminamos la factura del Storagr */
                                setTimeout(()=>{
                                    localStorage.removeItem('carritoInventario');
                                    localStorage.removeItem('facturaInventario');
                                    window.location.href = "/inventarios/crearEntrada";
                                },2500);
    
                            } else {
                                 /** Eliminamos la factura del Storagr */
                                 localStorage.removeItem('carritoInventario');
                                 localStorage.removeItem('facturaInventario');
                                window.location.href = "/inventarios/crearEntrada";
                            }

                    
                        } else {
                            alert(resultadoDeFacturar.mensaje)
                        }
                    }, 2500);


            }else{
                return alert('Acción cancelada.')
            }

            break;
        case 'desactivarFacturaFiscal':
            cargarDatosDeFactura(carritoActual, factura, 0, factura.descuento);
            break;
        case 'activarFacturaFiscal':
            cargarDatosDeFactura(carritoActual, factura, 0.16, factura.descuento);
            break;
        case 'editarDescuento':
            cargarDatosDeFactura(carritoActual, factura, factura.iva, e.target.value);
            break;
        case 'concepto_entrada':
            factura.concepto = e.target.value;
            log(e.target.value)
            log(factura)
            localStorage.setItem('facturaInventario', JSON.stringify(factura));
        break;
        default:
            break;
    }

   
   
    
};

/** NO SE ESTA USANDO ESTE MANEJADOR */
const hanledAccionesDeMetodoDePago = async (e) => {
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
                metodosPagos = arregloDeMetodosDePago;
                elementoMetodoDePago.innerHTML = await componenteMetodosForm(arregloDeMetodosDePago.reverse(), factura);
                await cargarEventosAccionesDeFactura();
            break;
        case 'eliminarMetodo':
                // elementoMetodoDePago.innerHTML += componenteMetodosForm();
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

elementoTarjetaCliente.addEventListener('keyup', hanledBuscarProveedor);
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
    banderaDeALertar = 0;
    for (const iterator of formulario) {
        if(iterator.localName == "input" || iterator.localName == "select"){
            if(!iterator.value.length) iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            else iterator.classList.remove(['border-danger']), iterator.nextElementSibling.textContent=`${iterator.name}`, iterator.classList.add(['border-success']);
            if(iterator.value == "Tipo de documento") iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            // Asignamos el valor al esquema
            esquema[iterator.name] = iterator.value;
        }
    }
    if(banderaDeALertar) return false;
    else return esquema;
}

/** adaptadores de productos */
function adaptadorDeProducto(data){
    let dataInventario = ''; 
    if(data.inventario.length){
        dataInventario = data.inventario[0];
        return {
            id: data.id,
            numero: data.id,
            codigo: data.codigo,
            descripcion: data.descripcion,
            marca: data.id_marca.nombre,
            categoria: data.id_categoria.nombre,
            imagen: data.imagen,
            /** Data de inventario */
            idInventario: dataInventario.id,
            stock: parseFloat(dataInventario.cantidad),
            costo: parseFloat( dataInventario.costo ),
            pvp: parseFloat( ( dataInventario.pvp ) ),
            pvp_2: parseFloat( ( dataInventario.pvp_2 ) ),
            pvp_3: parseFloat( ( dataInventario.pvp_3 ) ),
            // fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
        };
    }else{
        return {
            id: data.id,
            numero: data.id,
            codigo: data.codigo,
            descripcion: data.descripcion,
            marca: data.id_marca.nombre,
            categoria: data.id_categoria.nombre,
            imagen: data.imagen,
             /** Data de inventario */
             idInventario: 0,
             stock: 0,
             costo: 0,
             pvp: 0,
             pvp_2: 0,
             pvp_3: 0,
        };

    }
};

function adaptadorDeProductoACarrito(producto, data, factura){
    return {
        codigo: factura.codigo, // Codigo del movimiento
        codigo_factura: factura.codigo_factura, // Codigo de la factura
        codigo_producto: producto.codigo,
        identificacion: factura.identificacion,
        descripcion: producto.descripcion,
        fecha: factura.fecha,
        stock: producto.cantidad, // disponibles o en existencia
        /** Datos numéricos */
        cantidad: parseFloat(data.cantidad), // la cantida de entrada de este producto
        costo: parseFloat(data.costo), // costo en dolares 
        pvp: parseFloat(data.pvp), // pvp en dolares 
        pvp_2: parseFloat(data.pvp_2), // pvp 2 en dolares 
        pvp_3: parseFloat(data.pvp_3), // pvp 3 en dolares 
        subtotal: parseFloat(data.costo * data.cantidad), // subtotal en dolares
    };
};
/** CIERRE adaptadores de productos */

/** funciones encargadas de los eventos de los componentes */
async function cargarEventosDeAgregarProductoAFactura(){
    let elementoAgregarAFactura = d.querySelectorAll('.agregar-producto'),
    elementoCerrarModalCustom = d.querySelectorAll('.cerrar__ModalCustom');

    elementoCerrarModalCustom.forEach(btnCerrarModal => {
        btnCerrarModal.addEventListener('click', hanledAgregarAFactura);
    });

    elementoAgregarAFactura.forEach(inputAgregarCantidad => {
        inputAgregarCantidad.addEventListener('click', hanledAgregarAFactura);
    })
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
/** CIERRE funciones encargadas de los eventos de los componentes */

function vaciarDatosDelClienteDeLaFactura(factura){
    factura.identificacion = "";
    factura.razon_social = "";
    localStorage.setItem('facturaInventario', JSON.stringify(factura));
};

/** FUNCIONES que se encargar de llenar de datos los componentes */
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
        acumuladorSubtotal = parseFloat(acumuladorSubtotal) + producto.subtotal; 
    });
    factura.iva = iva; 
    factura.subtotal = acumuladorSubtotal;
    factura.descuento = descuento;
    factura.total = (parseFloat( (acumuladorSubtotal * factura.iva) + acumuladorSubtotal) - parseFloat(acumuladorSubtotal * (factura.descuento/100)));
    
    localStorage.setItem('facturaInventario', JSON.stringify(factura));

    /** Recargamos el componente factura */
    elementoFactura.innerHTML = spinner();
    elementoFactura.innerHTML = await componenteFactura(factura);

       /** Cargamos el modal de metodos de pago */
       elementoMetodoDePagoModal.innerHTML = await componenteMetodoDePago(factura);

       /** Obtenemos el elemento del componente M-pagos */
    //    let elementoMetodoDePago = d.querySelector('#elementoMetodoDePago');
    //    elementoMetodoDePago.innerHTML = await componenteMetodosForm(metodosPagos, factura);

    await cargarEventosAccionesDeFactura()

};

/** CIERRE - FUNCIONES que se encargar de llenar de datos los componentes */

