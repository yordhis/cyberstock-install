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

/** BUSCA LAS FACTURA POR SU CODIGO */
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
            margin-top: 7%;
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
                padding: 2px;
                /* border-top: 1px solid rgb(27, 25, 25); */
                border-collapse: collapse;
                width: 60em;
            }

            
            td.descripcion{
                padding: 5px;
                font-size: 10px;
                max-width: 150px;
            }

            th.numero,
            td.numero{
                padding: 5px;
                width: auto;
                max-width: 150px;
            }

            .table-totales{
                padding: 2px;
                position: absolute;
                bottom: 15em;
                width: 60em;
            }

            .text__left{
                text-align: left;
            }
            .text__right{
                text-align: right;
            }

            tr.border {
                border-top: 1px solid rgb(27, 25, 25);
                border-bottom: 1px solid rgb(27, 25, 25);
            }

            .red{
                color: brown;
            }

            .centrado {
                margin: 0%;
                text-align: center;
                align-content: center;
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

/** FORMULA LIBRE - FACTURA */
const formulaLibreFacturaHtml = (factura) => {
    let metodosDePagoHtml = ``,
    carritoHtml="",
    ivaHtml="",
    descuentoHtml="",
    [dia,mes,anio] = factura.fecha.split('-');

    let fecha = new Date(anio,mes-1,dia).toLocaleString('ves', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }),
    hora = new Date(anio,mes-1,dia).toLocaleTimeString('ves');

    
    /** RECORREMOS EL CARRITO DE COMPRA */
    // Recorremos el carrito
    factura.carrito.forEach(producto => {
        carritoHtml+=`
            <tr>
                <td class="text__left numero">${producto.codigo_producto}</td>
                <td class="text__left descripcion">${producto.descripcion}</td>
                <td class="text__right numero">${producto.cantidad} </td>
                <td class="text__right numero">${ darFormatoDeNumero(producto.costo * factura.tasa) } </td>
                <td class="text__right numero">${  darFormatoDeNumero( producto.subtotal * factura.tasa) }</td>
            </tr>
        `;
    });
    
    // Verificacamos si se aplico el impuesto
        ivaHtml = `
            <td class="text__right">
                IVA 16%: 
                ${ darFormatoDeNumero( factura.subtotal  * 0.16 * factura.tasa )  } 
            </td>
        `;
    

    // Verificamos si se aplico un descuento
 
        descuentoHtml = ` 
                <td class="text__right">
                    DESCUENTO ${factura.descuento}%:  
                    ${ factura.descuento > 0 
                        ? darFormatoDeNumero( ( (factura.subtotal * factura.tasa) * (factura.descuento/100) ) )
                        : 0 
                    } 
                </td>
        `;


    return `
    <table class="table">
    <thead>
        
        <tr class="border">
            <th colspan="3" class="text__left">CLIENTE: ${factura.cliente.nombre.toUpperCase()}</th>
            <th colspan="2" class="text__right descripcion">FACTURA | <span class="red">${factura.concepto == "VENTA" ? "CONTADO" :  factura.concepto }</span></th>
        </tr>
        <tr>
            <th colspan="3" class="text__left">
                N° CÉDULA: ${factura.cliente.tipo}-${factura.cliente.identificacion}
                / N° TELÉFONO: ${factura.cliente.telefono ? factura.cliente.telefono : "" }
            </th>
            <th colspan="2" class="text__right descripcion">CÓDIGO CLIENTE: <span class="red">000${factura.cliente.id}</span></th>    
        </tr>
        <tr>
            <th colspan="5" class="text__left">DIRECCIÓN: <span class="">${factura.cliente.direccion ? factura.cliente.direccion : ""}</span></th>
        </tr>
        <tr>
            <th colspan="3" class="text__left">
                VENDEDOR: VENDEDOR 1
                / N° TELÉFONO: 0000-000000
            </th>
         </tr>
        <tr>
            <th colspan="5" class="text__left">FECHA DE EMISIÓN: <span class="">${fecha} - ${hora}</span></th>
        </tr>
        <tr class="border">
            <th class="text__left numero">CODIGO</th>
            <th class="text__left descripcion">DESCRIPCIÓN</th>
            <th class="text__right numero">CANTIDAD</th>
            <th class="text__right numero">C/U</th>
            <th class="text__right numero">SUBTOTAL</th>
        </tr>
    </thead>
    <tbody>
        ${carritoHtml}
    </tbody>
</table>

<table class="table-totales">

        <tr class="border">
            <td colspan="2" class="text__left">CANTIDAD DE ITEMS: ${ factura.carrito.length } </td>
            <td class="text__right">SUBTOTAL: ${darFormatoDeNumero( factura.subtotal * factura.tasa )} </td>
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
            <td colspan="3" class="text__right">
                    TOTAL: ${darFormatoDeNumero( factura.iva > 0 
                        ? factura.total * factura.tasa 
                        : factura.total * factura.tasa * 1.16 )} 
            </td>
        </tr>
        <tr class="border">
            <td colspan="3" class="centrado">${factura.pos.direccion} </td>
        </tr>
        

</table>
    `;
};

/** FORMULA LIBRE - NOTA DE ENTREGA */
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
                <td class="text__left">${producto.codigo_producto}</td>
                <td class="text__left">${producto.descripcion}</td>
                <td class="text__right">${producto.cantidad} </td>
                <td class="text__right">${ darFormatoDeNumero( producto.costo ) } </td>
                <td class="text__right">${ darFormatoDeNumero( producto.subtotal ) }</td>
            </tr>
        `;
    });

    // descuento
        descuentoHtml = ` 
                <td class="text__right">
                    DESCUENTO ${factura.descuento}%:  
                    ${ factura.descuento > 0 
                        ? darFormatoDeNumero( (factura.subtotal * (factura.descuento/100)) ) 
                        : 0
                    } 
                </td>
        `;
    


    return `
    <table class="table">
        <thead>
            
            <tr class="border">
                <th colspan="3"  class="text__left">
                    CLIENTE: ${factura.cliente.nombre.toUpperCase()}
                </th>
            
                <th colspan="2" class="text__right">NOTA | <span class="red">${factura.concepto == "VENTA" ? "CONTADO" :  factura.concepto }</span></th>
            </tr>
            <tr>
                <th colspan="3" class="text__left">
                    N° CÉDULA: ${factura.cliente.tipo}-${factura.cliente.identificacion}
                    / N° TELÉFONO: ${factura.cliente.telefono ? factura.cliente.telefono : "" }
                </th>
                <th colspan="2" class="text__right">CÓDIGO CLIENTE: <span class="red">000${factura.cliente.id}</span></th>
            </tr>
            <tr>
                <th colspan="5" class="text__left">DIRECCIÓN: <span class="">${factura.cliente.direccion ? factura.cliente.direccion : ""}</span></th>
            </tr>
            <tr>
                <th colspan="3" class="text__left">
                    VENDEDOR:  VENDEDOR 1
                    / N° TELÉFONO: 0000-0000000
                </th>
                <th colspan="2" class="text__right">FECHA DE EMISIÓN: <span class="">${fecha} - ${hora}</span></th>
            </tr>
            <tr class="border">
                <th class="text__left">CÓDIGO</th>
                <th class="text__left">DESCRIPCIÓN</th>
                <th class="text__right">CANTIDAD</th>
                <th class="text__right">C/U</th>
                <th class="text__right">SUBTOTAL</th>
            </tr>
        </thead>
        <tbody>
            ${carritoHtml}
        </tbody>
    </table>

    <table class="table-totales">

        <tr class="border">
            <td colspan="2" class="text__left">CANTIDAD DE ITEMS: ${ factura.carrito.length} </td>
            <td class="text__right">SUBTOTAL: ${darFormatoDeNumero( factura.subtotal )} </td>
        </tr>
        <tr class="border">
            <td colspan="2">BULTOS:</td>
            ${descuentoHtml}
        </tr>
        <tr class="border">
            <td colspan="2">TRANSPORTE:</td>
            <td colspan="3" class="text__right">TOTAL: ${darFormatoDeNumero( factura.subtotal - (factura.subtotal * factura.descuento) )} </td>
        
        </tr>
    
        <tr class="border">
            <td colspan="3" class="centrado">${factura.pos.direccion} </td>
        </tr>
        

    </table>
    `;
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