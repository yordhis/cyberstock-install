/** ELEMENTOS */
console.log('conectador al js cobrar');
let elementoAlertas = d.querySelector('#alertas'), 
elementoMetodoDePagoModal= d.querySelector('#elementoMetodoDePagoModal'), 
elementoMensajeDeEspera= d.querySelector('#mensajeDeEspera'), 
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
        metodos:'',
        estatusDeDevolucion: false
},
metodosPagos = [{
    id: 1,
    tipoDePago: null,
    montoDelPago: 0,
}];


/** COMPONENTES */


/** MODAL METODOS DE PAGO Y VUELTO */
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
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary acciones-factura" id="vender">Facturar</button>
                    </div>

                </div>
            </div>
        </div>
    `;
};
/** CIERRE MODAL METODOS DE PAGO Y VUELTO */

/** MANEJADORES DE EVENTOS */
const hanledLoad = async (e) => {
    await cargarEventosDeFormularios();
    let elementoVuelto = d.querySelector('#elementoVuelto');
}; /** HANLEDLOAD */

const hanledFormulario = async (e) => {
    console.log(e.target)
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

/** ULTILIDADES */

/** Esta funcion obtiene todos los formularios de la vista */
async function cargarEventosDeFormularios(){
    let formularios = d.forms;
    log(formularios)
    for (const iterator of formularios) {
        if (iterator.id != 'cerrarSesion') {
            iterator.addEventListener('submit', hanledFormulario);
        }
    }
};

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


