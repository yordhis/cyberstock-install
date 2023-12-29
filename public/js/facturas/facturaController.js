/** TRAE EL CODIGO DE FACTURA ENTRADA - SALIDA - POS */
const getCodigoFactura = async (url) => {
    return await fetch(`${url}`, {      
        method: "GET", // or 'PUT'
        // body: JSON.stringify(filtro), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .catch(err => err)
    .then(data => data)
};

/** FACTURA LOS CARRITOS DEL SALIDA - ENTRADA */
const facturarCarrito = async (url, carrito) => {
    return await fetch(`${url}`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(carrito), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then( (res) => res.json() )
    .catch( (error) => error )
    .then( (response) => response ) 
};

/** Aqui se procesan las facturas de ENTRADA Y SALIDA */
const setFactura = async (url, factura) => {
    return await fetch(url, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(factura), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then( (res) => res.json() )
        .catch( (error) => error )
        .then( (response) => response )
};

/** FACTURAMOS LAS VENTAS */
const facturaStore = async (factura) => {
    return await fetch(`${URL_BASE}/facturas`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(factura), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then( (res) => res.json() )
        .catch( (error) => error )
        .then( (response) => response )
};

/** TICKET DE SALIDA NORMAL en dolares */
const htmlTicketSalidaV1 = (factura) => {
    // Declaracion de variables
    let carritoHtml = '', 
    descuentoHtml = '';
    // metodosPagos = factura.metodos.split(','),
    ivaHtml = '';

    // Recorremos el carrito
    factura.carrito.forEach(producto => {
        carritoHtml+=`
            <tr>
                <td class="producto">${producto.cantidad} X ${producto.descripcion}</td>

                <td class="precio">USD ${  darFormatoDeNumero( producto.subtotal ) }</td>
            </tr>
        `;
    });

    // Verificacamos si se aplico el impuesto
    if( factura.iva > 0 ){
        ivaHtml = `
            <tr>
                <td class="producto">IVA </td>

                <td class="precio"> USD ${ darFormatoDeNumero( factura.subtotal  * factura.iva )  }</td>
            </tr>
        `;
    }

    // Verificamos si se aplico un descuento
    if(factura.descuento > 0){
        descuentoHtml = ` 
            <tr>
                <td class="producto">Descuento ${factura.descuento}%</td>
                <td class="precio">USD ${ darFormatoDeNumero( (factura.subtotal * (factura.descuento/100)) ) }</td>
            </tr>
        `;
    }


    return `
        <div class="ticket" id="ticket">
        
            <img src="${factura.pos.imagen}" class="img" alt="Logotipo">
        
        
        <p class="centrado">
            <br>${factura.pos.empresa}
            <br>${factura.pos.rif}
            <br>${factura.pos.direccion}
            
        </p>
        <table>
            <thead>
                <tr>
                    <th colspan="2" class="producto">CLIENTE: ${factura.razon_social}</th>
                </tr>
                <tr>
                    <th colspan="2" class="producto">RIF: ${factura.identificacion}</th>
                </tr>
                <tr>
                    <th class="producto">N° FACTURA</th>
                    <th class="precio">${factura.codigo_factura}</th>
                </tr>
                <tr>
                    <th class="producto">FECHA</th>
        
                    <th class="precio">${factura.fecha}
                    </th>
                </tr>
                <tr>
                    <th class="producto">HORA</th>
        
                    <th class="precio">${factura.hora}
                    </th>
                </tr>
                <tr>
                    <th class="producto">CANT. / PRODUCTO</th>
                    <th class="precio">PVP</th>
                </tr>
                
            </thead>
            <tbody>
                
                ${carritoHtml}
                
                <tr>
                    <td class="producto">
                        |Total de Articulos: ${ factura.totalArticulo } | <br>
                        SUB-TOTAL <br>
                    </td>
        
                    <td class="precio"><br> USD  ${ darFormatoDeNumero( factura.subtotal  ) }</td>
                </tr>
              
                ${descuentoHtml}

                ${ivaHtml}

                <tr>
                    <td class="producto">TOTAL</td>
        
                    <td class="precio"> USD ${ darFormatoDeNumero( factura.total ) }</td>
                </tr>
              
                
            </tbody>
        </table>
        
        
        <p class="centrado">
            ¡GRACIAS POR SU COMPRA!
        </p>
        </div>
      `;
};

/** TICKET DE ENTRADA */
const htmlTicketEntrada = (factura) => {
    log(factura)
    // Declaracion de variables
    let carritoHtml = '', 
    descuentoHtml = '';
    // metodosPagos = factura.metodos.split(','),
    ivaHtml = '';

    // Recorremos el carrito
    factura.carrito.forEach(producto => {
        carritoHtml+=`
            <tr>
                <td class="producto" style="font-size: 12px;">${producto.cantidad} X ${producto.descripcion}</td>
                <td class="" style="font-size: 12px; text-align: left;">${darFormatoDeNumero(producto.costo)}</td>
                <td class="precio" style="font-size: 12px;">USD ${   darFormatoDeNumero( producto.subtotal ) }</td>
            </tr>
        `;
    });

    // Verificacamos si se aplico el impuesto
    if( factura.iva > 0 ){
        ivaHtml = `
            <tr>
                <td class="producto">IVA </td>

                <td colspan="2" class="precio"> USD ${ darFormatoDeNumero( factura.subtotal  * factura.iva )  }</td>
            </tr>
        `;
    }

    // Verificamos si se aplico un descuento
    if(factura.descuento > 0){
        descuentoHtml = ` 
            <tr>
                <td class="producto">Descuento ${factura.descuento}%</td>
                <td colspan="2" class="precio">USD ${ darFormatoDeNumero( (factura.subtotal * (factura.descuento/100)) ) }</td>
            </tr>
        `;
    }


    return `
        <div class="ticket" id="ticket">
        
        <p class="centrado">
            FACTURA DE COMPRA
        </p>
        <table>
            <thead>
                <tr>
                    <th class="producto">CÓDIGO ENTRADA</th>
                    <th colspan="2" class="precio">${factura.codigo}</th>
                </tr>
                <tr>
                    <th class="producto">CONCEPTO DE ENTRADA</th>
                    <th colspan="2" class="precio">${factura.concepto}</th>
                </tr>

                <tr>
                    <th class="producto">CÓDIGO FACTURA</th>
                    <th colspan="2" class="precio">${factura.codigo_factura}</th>
                </tr>
                
                <tr>
                    <th class="producto">PROVEEDOR:</th>   
                    <th colspan="2" class="precio">${factura.pos.empresa}</th>
                </tr>

                <tr>
                    <th class="producto">RIF:</th>                  
                    <th colspan="2" class="precio">${factura.pos.tipo_documento}-${factura.pos.codigo}</th>
                </tr>

                <tr>
                    <th class="producto">FECHA</th>
                    <th colspan="2" class="precio">${factura.fecha}</th>
                </tr>

                <tr>
                    <th class="producto">HORA</th>
                    <th colspan="2" class="precio">${factura.hora}</th>
                </tr>
                <tr>
                    <th class="producto"  style="font-size: 12px;">CANTIDAD/DESCRIPCIÓN</th>
                    <th class=""  style="font-size: 12px;  text-align: left;">C/U</th>
                    <th class="precio" style="font-size: 12px;">SUBTOTAL</th>
                </tr>
                
            </thead>
            <tbody>
                
                    ${carritoHtml}
                
        
        
                <tr>
                    <td class="producto">
                        |Total de Articulos: ${factura.totalArticulo } | <br>
                        SUB-TOTAL <br>
                    </td>
        
                    <td colspan="2" class="precio"><br> USD  ${ darFormatoDeNumero( parseFloat(factura.subtotal ) ) }</td>
                </tr>
              
                ${descuentoHtml}

                ${ivaHtml}

                <tr>
                    <td class="producto">TOTAL</td>
        
                    <td colspan="2" class="precio"> USD ${ darFormatoDeNumero( parseFloat(factura.total ) ) }</td>
                </tr>
              
                
            </tbody>
        </table>
        
        </div>
      `;
};

/** TICKET DE POS - VENTA */
const htmlTicket = (factura) => {
    // Declaracion de variables
    let carritoHtml = '', 
    descuentoHtml = '',
    metodosPagosHtml = '',
    logo = '',
    metodosPagos = JSON.parse(factura.metodos),
    ivaHtml = '',
    cambioHtml = '';

    // Recorremos el carrito
    factura.carrito.forEach(producto => {
        carritoHtml+=`
            <tr>
                <td class="producto">${producto.cantidad} X ${producto.descripcion}</td>

                <td class="precio">Bs ${ darFormatoDeNumero(quitarFormato(producto.subtotal) * factura.tasa) }</td>
            </tr>
        `;
    });

    // Verificacamos si se aplico el impuesto
    if( factura.iva > 0 ){
        ivaHtml = `
            <tr>
                <td class="producto">IVA 16%</td>

                <td class="precio">Bs ${ darFormatoDeNumero( factura.subtotal * factura.tasa * factura.iva )  }</td>
            </tr>
        `;
    }

    // Verificamos si se aplico un descuento
    if(factura.descuento > 0){
        descuentoHtml = ` 
            <tr>
                <td class="producto">Descuento ${factura.descuento}%</td>
                <td class="precio">Bs ${ darFormatoDeNumero( (factura.subtotal * ( factura.descuento/100 )) * factura.tasa ) }</td>
            </tr>
        `;
    }

    // recorremos los metodos de pagos
    let cambio = 0;
    metodosPagos.forEach((pago)=>{
        if(pago.tipoDePago == "DIVISAS"){
            metodosPagosHtml += `
                <tr>
                    <td class="producto">EFECTIVO 2</td>

                    <td class="precio">Bs ${ darFormatoDeNumero(pago.montoDelPago * factura.tasa) }</td>
                </tr>
            `;
            cambio += parseFloat(pago.montoDelPago * factura.tasa); 
        }else{
            metodosPagosHtml += `
                <tr>
                    <td class="producto">${pago.tipoDePago}</td>
    
                    <td class="precio">Bs ${ darFormatoDeNumero(pago.montoDelPago) }</td>
                </tr>
            `;
            cambio += parseFloat(pago.montoDelPago); 
        }
    });

    if(cambio > factura.total){
        cambioHtml = `
            <tr>
                <td class="producto">CAMBIO</td>

                <td class="precio">Bs ${ darFormatoDeNumero( cambio - ( factura.total * factura.tasa ) ) }</td>
            </tr>
        `;
    }

    // VERIFIVCAMOS SI SE DESEA IMPRIMIR CON EL LOGO
    if (factura.pos.estatusImagen) {
        logo = `<img src="${factura.pos.imagen}" class="img" alt="Logotipo">`;
    } else {
        logo= '';
    }

    return `
        <div class="ticket" id="ticket">
        
        ${logo}
        
        <p class="centrado empresa">
            <br>${factura.pos.empresa}
            <br>${factura.pos.rif}
            <br>${factura.pos.direccion}
            <br>ZONA POSTAL ${factura.pos.postal}
        </p>
        <table>
            <thead>
               
                <tr>
                    <th colspan="2" class="cantidad">CLIENTE: ${factura.cliente.nombre.toUpperCase()}</th>
                </tr>
                <tr>
                    <th colspan="2" class="cantidad">RIF: ${factura.cliente.tipo}-${factura.cliente.identificacion}</th>
                </tr>
                <tr>
                    <th class="producto"> N° ${factura.iva == 0 ? 'NOTA' : 'FACTURA'} </th>
        
                    <th class="precio"> ${factura.codigo} </th>
                </tr>
                <tr>
                    <th class="producto"> FECHA </th>
        
                    <th class="precio"> ${factura.fecha} </th>
                </tr>
                <tr>
                    <th class="producto">HORA</th>
        
                    <th class="precio"> ${factura.hora} </th>
                </tr>
                
            </thead>
            <tbody>
                
                    ${carritoHtml}
                
        
        
                <tr>
                    <td class="producto">
                        |Total de Articulos: ${factura.totalArticulo } | <br>
                        SUB-TOTAL <br>
                    </td>
        
                    <td class="precio"><br> Bs  ${ darFormatoDeNumero(factura.subtotal * factura.tasa) }</td>
                </tr>
              
                ${descuentoHtml}

                ${ivaHtml}

                <tr>
                    <td class="producto">TOTAL</td>
        
                    <td class="precio">Bs ${ darFormatoDeNumero(factura.total * factura.tasa)  }</td>
                </tr>

                <!-- Oculto para la roca -->
                <tr>
                    <td class="producto">TOTAL REF</td>
        
                    <td class="precio"> ${ darFormatoDeNumero(factura.total) }</td>
                </tr>
              
                ${metodosPagosHtml}
                ${cambioHtml}
            </tbody>
        </table>
        
        
        <p class="centrado">
            ¡GRACIAS POR SU COMPRA!
        </p>
        </div>
      `;
};

/** BUSCA LAS FACTURA POR SI CODIGO */
const getFacturaES = async (url, data) => {
    return await fetch(url, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(data), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then((res) => res.json())
    .catch((error) => error)
    .then((response) => response )
};

/** BUSCA LAS FACTURA POR SI CODIGO */
const getFactura = async (codigoFactura) => {
    return await fetch(`${URL_BASE}/getFactura/${codigoFactura}`, {
        method: "GET", // or 'PUT'
        // body: JSON.stringify(codigoFactura), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then((res) => res.json())
    .catch((error) => error)
    .then((response) => response )
};

/** TOMA EL ELEMENTO HTML PARA IMPRIMIR UN PDF O TIKCKET */
const imprimirElemento = (elemento) => {
    var ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>Factura</title>');
    ventana.document.write(`<base href="${URL_BASE_APP}/public" target="objetivo">`);
    ventana.document.write(`<style>
        * {
            margin-top: 0%;
            font-size: 12px;
            font-family: 'Times New Roman';
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid rgb(27, 25, 25);
            border-collapse: collapse;
        }

        td.producto,
        th.producto {
            width: 200px;
            max-width: 200px;
            text-align: left;
        }
        

        td.cantidad,
        th.cantidad {
            width: 100%;
            text-align: left;
            font-size: 12px;
            
        }

        td.precio,
        th.precio {
            font-size: 18px;
            width: 160px;
            max-width: 160px;
            word-break: break-all;
            text-align: right;
        }

        p.empresa{
            margin-top: 0%;
        }

        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 325px;
            max-width: 355px;
        }

        .img {
            margin: 1%;
            padding-left: 20%;
            width: 195px;
            max-width: 355px;
        }
    </style>`);
    ventana.document.write('</head><body >');
    ventana.document.write(elemento);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.print();
    ventana.close();
    return true;
};
 


/** IMRPIMIR ELEMTO CON FORMATO DE FORMULA LIBRE */
const imprimirElementoFormulaLibre = (elemento) => {
    var ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>Factura</title>');
    ventana.document.write(`<base href="${URL_BASE_APP}/public" target="objetivo">`);
    ventana.document.write(`<style>
            * {
            margin-top: 8%;
            font-size: 12px;
            font-family: 'Times New Roman';
        }

        body{
            margin: 0;
            position: relative;
            min-height: 100vh;
        }

        .img{
            width: 100px;
        }


        td,
        th,
        tr,
        table {
            padding: 5px;
            /* border-top: 1px solid rgb(27, 25, 25); */
            border-collapse: collapse;
            max-height: 90%;
        }

        .table-totales{
            position: absolute;
            bottom: 10em;
            width: 100%;
        }

        th.titulo{
            padding-left: 35px;
            text-align: left;
            font-size: 25px;
        }

        tr.border {
            border-top: 1px solid rgb(27, 25, 25);
            border-bottom: 1px solid rgb(27, 25, 25);
        }

        td.descripcion,
        th.descripcion {
            text-align: left;
            width: auto;
            max-width: 350px;
            text-align: left;
        }

        td.codigo,
        th.codigo {
            text-align: left;
            width: auto;
            max-width: 70px;
            text-align: left;
        }

        td.descripcion-producto,
        th.descripcion-producto {
            font-size: 11px;
            text-align: left;
            width: 400px;
            max-width: 450px;
            text-align: left;
        }

        td.numero,
        th.numero {
            text-align: right;
        }

        td.total-bruto{
            text-align: right;
        }
        td.total-neto{
            text-align: right;
        }

        .red{
            color: brown;
        }

        .centrado {
            margin: 0%;
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: 325px;
            max-width: 355px;
        }

        img {
            max-width: 150px;
            width: 150px;
            margin-top: 3%;
            margin-bottom: 0%;
            padding-left: 28%;
        }
    </style>`);
    ventana.document.write('</head><body >');
    ventana.document.write(elemento);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.print();
    ventana.close();
    return true;
};

/** REPORTE GENERAL DEL DIA */
const formulaLibreHtml = (factura) => {
    let metodosDePagoHtml = ``,
    carritoHtml="",
    ivaHtml="",
    descuentoHtml="",
    fecha = factura.fecha.split('T')[0],
    hora = factura.fecha.split('T')[1];

    /** RECORREMOS EL CARRITO DE COMPRA */
    // Recorremos el carrito
    factura.carrito.forEach(producto => {
        carritoHtml+=`
            <tr>
                <td class="codigo">${producto.codigo_producto}</td>
                <td class="descripcion-producto">${producto.descripcion}</td>
                <td class="numero">${producto.cantidad} </td>
                <td class="numero">${ darFormatoDeNumero(producto.costo) } </td>
                <td class="numero">${  darFormatoDeNumero( producto.subtotal ) }</td>
            </tr>
        `;
    });
    
    // Verificacamos si se aplico el impuesto
    if( factura.iva > 0 ){
        ivaHtml = `
            <td class="total-bruto">
                IVA 16%: 
                ${ darFormatoDeNumero( factura.subtotal  * factura.iva )  } 
            </td>
        `;
    }

    // Verificamos si se aplico un descuento
    if(factura.descuento > 0){
        descuentoHtml = ` 
                <td class="total-bruto">
                    DESCUENTO ${factura.descuento}%:  
                    ${ darFormatoDeNumero( (factura.subtotal * (factura.descuento/100)) ) } 
                </td>
        `;
    }


    return `
    <table class="table">
    <thead>
        
        <tr class="border">
            <th class="descripcion">CLIENTE:</th>
            <th class="descripcion">${factura.cliente.nombre.toUpperCase()}</th>
            <th colspan="3" class="numero">FACTURA | <span class="red">CONTADO</span></th>
        </tr>
        <tr>
            <th class="descripcion">N° CÉDULA:</th>
            <th class="descripcion">
                ${factura.cliente.tipo}-${factura.cliente.identificacion}
                / N° TELÉFONO: ${factura.cliente.telefono ? factura.cliente.telefono : "" }
            </th>
            <th colspan="3" class="numero">DIRECCIÓN: <span class="">${factura.cliente.direccion ? factura.cliente.direccion : ""}</span></th>
        </tr>
        <tr>
            <th class="descripcion">VENDEDOR:</th>
            <th class="descripcion">
                JUAN RAMIREZ
                / N° TELÉFONO: 0414-3534569
            </th>
            <th colspan="3" class="numero">FECHA DE EMISIÓN: <span class="">${fecha}</span></th>

        </tr>
        <tr class="border">
            <th class="codigo">CODIGO</th>
            <th class="descripcion">DESCRIPCIÓN</th>
            <th class="numero">CANTIDAD</th>
            <th class="numero">C/U</th>
            <th class="numero">SUBTOTAL</th>
        </tr>
    </thead>
    <tbody>
        ${carritoHtml}
    </tbody>
</table>

<table class="table-totales">

        <tr class="border">
            <td colspan="2" class="descripcion">CANTIDAD DE ITEMS: ${ factura.carrito.length} </td>
            <td class="total-bruto">SUBTOTAL: ${factura.subtotal} </td>
        </tr>
        <tr class="border">
            <td colspan="2">BULTOS:</td>
            ${descuentoHtml}
        </tr>
        <tr class="border">
            <td colspan="2">TRANSPORTE:</td>
            ${ivaHtml}
        </tr>
        <tr class="border">
            <td colspan="3" class="total-bruto">TOTAL: ${factura.total} </td>
        </tr>
        <tr class="border">
            <td colspan="3" class="centrado">${factura.pos.direccion} </td>
        </tr>
        

</table>
    `;

    // return `
    //         <table class="table">
    //             <thead>
                  
    //                 <tr>
    //                     <th colspan="2" class="descripcion">
    //                         FECHA: <br> 
    //                         ${fecha} Hora: ${hora}
    //                     </th>
                        
    //                     <th class="descripcion">VENDEDOR:</th>
    //                     <th colspan="" class="descripcion">MOTO SPORT LA ROCA</th>
    //                     <th colspan="" class="descripcion">TIPO: ${factura.concepto == "VENTA" ? "CONTADO" :  factura.concepto}</th>
                       
                        
    //                 </tr>
    //                 <tr>
    //                     <th colspan="5" class="titulo">
    //                         CLIENTE: ${factura.cliente.nombre.toUpperCase()} <br>
    //                         C.I.: ${factura.cliente.identificacion} <br>
    //                         DIRECCIÓN: ${factura.cliente.direccion ? factura.cliente.direccion : "No asignado." } <br>
    //                         TELÉFONO: ${factura.cliente.telefono ? factura.cliente.telefono : "No asignado." } <br>
    //                         CORREO: ${factura.cliente.correo ? factura.cliente.correo : "No poseé." } 
    //                     </th>
    //                 </tr>
    //                 <tr class="border">
    //                     <th class="descripcion">CÓDIGO</th>
    //                     <th class="descripcion">DESCRIPCIÓN</th>
    //                     <th class="numero">CANT.</th>
    //                     <th class="numero">C/U</th>
    //                     <th class="numero">SUBTOTAL</th>
    //                 </tr>
    //             </thead>
    //             <tbody>

    //                 ${carritoHtml}
                

    //             </tbody>

    //             <tfoot >
    //                 <tr class="border">
    //                     <td colspan="4" class="total-bruto">TOTAL BRUTO:</td>
    //                     <td  class="numero"> ${darFormatoDeNumero(factura.subtotal)} </td>
    //                 </tr>

    //                 ${descuentoHtml}
                    
    //                 ${ivaHtml}

    //                 <tr class="border">
    //                     <td colspan="4" class="total-bruto">TOTAL BRUTO:</td>
    //                     <td  class="numero"> ${darFormatoDeNumero(factura.total)} </td>
    //                 </tr>

    //             </tfoot>
    //         </table>
    // `;
};

/** FORMAS DE PAGO TOTALIZADAS*/
const formasDePagoTotalizadas = (metodosDePagos, formaDePago) => {
    for (const key in metodosDePagos) {
        if (Object.hasOwnProperty.call(metodosDePagos, key)) {
            const monto = metodosDePagos[key];
            if(key == formaDePago.tipoDePago) metodosDePagos[key] = monto + formaDePago.montoDelPago;
        }
    }
    return metodosDePagos;
};