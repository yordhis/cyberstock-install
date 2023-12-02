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
        razon_social:'', // nombre de cliente o proveedor
        identificacion:'', // numero de documento
        subtotal: 0, // se guarda en divisas
        total: 0,
        tasa: 0, // tasa en el momento que se hizo la transaccion
        iva: 0, // impuesto
        tipo:'', // fiscal o no fialcal
        concepto:'', // venta, compra ...
        descuento: 0, // descuento
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
        if(!cliente.telefono){
            cliente.telefono = "no asignado";
        }else{
            cliente.telefono = cliente.telefono.substring(4,0) +"-"+ cliente.telefono.substring(4);
        }
        return `
        <div class="card-body">
            <h5 class="card-title text-danger">Cliente N°: ${cliente.id}</h5>
            <h6 class="card-subtitle mb-2 text-muted">Datos del cliente</h6>
            <p class="card-text">
                <b>Nombre y Apellido:</b> ${cliente.nombre} <br><br>
                <b>Rif o ID:</b> ${cliente.tipo}-${cliente.identificacion} <br><br>
                <b>Teléfono:</b> ${cliente.telefono} <br><br>
                
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
                <input type="text" class="form-control" name="telefono" id="floatingInput" placeholder="Ingrese número de teléfono.">
                <label for="floatingInput">TELÉFONO</label>
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
                    <input type="text" class="form-control" name="telefono" value="${cliente.telefono}" id="floatingInput" placeholder="Ingrese número de teléfono.">
                    <label for="floatingInput">TELÉFONO</label>
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
            <td >
                BS ${darFormatoDeNumero(producto.pvp)}  <br>
                REF: ${darFormatoDeNumero(producto.pvpUsd)}
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
                    <input type="number" class="form-control agregar-producto" id="${producto.codigo}" value="1" >
                    <label for="floatingInput">Cantidad</label>
                </div>
                <p class="text-muted fs-4"> Enter para agregar </p>
                <p class="btn btn-none text-danger fs-5 cerrar__ModalCustom" id="cerrarModalCustom"> CERRAR </p>
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
                <td>${darFormatoDeNumero(producto.costoBs)}</td>
                <td>${darFormatoDeNumero(producto.subtotalBs)}</td>
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
    return `<i class="bi bi-back"></i> N° Factura: ${data.data}`;
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
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero(factura.subtotal * factura.tasa) } BS</p>
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero(factura.subtotal * factura.iva * factura.tasa) } BS</p>
            <p>${ factura.descuento == "" ? 0 : darFormatoDeNumero( factura.subtotal * (factura.descuento/100) * factura.tasa ) } BS</p>
        </div>
        <div class="col-sm-6">
            <p>TOTAL</p>
            <p class="fs-1 text-success">${ factura.total == "" ? 0 :  darFormatoDeNumero(factura.total * factura.tasa) } BS</p>
            <p class="fs-5 text-success">REF ${ factura.total == "" ? 0 : darFormatoDeNumero(factura.total) }</p>
            
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
    /** Guardamos los métodos de pagosen la variable global */
    metodosPagos = metodos;

    /** Recorremos los metodos de pagos para acumular los abonos */
    metodos.forEach(elementoAbono => {
     
        if(elementoAbono.tipoDePago == "DIVISAS" ) abonado += elementoAbono.montoDelPago;
        else abonado +=  elementoAbono.montoDelPago / factura.tasa ;
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
  
    metodosPagos.forEach(elementoPago => {
        /** Obtenemos el metodo de pago seleccionado */
        metodoSeleccionado = elementoPago.tipoDePago ? `<option value="${elementoPago.tipoDePago}" selected>${elementoPago.tipoDePago}</option>`
        : `<option selected>Método de pago</option>`; 

        /** añadimos el html del componente configurado */
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
                    <span class="text-danger"></span>
                </div>

                <div class="col-md-2 " id="${elementoPago.id}">
                    ${elementoPago.montoDelPago == 0 ?  `<i class='bx bx-plus-circle text-success fs-3 acciones-pagos' id="agregarMetodo"></i>` 
                    : `<i class='bx bx-trash text-danger fs-3 acciones-pagos' id="eliminarMetodo"></i>` }
                </div>
            </div>
        `;
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
    
    /** Numero de factura */ 
    let resultado =  await getCodigoFactura(`${URL_BASE}/getCodigoFactura/facturas`);
    codigoFactura.innerHTML = componenteNumeroDeFactura(resultado);
    
   
    facturaStorage = JSON.parse(localStorage.getItem('factura'))

    if(facturaStorage){

        factura = facturaStorage;
         /** ALMACENAMOS LA FACTURA */
         factura.codigo = resultado.data;
         factura.concepto = VENTA.name;
         factura.tipo = 'SALIDA';
         factura.iva = 0.16;
         let fecha = new Date();
         factura.fecha = fecha;
         localStorage.setItem('factura', JSON.stringify(factura));

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
        factura.codigo = resultado.data;
        factura.concepto = VENTA.name;
        factura.tipo = 'SALIDA';
        factura.iva = 0.16;
        let fecha = new Date();
        factura.fecha = fecha;
        localStorage.setItem('factura', JSON.stringify(factura));
     
    }

    /** Cargamos el componente factura */
    elementoFactura.innerHTML = spinner;
    // elementoFactura.innerHTML = await componenteFactura(factura);

    /** Validamos si el carrito tiene productos para cargarlos a la factura */
 
    carritoStorage =  localStorage.getItem('carrito')  ? JSON.parse(localStorage.getItem('carrito')) : [];
   
    if( carritoStorage.length ){
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoStorage.reverse());

        /** Cargamos los datos de la factura */
        await cargarDatosDeFactura(carritoStorage, factura, factura.iva, factura.descuento);

    }else{
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito([]);
        localStorage.setItem('carrito', JSON.stringify(carritoStorage));

        /** Cargamos los datos de la factura */

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
            localStorage.setItem('factura', JSON.stringify(factura));
           
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
                    if(!resultado) return;
                    e.target.innerHTML = spinner;
                    
                    cliente = await storeCliente(resultado);

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
                            localStorage.setItem('factura', JSON.stringify(factura));
                            
                        elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([cliente.data], cliente.mensaje);
                        cargarEventosAccionesDelCliente();
                        cargarEventosDeFormularios();  
                    }

        break;
        case 'formEditarCliente':
            resultado = await validarDataDeFormularioCliente(e.target)
            if(!resultado) return;
            e.target.innerHTML = spinner;

            cliente = await updateCliente(e.target.action, resultado);
               /** Seteamos el cliente en la factura de local storage */
               factura.identificacion = cliente.data[0].identificacion;
               factura.tipoDocumento = cliente.data[0].tipo;
               factura.razon_social = cliente.data[0].nombre;
               localStorage.setItem('factura', JSON.stringify(factura));

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

    if(e.target.id == "cerrarModalCustom"){
           /** CERRAMOS EL MODAL */
           e.target.parentElement.parentElement.classList.remove('modal--show');
    }

    if(e.key == "Enter"){
        let carritoActualizado = [],
        carritoActual = localStorage.getItem('carrito') != 'undefined' ? JSON.parse(localStorage.getItem('carrito')) : [],
        banderaDeALertar = 0,
        banderaDeProductoNuevo = 0,
        acumuladorSubtotal = 0; 

        /** Configuramos el filtro de productos Inventario */
        let filtro = {
            filtro: e.target.id,
            campo: ["codigo"]
        },
        resultado = await getInventariosFiltro(`${URL_BASE}/getInventariosFiltro`, filtro),
        cantidad = e.target.value;

        /** AÑADIMOS LA TASA DE VENTA A LA FACTURA */
        factura.tasa = resultado.tasa;
        localStorage.setItem('factura', JSON.stringify(factura));


        /** Validamos que la cantidad ingresada no sobrepase la del inventario */
        if(parseFloat(cantidad) > parseFloat(resultado.data.data[0].cantidad) ){
            elementoAlertas.innerHTML = componenteAlerta('No tiene SUFICIENTE STOCK para suplir el pedido, intente de nuevo.', 401); 
            return setTimeout(()=>{
                elementoAlertas.innerHTML="";
            }, 3500)
        }
        /** Validamos que la cantida sea agreagada */
        if(cantidad > 0){
            
            /** Adaptamos el producto para añadirlo al carrito */
            productoAdaptado = adaptadorDeProductoACarrito(resultado.data.data[0], cantidad, factura);
        
            /** Si ya existe un carrito añadimos a ese carrito */
            if(carritoActual.length){
                /** Recorremos el carrito para verificar si se añade un producto nuevo o se suma al existente */
                carritoActualizado = carritoActual.map(producto => {
                    if(productoAdaptado.codigo_producto == producto.codigo_producto){
                        if(parseFloat(productoAdaptado.cantidad) + parseFloat(producto.cantidad) > parseFloat(producto.stock)) banderaDeALertar++;
                        else {
                            producto.cantidad = parseFloat(productoAdaptado.cantidad) + parseFloat(producto.cantidad);
                            producto.subtotal =   producto.cantidad * producto.costo;
                            producto.subtotalBs =  producto.cantidad * producto.costoBs;
                        }; 
                    }else if(productoAdaptado.codigo_producto != producto.codigo_producto){
                        banderaDeProductoNuevo++;
                    }
                    return producto;
                });

                /** Si se detecta que hay un producto nuevo se añade */
                if(banderaDeProductoNuevo == carritoActual.length) carritoActualizado.push(productoAdaptado);
                
                /** Si hay un error damos respuesta */
                if(banderaDeALertar){
                    elementoAlertas.innerHTML = componenteAlerta('El prodcuto NO se agregó a la factura STOCK INSUFICIENTE', 404);
                    return setTimeout(()=>{
                        elementoAlertas.innerHTML="";
                    }, 2500);
                }
            }else{
                carritoActualizado.push(productoAdaptado);
            }

            /** Guardamos en localStorage */
            localStorage.setItem('carrito', JSON.stringify(carritoActualizado.reverse()));

            /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
            carritoActual = JSON.parse(localStorage.getItem('carrito'));
            elementoAlertas.innerHTML = componenteAlerta('El prodcuto se agregó a la factura', 200);
            
            /** CERRAMOS EL MODAL */
            e.target.parentElement.parentElement.parentElement.classList.remove('modal--show');
            setTimeout(()=>{
                elementoAlertas.innerHTML="";
            }, 2500);
        
            /** Actualizamos la lista de productos en la factura */
            listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito( JSON.parse(localStorage.getItem('carrito')) );

            /** Cargamos la factura y sus eventos de acciones del carrito de factura */
            await cargarDatosDeFactura(carritoActual, factura);

        }else{
            elementoAlertas.innerHTML = componenteAlerta('Ingrese cantidad del pedido, intente de nuevo.', 404);
            return setTimeout(()=>{
                elementoAlertas.innerHTML="";
            }, 3500);
        }

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
    carritoActualizado = [],
    carritoActual = JSON.parse(localStorage.getItem('carrito')),
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
                        producto.cantidad = parseFloat(cantidad);
                        producto.subtotal = producto.costo * cantidad ;
                        producto.subtotalBs = cantidad * producto.costoBs;
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
                localStorage.setItem('carrito', JSON.stringify(carritoActualizado.reverse()));
                /** Cargamos la lista del carrito de compra */
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoActualizado.reverse());
            

                 /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
                carritoActual = JSON.parse(localStorage.getItem('carrito'));

                await cargarDatosDeFactura(carritoActual, factura);


            break;
        case 'eliminarProductoFactura':
                carritoActualizado = carritoActual.filter(producto => producto.codigo_producto != codigoProducto );
                localStorage.setItem('carrito', JSON.stringify(carritoActualizado.reverse()));
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoActualizado.reverse());

                /** Actualizamos la factura */
                await cargarDatosDeFactura(carritoActualizado, factura);
            break;
        case 'eliminarFactura':
            /** validamos si hay factura para eliminar */
            facturaActual = localStorage.getItem('factura');
       
            if(facturaActual){
                /** Eliminamos la factura y el carrito del almacen local */
                    localStorage.removeItem('carrito');
                    localStorage.removeItem('factura');
    
                elementoAlertas.innerHTML = componenteAlerta('La factura se elemino correctamente', 200);
                setTimeout(()=>{
                    elementoAlertas.innerHTML=spinner;
                    window.location.href = `${URL_BASE_APP}/pos`;
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
            facturaActual = localStorage.getItem('factura');
       
            if(facturaActual){
                /** Eliminamos la factura y el carrito del almacen local */
                    localStorage.removeItem('carrito');
                    localStorage.removeItem('factura');
    
                elementoAlertas.innerHTML = componenteAlerta('La factura se elemino correctamente', 200);
                setTimeout(()=>{
                    elementoAlertas.innerHTML=spinner;
                    window.location.href = `${URL_BASE_APP}/panel`;
                }, 1500);
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

         
            /** Sumamos todos los metodos de pago */
            metodosPagos.forEach(elementoAbono => {
                if(elementoAbono.tipoDePago == "DIVISAS" ) abonado += elementoAbono.montoDelPago;
                else abonado += elementoAbono.montoDelPago / factura.tasa;
            });

            /** Previsualizamos el monto abonado */
         
                /** Validamos si el monto es mayor o igual al total a pagar */
                if( (Math.round(abonado*100)/100) >= (Math.round(factura.total * 100)/100) ){
                    
                    /** Agregamos los metodos en formato JSON a la factura */
                    factura.metodos = JSON.stringify(metodosPagos);
                  
                    /** Guardamos los cambios en localStorage */
                    localStorage.setItem('factura', JSON.stringify(factura));
    
                    /** Obtenemos el carrito y la factura actualizados */
                    let facturaVender = JSON.parse(localStorage.getItem('factura')),
                    carritoVender = JSON.parse(localStorage.getItem('carrito'));

                    /** FACTURAR EL CARRITO */
                    /** Al procesar la facturacion del carrito descontamos del inventario las cantidades */
                    carritoVender.forEach(producto => {
                        producto.identificacion = facturaVender.identificacion;
                        producto.subtotal = producto.subtotal;
                        facturarCarrito(`${URL_BASE}/facturarCarrito`, producto);
                    });
                    
                    /** MOSTRAR QUE ESTA CARGANDO  */
                    e.target.parentElement.parentElement.children[1].innerHTML = spinner;

                    /** FACTURAR */
                    setTimeout( async ()=>{
                        /** Procesamos la factura y generamos el ticket */
                        resultadoDeFacturar = await facturaStore(facturaVender);
                        
                        /** Mostramos el dialogo de facturar */
                         if (resultadoDeFacturar.estatus == 201) {
                            resultado = confirm("Factura procesada correctamente, ¿Deseas imprimir el comprobante?");
                            if (resultado) {
                                imprimirElemento(htmlTicket(resultadoDeFacturar.data));
                                
                                resultadoOtraCapia = confirm("¿Deseas imprimir otra copia del comprobante?");
                                if (resultadoOtraCapia) {
                                    imprimirElemento(htmlTicket(resultadoDeFacturar.data));
                                }
                                /** Eliminamos la factura del Storagr */
                                localStorage.removeItem('carrito');
                                localStorage.removeItem('factura');
                                window.location.href = "/pos";
    
                            } else {
                                 /** Eliminamos la factura del Storagr */
                                 localStorage.removeItem('carrito');
                                 localStorage.removeItem('factura');
                                window.location.href = "/pos";
                            }
                        } else {
                            alert(resultadoDeFacturar.mensaje)
                        }
                    },2500)

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
           
            cargarDatosDeFactura(carritoActual, factura, 0, factura.descuento);
            break;
        case 'activarFacturaFiscal':
           
            cargarDatosDeFactura(carritoActual, factura, 0.16, factura.descuento);
            break;
        case 'editarDescuento':
           
            cargarDatosDeFactura(carritoActual, factura, factura.iva, e.target.value);
            break;
        default:
            break;
    }

   
   
    
};

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
                        id: element.children[2].id,
                        tipoDePago: element.children[0].children[0].value,
                        montoDelPago: parseFloat(element.children[1].children[0].value),
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
                elementoMetodoDePago.innerHTML = await componenteMetodosForm(arregloDeMetodosDePago, factura);
                await cargarEventosAccionesDeFactura();
            break;
        case 'eliminarMetodo':
                // elementoMetodoDePago.innerHTML += componenteMetodosForm();
                metodosActuales.forEach(element => {
                    if(element.children[2].id  != e.target.parentElement.id){
                        arregloDeMetodosDePago.push({
                            id: element.children[2].id,
                            tipoDePago: element.children[0].children[0].value,
                            montoDelPago: parseFloat(element.children[1].children[0].value),
                        });
                    }
                });
           
                elementoMetodoDePago.innerHTML = await componenteMetodosForm(arregloDeMetodosDePago, factura);
                elementoVuelto.innerHTML = await componenteVuelto(arregloDeMetodosDePago, factura);
                await cargarEventosAccionesDeFactura();
            break;
        case 'tipoDePago':
            
            if(metodosActuales.length == 1){
                if(e.target.value == "DIVISAS" ) e.target.parentElement.parentElement.children[1].children[0].value = factura.total;
                else e.target.parentElement.parentElement.children[1].children[0].value = factura.total * factura.tasa;
            }
           
            metodosActuales.forEach(element => {
                arregloDeMetodosDePago.push({
                    id: element.children[2].id,
                    tipoDePago: element.children[0].children[0].value,
                    montoDelPago: parseFloat(element.children[1].children[0].value),
                }); 
            });
            elementoVuelto.innerHTML = await componenteVuelto(arregloDeMetodosDePago, factura);
            break;
        case 'montoDelPago':
            /** obtener el ID del elemento tipo de pago para actualizar el monto ingresado  */
            metodosActuales.forEach(element => {
                if(e.target.parentElement.parentElement.children[2].id == element.id){
                    arregloDeMetodosDePago.push({
                        id: element.children[2].id,
                        tipoDePago: element.children[0].children[0].value,
                        montoDelPago: parseFloat(e.target.value), /** actualizamos el monto */
                    });
                    
                }else{
                    arregloDeMetodosDePago.push({
                        id:  element.children[2].id,
                        tipoDePago: element.children[0].children[0].value,
                        montoDelPago: parseFloat(element.children[1].children[0].value),
                    });
                }
                
            });
            elementoVuelto.innerHTML = await componenteVuelto(arregloDeMetodosDePago, factura);

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

/** ADAPTADOR DE PRODUCTO */
function adaptadorDeProducto(data){
    return {
        id: data.id,
        numero: data.id,
        codigo: data.codigo,
        descripcion: data.descripcion,
        marca: data.id_marca.nombre,
        imagen: data.imagen,
        fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
        categoria: data.id_categoria.nombre,
        /** Datos numéricos */
        cantidad: parseFloat( data.cantidad ),
        costo: parseFloat( data.costo ),
        costoBs: parseFloat( data.costo * data.tasa ),
        pvp: parseFloat( (data.tasa * data.pvp) ),
        pvpUsd:  parseFloat(data.pvp),
        pvp_2: parseFloat( (data.tasa * data.pvp_2) ),
        pvpUsd_2:  parseFloat(data.pvp_2),
        pvp_3: parseFloat( (data.tasa * data.pvp_3) ),
        pvpUsd_3:  parseFloat(data.pvp_3),
    };
};

function adaptadorDeProductoACarrito(producto, cantidad, factura){
    return {
        codigo: factura.codigo, // Codigo de la factura
        codigo_producto: producto.codigo,
        identificacion: factura.identificacion,
        descripcion: producto.descripcion,
        /** Datos numéricos */
        cantidad: cantidad,
        stock: parseFloat(producto.cantidad),
        costo: parseFloat(producto.pvp), // costo/pvp en dolares 
        costoBs: parseFloat(producto.pvp * factura.tasa), // costo/pvp en bolivares
        subtotal: parseFloat(producto.pvp * cantidad), // subtotal en dolares
        subtotalBs: parseFloat(producto.pvp * cantidad * factura.tasa), // subtotal en bolivares
    };
};

async function cargarEventosDeAgregarProductoAFactura(){
    let elementoAgregarAFactura = d.querySelectorAll('.agregar-producto'),
    elementoCerrarModalCustom = d.querySelectorAll('.cerrar__ModalCustom');

    elementoCerrarModalCustom.forEach(btnCerrarModal => {
        btnCerrarModal.addEventListener('click', hanledAgregarAFactura);
    });

    elementoAgregarAFactura.forEach(inputAgregarCantidad => {
        inputAgregarCantidad.addEventListener('keyup', hanledAgregarAFactura);
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

function removerEstilosDelElemento(elementos, estilo){
    elementos.forEach(elemento => {
        elemento.classList.remove(estilo);
    });
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
        acumuladorSubtotal = parseFloat(acumuladorSubtotal) + producto.subtotal; 
    });
    
    factura.iva = iva; 
    factura.subtotal = acumuladorSubtotal;
    factura.descuento = descuento;
    factura.total = parseFloat( (acumuladorSubtotal * factura.iva) + acumuladorSubtotal ) - parseFloat( acumuladorSubtotal * (factura.descuento/100) );
    
    localStorage.setItem('factura', JSON.stringify(factura));

    /** Recargamos el componente factura */
    elementoFactura.innerHTML = spinner;
    elementoFactura.innerHTML = await componenteFactura(factura);

       /** Cargamos el modal de metodos de pago */
       elementoMetodoDePagoModal.innerHTML = await componenteMetodoDePago(factura);

       /** Obtenemos el elemento del componente M-pagos */
       let elementoMetodoDePago = d.querySelector('#elementoMetodoDePago');
   
       elementoMetodoDePago.innerHTML = await componenteMetodosForm(metodosPagos, factura);

    await cargarEventosAccionesDeFactura()

};

function vaciarDatosDelClienteDeLaFactura(factura){
    factura.identificacion = "";
    factura.razon_social = "";
    factura.tipo = "";
    localStorage.setItem('factura', JSON.stringify(factura));
};