/** ELEMENTOS */
let elementoTarjetaCliente = d.querySelector('#tarjetaCliente'),
elementoBuscarProducto = d.querySelector('#buscarProducto'),
elementoTablaBuscarProducto = d.querySelector('#tablaBuscarProducto'),
elementoTotalProductos = d.querySelector('#totalProductosFiltrados'),
codigoFactura = d.querySelector('#codigoFactura'),
elementoAlertas = d.querySelector('#alertas'), 
listaDeProductosEnFactura = d.querySelector('#listaDeProductosEnFactura'), 
elementoFactura = d.querySelector('#componenteFactura'), 
elementoMetodoDePago= d.querySelector('#elementoMetodoDePago'), 
factura = {
        codigo:'',
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
};


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

const componenteListaDeProductoFiltrados = (producto) => {
    if (producto.estatus == 0) {
        return `
        <tr>
            <td colspan="5" class="text-center text-danger ">NO HAY RESULTADOS</td>
        </tr>
        `;
    } else {
        return `
        <tr>
            <td>
                <button class="btn btn-none p-0 m-0 agregar-producto" id="${producto.codigo}">
                    <i class="bi bi-plus-square fs-5 "></i>
                </button>
            </td>
            <td>${producto.codigo}</td>
            <td>${producto.descripcion}</td>
            <td>
                ${producto.pvp} <br>
                REF: ${producto.pvpUsd}
            </td>
        
            <td>${producto.cantidad}</td>
        
        </tr>
        `;
    }
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
                <td>${producto.costoBs}</td>
                <td>${producto.subtotalBs}</td>
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
    // log(data)
    return `<i class="bi bi-back"></i> N° Factura: ${data.data}`;
}

const componenteFactura = async (factura) => {
    return `
        <div class="col-sm-6 form-floating mb-3">
            <input type="number" class="form-control acciones-factura" id="editarDescuento" placeholder="Ingrese descuento">
            <label for="floatingInput">Descuento %</label>
        </div>
        <div class="col-sm-3 form-floating mb-3">
            <input type="number" class="form-control bg-secondary-light" readonly id="floatingInput" placeholder="16" value="16">
            <label for="floatingInput">IVA %</label>
        </div>
        <div class="col-sm-3 ">
            <label for="">No fiscal</label>
            <input type="checkbox" class="form-check acciones-factura" id="desactivaIva" >
        </div>

        <div class="col-sm-2 text-black">
            <p>SUBTOTAL</p>
            <p>IVA ${ factura.iva == "" ? 16 : (factura.iva * 100).toFixed(2) }%</p>
            <p>DESCUENT.</p>
        </div>
        <div class="col-sm-4 ">
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero(factura.subtotal * factura.tasa) } BS</p>
            <p>${ factura.subtotal == "" ? 0 : darFormatoDeNumero(factura.subtotal * factura.iva * factura.tasa) } BS</p>
            <p>${ factura.descuento == "" ? 0 : darFormatoDeNumero(factura.descuento) } BS</p>
        </div>
        <div class="col-sm-6">
            <p>TOTAL</p>
            <p class="fs-1 text-success">${ factura.total == "" ? 0 : (  darFormatoDeNumero(parseFloat(factura.total) * factura.tasa)) } BS</p>
            <p class="fs-5 text-success">REF ${ factura.total == "" ? 0 : darFormatoDeNumero(parseFloat(factura.total)) }</p>
            
        </div>

        <div class="col-sm-6">
            <button class="btn btn-success  w-100 fs-3 acciones-factura" id="cargarModalMetodoPago" data-bs-toggle="modal" data-bs-target="#staticBackdrop" >
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


const componenteMetodosForm = () => {
    return `
        <div class="metodoAdd mt-2 row g-3">
            <div class="col-md-6">
                <select class="form-select acciones-pagos" id="tipoDePago" >
                    <option selected>Método de pago</option>
                    <option value="EFECTIVO">EFECTIVO</option>
                    <option value="PAGO MOVIL">PAGO MOVIL</option>
                    <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                    <option value="TD">TD | PUNTO</option>
                    <option value="TC">TC | PUNTO</option>
                </select>
            </div>

            <div class="col-md-4">
                <input type="number" step="any" class="form-control metodoPago acciones-pagos" id="montoDelPago" >
            </div>

            <div class="col-md-2 ">
                <i class='bx bx-trash text-danger fs-3 acciones-pagos' id="eliminarMetodo"></i>
                <i class='bx bx-plus-circle text-success fs-3 acciones-pagos' id="agregarMetodo"></i>
            </div>
        </div>
    `; 
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

                    <div class="modal-body">
                        <p class="fs-3 text-center text-success">Monto a pagar</p>
                        <p class="fs-1 text-center"> ${darFormatoDeNumero(factura.total * factura.tasa)} BS </p>
                        <p class="fs-3 text-center"> REF: ${darFormatoDeNumero(factura.total)} </p>

                        <div class="metodoAdd mt-2 row g-3">
                            <div class="col-md-6">
                                <select class="form-select acciones-pagos" id="tipoDePago" >
                                    <option selected>Método de pago</option>
                                    <option value="EFECTIVO">EFECTIVO</option>
                                    <option value="PAGO MOVIL">PAGO MOVIL</option>
                                    <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                    <option value="TD">TD | PUNTO</option>
                                    <option value="TC">TC | PUNTO</option>
                                </select>
                            </div>
                
                            <div class="col-md-4">
                                <input type="number" step="any" class="form-control metodoPago acciones-pagos" id="montoDelPago" >
                            </div>
                
                            <div class="col-md-2 ">
                                <i class='bx bx-trash text-danger fs-3 acciones-pagos' id="eliminarMetodo"></i>
                                <i class='bx bx-plus-circle text-success fs-3 acciones-pagos' id="agregarMetodo"></i>
                            </div>
                        </div>

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
    let elementoBuscarCliente = d.querySelector("#buscarCliente");
    cargarEventosAccionesDelCliente();
    
    /** FILTRO PRODUCTOS */
    elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
    elementoTablaBuscarProducto.innerHTML = componenteListaDeProductoFiltrados({estatus:0});
    
    /** Numero de factura */ 
    let resultado =  await getCodigoFactura(`${URL_BASE}/getCodigoFactura`);
    codigoFactura.innerHTML = componenteNumeroDeFactura(resultado);
    
    // log(JSON.parse(localStorage.getItem('factura')))
    facturaStorage = JSON.parse(localStorage.getItem('factura'))

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
        factura.codigo = resultado.data;
        factura.concepto = VENTA.name;
        factura.tipo = 'SALIDA';
        let fecha = new Date();
        factura.fecha = `${fecha.getFullYear()}-${fecha.getMonth()}-${fecha.getDate()}`;
        localStorage.setItem('factura', JSON.stringify(factura));
        // log(JSON.parse(localStorage.getItem('factura')))
    }

    /** Cargamos el componente factura */
    elementoFactura.innerHTML = spinner;
    elementoFactura.innerHTML = await componenteFactura(factura);

    /** Validamos si el carrito tiene productos para cargarlos a la factura */
 
    carritoStorage =  localStorage.getItem('carrito')  ? JSON.parse(localStorage.getItem('carrito')) : [];
   
    if( carritoStorage.length ){
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoStorage.reverse());
        
         /** Cargamos el modal de metodos de pago */
         elementoMetodoDePago.innerHTML = await componenteMetodoDePago(factura);

        /** Cargamos los datos de la factura */
        log(factura)
        await cargarDatosDeFactura(carritoStorage, factura);

    }else{
        listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito([]);
        localStorage.setItem('carrito', JSON.stringify(carritoStorage));

        /** Cargamos el modal de metodos de pago */
        elementoMetodoDePago.innerHTML = await componenteMetodoDePago(factura);

        /** Cargamos las acciones del carrito de factura */
        await cargarEventosAccionesDeFactura();
    }

    
};

const hanledAccionesCliente = async (e) => {
    e.preventDefault();
    // log(e.key)
    // log(e.target.parentElement)

    switch (e.target.parentElement.id) {
        case 'activarInputBuscarCliente':
            elementoTarjetaCliente.innerHTML = componenteTarjetaCliente([],'');
            cargarEventosAccionesDelCliente();
            break;
        case 'activarFormCrearCliente':
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
            localStorage.setItem('factura', JSON.stringify(factura));
            log(JSON.parse(localStorage.getItem('factura')))
            // utilidad de cargar eventos de las acciones del cliente
            cargarEventosAccionesDelCliente()
        }
    }
};

const hanledFormulario = async (e) => {
    e.preventDefault();
    let resultado = '', 
    cliente = '';
    // log(e.target)
    // log(e.target.action)
    // log(e.target)
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
    let carritoActualizado = [],
    carritoActual = localStorage.getItem('carrito') != 'undefined' ? JSON.parse(localStorage.getItem('carrito')) : [],
    banderaDeALertar = 0,
    banderaDeProductoNuevo = 0,
    acumuladorSubtotal = 0; 

    /** Validamos el elemento seleccionado para obtener el codigo  */
    let codigoProducto = "";
    if(e.target.localName == 'button'){
        codigoProducto = e.target.id;
        e.target.classList.add('bg-primary');
        e.target.classList.add('text-white');
    }else if(e.target.localName == 'i'){
        codigoProducto = e.target.parentElement.id;
        e.target.classList.add('bg-primary');
        e.target.classList.add('text-white');
    }

    /** Configuramos el filtro de productos Inventario */
    let filtro = {
        filtro: codigoProducto,
        campo: "codigo"
    },
    resultado = await getInventariosFiltro(`${URL_BASE}/getInventariosFiltro`, filtro),
    cantidad = 1;
    /** AÑADIMOS LA TASA DE VENTA A LA FACTURA */
    factura.tasa = resultado.tasa;
    localStorage.setItem('factura', JSON.stringify(factura));

    /** Solicitimos la cantidad a vender del producto */
    cantidad = prompt('Ingrese cantidad de producto a vender');

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
            carritoActualizado = carritoActual.map(producto => {
                if(productoAdaptado.codigo_producto == producto.codigo_producto){
                    if(parseFloat(productoAdaptado.cantidad) + parseFloat(producto.cantidad) > parseFloat(producto.stock)) banderaDeALertar++;
                    else {
                        producto.cantidad = parseFloat(productoAdaptado.cantidad) + parseFloat(producto.cantidad);
                        producto.subtotal =  darFormatoDeNumero( producto.cantidad * quitarFormato(producto.costo) );
                        producto.subtotalBs = darFormatoDeNumero( producto.cantidad * quitarFormato(producto.costoBs) );
                    }; 
                }else if(productoAdaptado.codigo_producto != producto.codigo_producto){
                    banderaDeProductoNuevo++;
                }
                return producto;
            });
            log(banderaDeALertar);
            /** Si se detecta que hay un producto nuevo se añade */
            if(banderaDeProductoNuevo == carritoActual.length) carritoActualizado.push(productoAdaptado);

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
        log( JSON.parse(localStorage.getItem('carrito')) );

        /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
        carritoActual = JSON.parse(localStorage.getItem('carrito'));

        


        elementoAlertas.innerHTML = componenteAlerta('El prodcuto se agregó a la factura', 200);
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

    
};

const hanledBuscarProducto = async (e) => {    
   
    if(e.key == "Enter"){
        let filtro = {
            filtro: `${e.target.value.trim()}`,
            campo: `descripcion`,
        };

        if(filtro.filtro == "") return elementoTablaBuscarProducto.innerHTML = componenteListaDeProductoFiltrados({estatus:0}), elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
        
        elementoTotalProductos.innerHTML = spinner;
        elementoTablaBuscarProducto.innerHTML = '';
    
        let resultado = await getInventariosFiltro(`${URL_BASE}/getInventariosFiltro`, filtro),
        lista='';
    
        log(resultado);
        if(!resultado.data.data.length) return elementoTablaBuscarProducto.innerHTML += componenteListaDeProductoFiltrados({estatus:0}), elementoTotalProductos.innerHTML = `<p>Total resultados: 0</p>`;
    
        resultado.data.data.forEach(producto => {
            producto.tasa = resultado.tasa;
            lista += componenteListaDeProductoFiltrados(adaptadorDeProducto(producto));
        });
    
        elementoTablaBuscarProducto.innerHTML =  lista;
        elementoTotalProductos.innerHTML = `<p>Total resultados: ${resultado.data.total}</p>`;
        await cargarEventosDeAgregarProductoAFactura();
    }
    
};

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
    }

    log(e.target);
    log(accion);
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
                localStorage.setItem('carrito', JSON.stringify(carritoActualizado.reverse()));
                /** Cargamos la lista del carrito de compra */
                listaDeProductosEnFactura.innerHTML = await cargarListaDeProductoDelCarrito(carritoActualizado.reverse());
            

                 /** Actualizamos FACTURA SUBTOTAL - IVA - DESCUENTO - TOTAL - TOTLA REF */
                carritoActual = JSON.parse(localStorage.getItem('carrito'));

                await cargarDatosDeFactura(carritoActual, factura);


            break;
        case 'eliminarProductoFactura':
                carritoActualizado = carritoActual.filter(producto => producto.codigo_producto != codigoProducto );
                log(carritoActualizado)
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
               await cargarEventosAccionesDeFactura()

            break;
        case 'vender':
                let facturaVender = localStorage.getItem('factura'),
                carritoVender = localStorage.getItem('carrito');

                /** validamos antes de facturar */
                if(facturaVender.identificacion == ""){
                    elementoAlertas.innerHTML = spinner; 
                    elementoAlertas.innerHTML = componenteAlerta() 
                }

                log(facturaVender);
                log(carritoVender);

                facturaStore(JSON.parse(facturaVender));

            break;
        
        default:
            break;
    }

   
   
    
};

const hanledAccionesDeMetodoDePago = async (e) => {
    log(e.target)
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

function adaptadorDeProducto(data){
    return {
        id: data.id,
        numero: data.id,
        codigo: data.codigo,
        descripcion: data.descripcion,
        cantidad: data.cantidad,
        costo: darFormatoDeNumero(parseFloat( data.costo )),
        costoBs: darFormatoDeNumero(parseFloat( data.costo * data.tasa )),
        pvp: darFormatoDeNumero(parseFloat( (data.tasa * data.pvp) )),
        pvpUsd:  darFormatoDeNumero(parseFloat(data.pvp)),
        pvp_2: darFormatoDeNumero(parseFloat( (data.tasa * data.pvp_2) )),
        pvpUsd_2:  darFormatoDeNumero(parseFloat(data.pvp_2)),
        pvp_3: darFormatoDeNumero(parseFloat( (data.tasa * data.pvp_3) )),
        pvpUsd_3:  darFormatoDeNumero(parseFloat(data.pvp_3)),
        marca: data.id_marca.nombre,
        imagen: data.imagen,
        fechaEntrada: new Date(data.fecha_entrada).toLocaleDateString(),
        categoria: data.id_categoria.nombre,
    };
};

async function cargarEventosDeAgregarProductoAFactura(){
    let elementoAgregarAFactura = d.querySelectorAll('.agregar-producto');
    elementoAgregarAFactura.forEach(btnAgregar => {
        btnAgregar.addEventListener('click', hanledAgregarAFactura);
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
        else element.addEventListener('click', hanledAccionesDeMetodoDePago);
    });
};

function removerEstilosDelElemento(elementos, estilo){
    elementos.forEach(elemento => {
        elemento.classList.remove(estilo);
    });
};

function adaptadorDeProductoACarrito(producto, cantidad, factura){
    return {
        codigo: factura.codigo, // Codigo de la factura
        codigo_producto: producto.codigo,
        identificacion: factura.identificacion,
        cantidad: cantidad,
        stock: producto.cantidad,
        costo: darFormatoDeNumero(parseFloat(producto.pvp)), // costo/pvp en dolares 
        costoBs: darFormatoDeNumero(parseFloat(producto.pvp * factura.tasa)), // costo/pvp en bolivares
        descripcion: producto.descripcion,
        subtotal: darFormatoDeNumero(parseFloat(producto.pvp * cantidad)), // subtotal en dolares
        subtotalBs: darFormatoDeNumero(parseFloat(producto.pvp * cantidad * factura.tasa)), // subtotal en bolivares
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

async function cargarDatosDeFactura(carritoActual, factura){
    let acumuladorSubtotal = 0;
    carritoActual.forEach(producto => {
        acumuladorSubtotal = parseFloat(acumuladorSubtotal) + quitarFormato(producto.subtotal); 
    });
    log(acumuladorSubtotal)
    factura.iva = 0.16; 
    factura.subtotal = acumuladorSubtotal;
    factura.descuento = 0;
    factura.total = (parseFloat(acumuladorSubtotal * factura.iva) + acumuladorSubtotal);
    
    localStorage.setItem('factura', JSON.stringify(factura));

    /** Recargamos el componente factura */
    elementoFactura.innerHTML = spinner;
    elementoFactura.innerHTML = await componenteFactura(factura);

    await cargarEventosAccionesDeFactura()

};