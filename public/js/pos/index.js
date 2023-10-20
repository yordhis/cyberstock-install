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
    subtotal = 0,
    // Sub-totales de la tabla carritos
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
    facturaTemporal = {};


const hanledInputIdentificacion = async (e) => {
    // log(e.target.value);
    // log(inputIdentificacionCliente)
    if (e.target.value.length > 6) {
        resultado = await getCliente(e.target.value);
        cliente = await resultado.result;
        if (cliente.status == 200) {
            if (cliente.data.nombre == undefined) {
                log('entro')
                elementoDataCliente.hidden = false;
                elementoDataCliente.innerHTML = `<span class="text-danger">Cliente No registrado</span>`;
                procesarVenta.hidden = true;
            }else {
                elementoDataCliente.hidden = false;
                elementoDataCliente.innerHTML = `<h4>Cliente: ${cliente.data.nombre}</h4>`;
                procesarVenta.hidden = false;
            }
        } 
    }
};

const hanledInputDescuento = (e) => {
    log(e.target.value);
    // log(subtotal)
    if (e.target.value) {
        inputSubtotalTemporalBs.value = (
            subtotal * parseFloat(inputTasa.value) -
            subtotal * parseFloat(inputTasa.value) * (e.target.value / 100)
        ).toFixed(2);
        subtotalTemporal = inputSubtotalTemporalUsd.value = (
            subtotal -
            subtotal * (e.target.value / 100)
        ).toFixed(2);
        subtotalTemporal = parseFloat(subtotalTemporal);
        inputTotal.value = (
            subtotalTemporal *
            inputTasa.value *
            (inputIva.value / 100 + 1)
        ).toFixed(2);
        inputTotalDivisas.value = (
            (subtotalTemporal * inputTasa.value * (inputIva.value / 100 + 1)) /
            inputTasa.value
        ).toFixed(2);
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
    
        log(producto.data)
        if (producto.status == 200) {
            if(producto.data.id > 0){
                inputDescripcion.value = producto.data.descripcion;
                inputCostoUnitario.value = producto.data.pvp; // usd
                inputCostoUnitarioBs.value = producto.data.pvp * inputTasa.value; // Bs

                mensajeInventario.innerText = "";
            }else{
                mensajeInventario.innerText = `${producto.message} `;
            }
        }
    }
};

const hanledInputSubtotal = (e) => {
    if (e.target.value) {
        let cantidad = parseFloat(e.target.value),
            costo = parseFloat(inputCostoUnitario.value);
        if (cantidad && costo) {
            inputSubtotal.value = cantidad * costo;
            inputSubtotalBS.value = cantidad * costo * parseFloat(inputTasa.value);
        } else {
            log("Los datos ingresados no son números!");
        }
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
   
    if(tdSubtotales.length){

        let inputsFactura = d.querySelectorAll(".factura");
    
        inputsFactura.forEach((element, key) => {
            if(element.id == "dataCliente"){
                log("entro")
                facturaTemporal[element.id] = element.textContent;
            }else{
                facturaTemporal[element.id] = element.value;
            }
        });
        // log(facturaTemporal);
        // adactamos para enviar a guardar e imprimir ticket
        factura.codigo = facturaTemporal.codigo;
        factura.razon_social = facturaTemporal.dataCliente.split(':')[1];
        factura.identificacion = facturaTemporal.cedula; // aqui va el nombre
        factura.subtotal = facturaTemporal.subtotal_temporal_usd;
        factura.total = facturaTemporal.totalDivias;
        factura.tasa = facturaTemporal.tasa;
        factura.iva = facturaTemporal.iva;
        factura.tipo = VENTA.id;
        factura.concepto = VENTA.name;
        factura.descuento = facturaTemporal.descuento;

        await facturaStore(factura);
    }else{
        alert("Debe agregar productos a la factura para poder ser procesada");
    }

};

// Cada ves que cargue la pagina se ejecuta
addEventListener("load", (ev) => {

        // ocultar la carta de cliente
        elementoDataCliente.hidden = false;
        if(elementoDataCliente.children.length == 0){
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

        // inpust
        inputSubtotalTemporalBs.value = (
            subtotal * parseFloat(inputTasa.value)
        ).toFixed(2);
        inputSubtotalTemporalUsd.value = parseFloat(subtotal).toFixed(2);
        inputTotal.value = (
            subtotal *
            inputTasa.value *
            (inputIva.value / 100 + 1)
        ).toFixed(2);
        inputTotalDivisas.value = (
            (subtotal * inputTasa.value * (inputIva.value / 100 + 1)) /
            inputTasa.value
        ).toFixed(2);

        procesarVenta.hidden = false;
    } else {
        log("No hay sutotales");
    }
});

inputDescuento.addEventListener("change", hanledInputDescuento);

inputIva.addEventListener("change", hanledInputIva);

inputCodigoBarra.addEventListener("keyup", hanledInputCodigoBarra);

inputCantidad.addEventListener("change", hanledInputSubtotal);

inputCostoUnitario.addEventListener("change", hanledInputSubtotalCosto);

procesarVenta.addEventListener("click", hanledBotonProcesarVenta);

inputIdentificacionCliente.addEventListener(
    "keyup",
    hanledInputIdentificacion
);


