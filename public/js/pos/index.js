// Elementos del Pos
let inputDescuento = d.querySelector("#descuento"),
    inputIdentificacionCliente = d.querySelector("#cedula"),
    inputSubtotalTemporalBs = d.querySelector("#subtotal_temporal_bs"),
    inputSubtotalTemporalUsd = d.querySelector("#subtotal_temporal_usd"),
    inputIva = d.querySelector("#iva"),
    inputTotal = d.querySelector("#total"),
    inputTasa = d.querySelector("#tasa"),
    // Inputs del carrito
    inputCodigoBarra = d.querySelector("#codigo_producto"),
    inputCantidad = d.querySelector("#cantidad"),
    inputDescripcion = d.querySelector("#descripcion"),
    inputCostoUnitario = d.querySelector("#costo_unitario"),
    inputCostoUnitarioBs = d.querySelector("#costo_unitario_bs"),
    inputSubtotal = d.querySelector("#subtotal"),
    inputSubtotalBS = d.querySelector("#subtotalBS"),
    inputTotalDivisas = d.querySelector("#totalDivias"),
    mensajeInventario = d.querySelector("#mensajeInventario"),
    // Cierre de input del carrito
    
    // Sub-totales de la tabla carritos
    subtotal = 0,
    tdSubtotales = d.querySelectorAll(".subtotalProductos"),
    procesarVenta = d.querySelector("#procesarVenta"),
    elementoDataCliente = d.querySelector("#dataCliente"),
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
    btnAgregarMetodo = d.querySelector("#agregarMetodo");

log(metodoPago);

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

const hanledInputIdentificacion = async (e) => {
    // log(e.target.value);
    // log(inputIdentificacionCliente)
    if (e.target.value.length > 6) {
        resultado = await getCliente(e.target.value);
        cliente = await resultado.result;
        if (cliente.status == 200) {
            if (cliente.data.nombre == undefined) {
                log("entro");
                elementoDataCliente.hidden = false;
                elementoDataCliente.innerHTML = `<span class="text-danger">Cliente No registrado</span>`;
                procesarVenta.hidden = true;
            } else {
                elementoDataCliente.hidden = false;
                elementoDataCliente.innerHTML = `<h4>Cliente: ${cliente.data.nombre}</h4>`;
                procesarVenta.hidden = false;
            }
        }
    }
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
                inputCostoUnitarioBs.value = producto.data.pvp * inputTasa.value; // Bs
                inputCantidad.value = 1;
                calcularSubtotalDelProducto(parseFloat(inputCantidad.value), parseFloat(inputCostoUnitario.value))
                mensajeInventario.innerText = "";
            } else {
                inputDescripcion.value = null;
                inputCostoUnitario.value = ""; // usd
                inputCostoUnitarioBs.value = ""; // Bs
                inputCantidad.value = 0;
                calcularSubtotalDelProducto(parseFloat(inputCantidad.value), parseFloat(inputCostoUnitario.value))
                mensajeInventario.innerText = `${producto.message} `;
            }
            
        }
    }
};

const hanledInputSubtotal = (e) => {
    if (e.target.value) {
        calcularSubtotalDelProducto(e.target.value, parseFloat(inputCostoUnitario.value))
    }
};

const hanledInputSubtotalCosto = (e) => {
    if (e.target.value) {
        let cantidad = parseFloat(inputCantidad.value),
            costo = parseFloat(e.target.value);
        if (cantidad && costo) {
            inputSubtotal.value = cantidad * costo;
        } else {
            log("Los datos ingresados no son números!");
        }
    }
};

const hanledBotonProcesarVenta = async (e) => {
    if (tdSubtotales.length) {
        let inputsFactura = d.querySelectorAll(".factura"),
        metodosDePagos = '';



        inputsFactura.forEach((element, key) => {
            if (element.id == "dataCliente") {
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
        factura.razon_social = facturaTemporal.dataCliente.split(":")[1];
        factura.identificacion = facturaTemporal.cedula; // aqui va el nombre
        factura.subtotal = facturaTemporal.subtotal_temporal_usd;
        factura.total = facturaTemporal.totalDivias;
        factura.tasa = facturaTemporal.tasa;
        factura.iva = facturaTemporal.iva;
        factura.tipo = VENTA.id;
        factura.concepto = VENTA.name;
        factura.descuento = facturaTemporal.descuento;
        factura.fecha = facturaTemporal.fecha;
        factura.metodos = metodosDePagos; // metodo | monto,
       
        log(factura);
        if (inputVuelto.value > 0) {
            alert('Debe ingresar los metodos de pagos con sus montos y cumplir con la totalidad del pago!')
        }else{
            log('procesando factura');
            await facturaStore(factura);
        }
    } else {
        alert("Debe agregar productos a la factura para poder ser procesada");
    }
};

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

    // ocultar la carta de cliente
    elementoDataCliente.hidden = false;
    if (elementoDataCliente.children.length == 0) {
        elementoDataCliente.hidden = true;
    }

    // if (inputIdentificacionCliente.value > 0) {
    //     componenteCardCliente(inputIdentificacionCliente.value)
    // }
    // OCULTAMOS EL BOTON DE PROCESAR HASTA QUE LA DATA ESTE COMPLETA
    procesarVenta.hidden = true;
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
        inputVuelto.value = inputTotal.value;
        inputVuelto.classList.add('text-danger');

        procesarVenta.hidden = false;
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

inputDescuento.addEventListener("change", hanledInputDescuento);

inputIva.addEventListener("change", hanledInputIva);

inputCodigoBarra.addEventListener("keyup", hanledInputCodigoBarra);

inputCantidad.addEventListener("change", hanledInputSubtotal);

inputCostoUnitario.addEventListener("change", hanledInputSubtotalCosto);

procesarVenta.addEventListener("click", hanledBotonProcesarVenta);

inputIdentificacionCliente.addEventListener("keyup", hanledInputIdentificacion);

btnAgregarMetodo.addEventListener("click", hanledBtnAgregarMetodoPago);


function calcularSubtotalDelProducto(cantidad, costo) {
        if (cantidad && costo) {
            inputSubtotal.value = cantidad * costo;
            inputSubtotalBS.value = cantidad * costo * parseFloat(inputTasa.value);
        } else {
            // alert("Los datos ingresados no son números!");
            inputSubtotal.value = 0;
            inputSubtotalBS.value = 0;
        }
}