let btnProcesar = d.querySelector("#btnProcesar"),
// btnAgregarAlCarro = d.querySelector('#agregarAlCarro'),  
mensajeInventario = d.querySelector('#mensajeInventario'),
inputsEntrys = d.querySelectorAll('.input-entrada'),
inputRif = document.querySelector('#rif'),
razon_social  = d.querySelector('#razon_social '),

// Inputs del carrito
inputCodigoDeBarra = d.querySelector('#codigo_producto'),
inputDescripcion = d.querySelector('#descripcion'),
inputCantidad = d.querySelector('#cantidad'),
inputCostoUnitario = d.querySelector('#costo_unitario'),
inputCostoUnitarioBs = d.querySelector('#costo_unitario_bs'),
inputSubtotal = d.querySelector('#subtotal'),
trPvpInputsRadios = d.querySelector('#trPvpInputsRadios'),
// pvp_2 = d.querySelector('#pvp_2'),
// trPvpInputsRadios = d.querySelector('#trPvpInputsRadios'),


// Factura
inputDescuento = d.querySelector("#descuento"),
inputSubtotalTemporalBs = d.querySelector("#subtotal_temporal_bs"),
inputSubtotalTemporalUsd = d.querySelector("#subtotal_temporal_usd"),
inputIva = d.querySelector("#iva"),
inputTotal = d.querySelector("#total"),
inputTotalDivisas = d.querySelector("#totalDivias"),
inputTasa = d.querySelector('#tasa'),
tdSubtotales = d.querySelectorAll(".subtotalProductos"),
productos = [],
subtotal = 0,
factura = {
    codigo: "",
    razon_social: "", // nombre de cliente o proveedor
    identificacion: "", // numero de documento
    subtotal: "", // se guarda en divisas
    total: "",
    tasa: "", // tasa en el momento que se hizo la transaccion
    iva: "", // impuesto
    tipo: "", // fiscal o no fialcal
    concepto: "", // venta: "", compra ...
    descuento: "", // venta, compra ...
},
facturaTemporal = {},

 // Elementos de Metodos de pago
 inputVuelto = d.querySelector("#restante"),
 metodoPago = d.querySelectorAll(".metodoPago"),
 eliminarMetodo = d.querySelectorAll("#eliminarMetodo"),
 divOtroMetodoPago = d.querySelector("#otroMetodoPago"),
 btnAgregarMetodo = d.querySelector("#agregarMetodo")

producto = null;

const htmlMetodoPago = `<div class="metodoAdd mt-2 row g-3">
        <div class="col-md-6">
            <select class="form-select metodoPago">
                <option selected>Método de pago</option>
                <option value="EFECTIVO">EFECTIVO</option>
                <option value="PAGO MOVIL">PAGO MOVIL</option>
                <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                <option value="TD">TD | PUNTO</option>
                <option value="TC">TC | PUNTO</option>
            </select>
        </div>
        <div class="col-md-4">
            <input type="number" id='pagoCliente' step="any" class="form-control metodoPago pagoCliente" >
        </div>
        <div class="col-md-2 ">
            <i class='bx bx-trash text-danger fs-5 metodoPago' id="eliminarMetodo"></i>
        </div>
    </div>`;

const hanledCostoUnitario = (e) => {
    log(e.target.value)
    log(e.key)

    inputCostoUnitarioBs.value = e.target.value * inputTasa.value;
};

const hanledInputDescuento = (e) => {
    log(subtotal);
    // log(subtotal)
    if (e.target.value) {
        let descuento = e.target.value;
        // inputSubtotalTemporalBs.value = ( ((subtotal * parseFloat(inputTasa.value)) - ((subtotal * parseFloat(inputTasa.value)) * (e.target.value / 100))) ).toFixed(2);
        // subtotalTemporal = inputSubtotalTemporalUsd.value = (subtotal - subtotal * (e.target.value / 100)).toFixed(2);
        // subtotalTemporal = parseFloat(subtotalTemporal);
        descuento = (descuento / 100) * subtotal;
        inputTotal.value = ( (subtotal * inputTasa.value - (descuento * inputTasa.value)) * ((inputIva.value / 100) + 1) ).toFixed(2);

        inputTotalDivisas.value = ( (subtotal - descuento) * (inputIva.value / 100 + 1) ).toFixed(2);
    }
};

const hanledInputCodigoBarra = async (e) => {
    // e.preventDefault();
    // log(e.target.value);
    if (e.target.value.length > 3) {
        resultado = await getProducto(e.target.value);
       
        producto = await resultado.result;

        log(producto);
        if (producto.status == 200) {
            if (producto.data.id > 0) {
                inputDescripcion.value = producto.data.descripcion;
                inputCostoUnitario.value = producto.data.pvp; // usd
                inputCostoUnitarioBs.value =  producto.data.pvp * inputTasa.value; // Bs
                trPvpInputsRadios.innerHTML = setPvpInputsRadios(producto.data, inputTasa.value);
                mensajeInventario.innerText = "";
            } else {
                mensajeInventario.innerText = `${producto.message}`;
                trPvpInputsRadios.innerHTML = '';
                inputDescripcion.value = '';
                inputCostoUnitario.value = ''; // usd
                inputCostoUnitarioBs.value =  ''; // Bs
                mensajeInventario.classList.add('text-danger')
                mensajeInventario.classList.add('text-center')
            }
        }
    }
};

const hanledInputIva = (e) => {
    // log(e.target.value);
    subtotalTemporal = parseFloat(inputSubtotalTemporalUsd.value);
    inputTotal.value = (
        subtotalTemporal *
        inputTasa.value *
        (e.target.value / 100 + 1)
    ).toFixed(2);
    inputTotalDivisas.value = (
        (subtotalTemporal * inputTasa.value * (e.target.value / 100 + 1)) /
        inputTasa.value
    ).toFixed(2);
};

const handleSubtotal = (e) => {
    let cantidad = parseFloat(e.target.value),
    costoUnitario = parseFloat(inputCostoUnitario.value);
    if (cantidad && costoUnitario) {
        inputSubtotal.value = cantidad * costoUnitario
    }else{
        log("Los datos ingresados no son números!")
    }
};

// const hanledRifProveedor = async (e) =>{
//     // log(e.key)
//     if(e.target.value.length > 4){
//         razon_social.hidden = false;
//         await getProveedor(e.target.value, razon_social )
//     }
// };

const hanledInputIdentificacion = async (e) => {
    // log(e.target.value);
    // log(inputIdentificacionCliente)
    if (e.target.value.length > 6) {
        resultado = await getCliente(e.target.value);
        cliente = await resultado.result;
        if (cliente.status == 200) {
            if (cliente.data.nombre == undefined) {
                log("entro");
                razon_social.hidden = false;
                razon_social.innerHTML = `<span class="text-danger">Cliente No registrado</span>`;
                
            } else {
                razon_social.hidden = false;
                razon_social.innerHTML = `<h4>Cliente: ${cliente.data.nombre}</h4>`;
    
            }
        }
    }
};


const hanledBotonProcesar = async (e) => {
    if (tdSubtotales.length) {
        let inputsFactura = d.querySelectorAll(".factura"),
        metodosDePagos = '';



        inputsFactura.forEach((element, key) => {
            if (element.id == "razon_social") {
                log("entro");
                facturaTemporal[element.id] = element.textContent;
            } else {
                facturaTemporal[element.id] = element.value;
            }
        });
      
        metodoPago = d.querySelectorAll(".metodoAdd")
        // log(metodoPago[0].children)
        metodoPago.forEach((metodoMonto)=>{
            metodosDePagos += metodoMonto.children[0].children[0].value + '|' +  metodoMonto.children[1].children[0].value + ',';
        })
        metodosDePagos = metodosDePagos.substring(0, metodosDePagos.length-1);
        // log(metodosDePagos.substring(0, metodosDePagos.length-1));
        // log(facturaTemporal);
        // adactamos para enviar a guardar e imprimir ticket
        factura.codigo = facturaTemporal.codigo;
        factura.codigo_factura = facturaTemporal.codigo_factura;
        factura.razon_social = facturaTemporal.razon_social.split(":")[1];
        factura.identificacion = facturaTemporal.rif; // aqui va el nombre
        factura.subtotal = facturaTemporal.subtotal_temporal_usd;
        factura.total = facturaTemporal.totalDivias;
        factura.tasa = facturaTemporal.tasa;
        factura.iva = facturaTemporal.iva;
        factura.tipo = facturaTemporal.tipoTransaccion;
        factura.concepto = facturaTemporal.conceptoDeMovimiento;
        factura.descuento = facturaTemporal.descuento;
        factura.fecha = facturaTemporal.fecha_factura_proveedor;
        factura.metodos = metodosDePagos; // metodo | monto,
       
        if( facturaTemporal.conceptoDeMovimiento.length == 0 ){
            alert('Por favor, ingrese el consepto del movimiento de inventario; Para proceder con la solicitud.')
        }else{
            if (inputVuelto.value > 0 || inputVuelto.value.length == 0) {
                alert('Debe ingresar los metodos de pagos con sus montos y cumplir con la totalidad del pago!')
            }else{
                log(factura);
                log('procesando factura');
                await facturaStoreSalida(factura);
            }
        }
        
    } else {
        alert("Debe agregar productos a la factura para poder ser procesada");
    }
};


const hanledInputRadiosPvp = (e) => {
   
    let inputPvp2 = document.querySelector("#pvp_2"),
    inputPvp3 = document.querySelector("#pvp_3");
    if(e.target.localName == 'input'){
        if(e.target.id == 'pvp_2'){ 
            inputPvp3.checked = false;
            inputCostoUnitario.value = e.target.value;
            inputCostoUnitarioBs.value =  e.target.value * inputTasa.value; // Bs
            inputSubtotal.value = inputCantidad.value * inputCostoUnitario.value
        }else if(e.target.id == 'pvp_3'){
            inputPvp2.checked = false;
            inputCostoUnitario.value = e.target.value;
            inputCostoUnitarioBs.value =  e.target.value * inputTasa.value; // Bs
            inputSubtotal.value = inputCantidad.value * inputCostoUnitario.value
        }
    }
}

const hanledBtnAgregarMetodoPago = (e) => {
    e.preventDefault();
    // log(e.target.id);

    // Agregamos un nuevo metodo de pago
    divOtroMetodoPago.innerHTML += htmlMetodoPago;

    // Obtenemos todos los metodos de pagos añadidos
    metodoPago = d.querySelectorAll(".metodoAdd");
    // log(metodoPago);

    // Eliminamos el metodo de pago
    metodoPago.forEach((elemento)=>{
        elemento.addEventListener('click', (e)=>{
            // log(e.target.id)
            // log(e.target.localName)
            if(e.target.id == 'eliminarMetodo'){
                e.target.parentElement.parentElement.innerHTML="";
                let resultadoDePagoCliente = 0;
                pagosCliente = d.querySelectorAll('.pagoCliente');
                pagosCliente.forEach((pago)=>{
                    log(pago)
                    resultadoDePagoCliente = resultadoDePagoCliente + parseFloat(pago.value);
                })
                // Cargamos el monto a pagar pendiente al vuelto
                inputVuelto.value = (inputTotal.value - resultadoDePagoCliente).toFixed(2);

                // Agregamos estilos
                if ( inputVuelto.value > 0) {
                    inputVuelto.classList.add('text-danger');
                    inputVuelto.classList.remove('text-success');
                    inputVuelto.classList.add('fs-4');
                }
            }
        })

        elemento.addEventListener('change', (e)=>{
            if (e.target.id == 'pagoCliente') {
                // obtenemos los pagos del cliente
                let resultadoDePagoCliente = 0;
                pagosCliente = d.querySelectorAll('.pagoCliente');
                pagosCliente.forEach((pago)=>{
                    log(pago)
                    resultadoDePagoCliente = resultadoDePagoCliente + parseFloat(pago.value);
                })
                // Cargamos el monto a pagar pendiente al vuelto
                inputVuelto.value = inputTotal.value - resultadoDePagoCliente;
                if ( inputVuelto.value <= 0) {
                    inputVuelto.classList.remove('text-danger');
                    inputVuelto.classList.add('text-success');
                    inputVuelto.classList.add('fs-4');
                }
            }    
        })
    });

  
};

// Cada ves que cargue la pagina se ejecuta
addEventListener("load", (ev) => {

    btnProcesar.hidden = true;
    // ocultar la carta de cliente
    razon_social .hidden = false;
    if (razon_social .children.length == 0) {
        razon_social .hidden = true;
    }

    // OCULTAMOS EL BOTON DE PROCESAR HASTA QUE LA DATA ESTE COMPLETA
    if (tdSubtotales.length) {
        tdSubtotales.forEach((element) => {
            // log(typeof(element.textContent))
            sub = parseFloat(element.textContent);
            subtotal = subtotal + sub;
        });

        // inpust subtotal
        inputSubtotalTemporalBs.value = (subtotal * parseFloat(inputTasa.value)).toFixed(2);
        inputSubtotalTemporalUsd.value = parseFloat(subtotal).toFixed(2);
        
        // inpust TOTAL
        inputTotal.value = (subtotal *inputTasa.value *(inputIva.value / 100 + 1)).toFixed(2);
        inputTotalDivisas.value = ((subtotal * inputTasa.value * (inputIva.value / 100 + 1)) /inputTasa.value).toFixed(2);

         // Cargamos el monto a pagar pendiente al vuelto

         btnProcesar.hidden = false;
    } else {
        log("No hay sutotales");
    }

    // Detectar los montos ingresados de los metodos de pago
      metodoPago = d.querySelectorAll(".metodoAdd");

      metodoPago.forEach((elemento)=>{
          elemento.addEventListener('change', (e)=>{
            if (e.target.id == 'pagoCliente') {
                // Cargamos el monto a pagar pendiente al vuelto
                inputVuelto.value = (inputTotal.value - e.target.value).toFixed(2);
                if ( inputVuelto.value <= 0) {
                    inputVuelto.classList.add('text-primary');
                }
            }
              
          })
      });

      
});


inputCostoUnitario.addEventListener('keyup', hanledCostoUnitario);
inputCostoUnitario.addEventListener('change', hanledCostoUnitario);

inputRif.addEventListener('keyup', hanledInputIdentificacion);
inputRif.addEventListener('change', hanledInputIdentificacion);

inputCodigoDeBarra.addEventListener('keyup', hanledInputCodigoBarra);
inputCodigoDeBarra.addEventListener('change', hanledInputCodigoBarra);

inputIva.addEventListener("change", hanledInputIva);

inputCantidad.addEventListener('keyup', handleSubtotal);
inputCantidad.addEventListener('change', handleSubtotal);

btnProcesar.addEventListener('click', hanledBotonProcesar);

inputDescuento.addEventListener("change", hanledInputDescuento);

trPvpInputsRadios.addEventListener("click", hanledInputRadiosPvp);
// btnAgregarAlCarro.addEventListener('clic', hanledClicAgregarAlCarrito);

btnAgregarMetodo.addEventListener("click", hanledBtnAgregarMetodoPago);



function setPvpInputsRadios(data, tasa) {
    return `
    <td colspan="7">
        <fieldset class="row mb-3">
                <legend class="col-form-label col-sm-2 pt-0">Precios</legend>
                <div class="col-sm-10 d-flex d-inline">
                    <div class="form-check ">
                        <input class="form-check-input" type="radio" name="pvp_2" id="pvp_2" value="${data.pvp_2}">
                        <label class="form-check-label" for="pvp_2">
                        ${(data.pvp_2 * tasa).toFixed(2)} BS | ${parseFloat(data.pvp_2).toFixed(2)} USD
                        </label>
                    </div>
                    <div class="form-check ms-3">
                        <input class="form-check-input" type="radio" name="pvp_3" id="pvp_3" value="${data.pvp_3}">
                        <label class="form-check-label" for="pvp_3">
                        ${(data.pvp_3 * tasa).toFixed(2)} BS | ${parseFloat(data.pvp_3).toFixed(2)} USD
                        </label>
                    </div>
                </div>
        </fieldset>
    </td>
    `;
}

