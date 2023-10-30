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
producto = null;



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
        resultado = await getProductoData(e.target.value);
       
        producto = await resultado.result;

        log(producto.data);
        if (producto.status == 200) {
            if (producto.data.id > 0) {
                inputDescripcion.value = producto.data.descripcion;
                // inputCostoUnitario.value = producto.data.pvp; // usd
                // inputCostoUnitarioBs.value =
                //     producto.data.pvp * inputTasa.value; // Bs

                mensajeInventario.innerText = "";
            } else {
                mensajeInventario.innerText = `${producto.message} `;
                inputDescripcion.value = '';
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

const hanledRifProveedor = async (e) =>{
    // log(e.key)
    if(e.target.value.length > 4){
        razon_social .hidden = false;
        await getProveedor(e.target.value, razon_social )
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
      

        // metodosDePagos = metodosDePagos.substring(0, metodosDePagos.length-1);
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
        // factura.metodos = metodosDePagos; // metodo | monto,
       
        log(factura);
        if( facturaTemporal.conceptoDeMovimiento.length == 0 ){
            alert('Por favor, ingrese el consepto del movimiento de inventario; Para proceder con la solicitud.')
        }else{
            log('procesando factura');
            await facturaStoreEntrada(factura);
        }
        
    } else {
        alert("Debe agregar productos a la factura para poder ser procesada");
    }
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


      
});


inputCostoUnitario.addEventListener('keyup', hanledCostoUnitario);

inputRif.addEventListener('keyup', hanledRifProveedor);

inputCodigoDeBarra.addEventListener('keyup', hanledInputCodigoBarra);

inputIva.addEventListener("change", hanledInputIva);

inputCantidad.addEventListener('change', handleSubtotal);

btnProcesar.addEventListener('click', hanledBotonProcesar);

inputDescuento.addEventListener("change", hanledInputDescuento);
// btnAgregarAlCarro.addEventListener('clic', hanledClicAgregarAlCarrito);



