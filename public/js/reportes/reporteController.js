/**
 * 
 * @param {*} url 
 * @param {tipo, rango:{inicio,fin}} config 
 * @param {tipo: "dia, semanal, mensual, personalizada"}
 * @returns 
 */

const storeReportes = async (url, config) => {
    return await fetch(url,{
        method: "POST",
        body: JSON.stringify(config),
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then(res => res.json())
    .catch(err => err)
    .then(data => data);
};

const getEmpresa = async () => {
    return await fetch(`${URL_BASE}/getEmpresa`,{
        method: "GET",
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then(res => res.json())
    .catch(err => err)
    .then(data => data);
};


/** TOMA EL ELEMENTO HTML PARA IMPRIMIR UN PDF O TIKCKET */
const imprimirElemento = (elemento) => {
    var ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>Factura</title>');
    ventana.document.write(`<base href="${URL_BASE_APP}/public" target="objetivo">`);
    ventana.document.write(`<style>
        * {
            margin-top: 0%;
            font-size: 10px;
            font-family: 'Times New Roman';
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
        }

        th.titulo{
            padding-left: 35px;
            text-align: left;
            font-size: 16px;
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
        
        }
        td.descripcion-producto,
        th.descripcion-producto {
            font-size: 11px;
            text-align: left;
            width: auto;
            max-width: 350px;
        }
        td.descripcion-num,
        th.descripcion-num {
            font-size: 11px;
            text-align: right;
            width: auto;
        }
        


        td.numero,
        th.numero {
            text-align: right;
            font-size: 14px;
        }

        td.total-bruto{
            text-align: right;
        }
        td.total-neto{
            text-align: right;
        }
        /* 

        td.precio,
        th.precio {
            font-size: 18px;
            width: 160px;
            max-width: 160px;
            word-break: break-all;
            text-align: right;
        } */

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


/** REPORTE DETALLADO POR RANGO DE FECHA */
const reportePorRangoDetalladolHtml = (reporte, empresa, config) => {

    let listaDeVentasDetallada = ``,
    subtotalBruto = 0,
    totalCosto = 0,
    totalIva = 0,
    totalGanacia = 0;
  
    reporte.forEach(producto => {
        listaDeVentasDetallada += `
            <tr>
                <td> ${producto.codigo} </td>
                <td class="descripcion-producto"> (${producto.codigo_producto}) - ${producto.descripcion} </td>
                <td class="numero">${producto.cantidad}</td>
                <td class="numero">${darFormatoDeNumero(producto.costoProveedor)}</td>
                <td class="numero">${producto.iva == 0 ? 'Excento' : darFormatoDeNumero(producto.iva * producto.subtotal) }</td>
                <td class="numero">${darFormatoDeNumero(producto.costo)}</td>
                <td class="numero">${darFormatoDeNumero(producto.subtotal)}</td>
            </tr>
        `;

        subtotalBruto +=  parseFloat(producto.subtotal);
        totalCosto +=  parseFloat(producto.costoProveedor * producto.cantidad);
        totalIva +=  parseFloat(producto.iva * producto.subtotal);
    });
    
    totalGanacia +=  parseFloat( subtotalBruto - (totalCosto + totalIva) );

    return `
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <img src="${URL_BASE_APP}/${empresa.imagen}" class="img" alt="">
                        </th>
                        <th colspan="8" class="titulo">
                            REPORTE DE VENTAS
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" class="descripcion">
                            RANGO DE FECHA DEL REPORTE: <br> 
                            INICIO: <span style="color:red;">${config.rango.inicio.split('-').reverse().join('/')}</span> 
                            HASTA: <span style="color:red;">${config.rango.fin.split('-').reverse().join('/')}</span>
                        </th>
                        
                        <th class="descripcion">EMPRESA:</th>
                        <th colspan="2" class="descripcion">${empresa.empresa.toUpperCase()}</th>
                        <th colspan="2" class="descripcion">TIPO DE REPORTE: DETALLADO</th>
                       
                        
                    </tr>
                    <tr class="border">
                        <th class="descripcion">CODIGO FACTURA</th>
                        <th class="descripcion">PRODUCTO</th>
                        <th class="numero">CANTIDAD</th>
                        <th class="numero">COSTO</th>
                        <th class="numero">IVA</th>
                        <th class="numero">PRECIO</th>
                        <th class="numero">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>

                    ${listaDeVentasDetallada}
                

                </tbody>

                <tfoot >
                    <tr class="border">
                        <td colspan="6" class="total-bruto">SUBTOTAL BRUTO:</td>
                        <td  class="numero"> ${darFormatoDeNumero(subtotalBruto)} USD </td>
                    </tr>
                    <tr class="border">
                        <td colspan="6" class="total-neto">COSTO:</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalCosto)} USD </td>
                    </tr>
                    <tr class="border">
                        <td colspan="6" class="total-neto">COSTO IVA 16%:</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalIva)} USD </td>
                    </tr>
                    <tr class="border">
                        <td colspan="6" class="total-neto">TOTAL NETO (GANACIA):</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalGanacia)} USD </td>
                    </tr>
                </tfoot>
            </table>
    `;
};

/** REPORTE GENERAL POR RANGO DE FECHA */
const reportePorRangoGeneralHtml = (reporte, empresa, config) => {

    let listaDeVentas = ``,
    metodosDePagoHtml = ``,
    totalBruto = 0,
    totalIva = 0,
    totalGanacia = 0,
    metodosDePagos = {
        EFECTIVO: 0,
        DIVISAS: 0,
        TD: 0,
        TC: 0,
        PAGOMOVIL: 0,
        TRANSFERENCIA: 0,
    };

    /** RECORREMOS LAS FACTURAS */
    reporte.forEach(factura => {

        let formasDePagos = JSON.parse(factura.metodos),
        formasDePagoHtml= ``;
        formasDePagos.forEach((formaDePago, index) => {
            barra = index ? '|' : '';
            formasDePagoHtml+= `${barra} ${formaDePago.tipoDePago} MONTO:${darFormatoDeNumero(formaDePago.montoDelPago)}`;
            metodosDePagos = formasDePagoTotalizadas(metodosDePagos, formaDePago);
        }); 

        listaDeVentas += `
            <tr>
                <td> ${factura.codigo} </td>
                <td> ${factura.fecha.split('T')[0].split('-').reverse().join('/')} </td>
                <td class="descripcion-producto"> 
                    ${factura.razon_social}<br>  
                    C.I.:${factura.identificacion} 
                </td>
                <td class="descripcion">${formasDePagoHtml}</td>
                <td class="numero">${factura.cantidad_articulos}</td>
                <td class="numero">${darFormatoDeNumero(factura.subtotal)}</td>
                <td class="numero">${factura.iva == 0 ? 'Excento' : darFormatoDeNumero(factura.iva * factura.subtotal) }</td>
                <td class="numero">${darFormatoDeNumero(factura.total)}</td>
            </tr>
        `;

        totalBruto +=  parseFloat(factura.total);
        totalIva +=  parseFloat(factura.iva * factura.subtotal);
    });
    
    /** CONFIGURAMOS LOS TOTALES DE LAS FORMAS DE PAGO */
    metodosDePagoHtml = `
        <td colspan="9" class="centrado">
            <b>FORMAS DE PAGOS:</b>
            EFECTIVO: ${darFormatoDeNumero(metodosDePagos.EFECTIVO)} |
            DIVISAS: ${darFormatoDeNumero(metodosDePagos.DIVISAS)} |
            TRANSFERENCIA: ${darFormatoDeNumero(metodosDePagos.TRANSFERENCIA)} |
            PAGO MOVIL: ${darFormatoDeNumero(metodosDePagos.PAGOMOVIL)} |
            TD PUNTO: ${darFormatoDeNumero(metodosDePagos.TD)} |
            TC PUNTO: ${darFormatoDeNumero(metodosDePagos.TC)}
        </td>
    `;
    // totalGanacia +=  parseFloat( subtotalBruto - (totalCosto + totalIva) );

    return `
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <img src="${URL_BASE_APP}/${empresa.imagen}" class="img" alt="">
                        </th>
                        <th colspan="9" class="titulo">
                            REPORTE DE VENTAS
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" class="descripcion">
                            RANGO DE FECHA DEL REPORTE: <br> 
                            INICIO: <span style="color:red;">${config.rango.inicio.split('-').reverse().join('/')}</span> 
                            HASTA: <span style="color:red;">${config.rango.fin.split('-').reverse().join('/')}</span>
                        </th>
                        
                        <th colspan="3" class="descripcion">EMPRESA: ${empresa.empresa.toUpperCase()}</th>
                        <th colspan="3" class="descripcion">TIPO DE REPORTE: GENERAL</th>
                       
                        
                    </tr>
                    <tr class="border">
                        <th class="descripcion">CODIGO <br> FACTURA</th>
                        <th class="descripcion">FECHA FACTURA</th>
                        <th class="descripcion">CLIENTE</th>
                        <th class="descripcion">FORMA DE PAGO</th>
                        <th class="descripcion-num">CANTIDAD ART.</th>
                        <th class="descripcion-num">SUBTOTAL</th>
                        <th class="descripcion-num">IVA</th>
                        <th class="descripcion-num">TOTAL</th>
                    </tr>
                </thead>
                <tbody>

                    ${listaDeVentas}
                

                </tbody>

                <tfoot >
                    <tr class="border">
                        <td colspan="7" class="total-neto">TOTAL IVA (16%):</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalIva)} USD </td>
                    </tr>

                    <tr class="border">
                        <td colspan="7" class="total-bruto">TOTAL BRUTO:</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalBruto)} USD </td>
                    </tr>
                 
                   
                    <tr class="border">
                        ${metodosDePagoHtml}
                    </tr>
                    <tr class="border">
                        <td colspan="9" class="centrado"> TOTAL DE VENTAS: <b>${reporte.length}</b> </td>
                    </tr>
                </tfoot>
            </table>
    `;
};


/** REPORTE GENERAL DEL DIA */
const reporteHtmlGeneral = (reporte, empresa, config) => {

    /** arrglando fecha */
    let fechaPersonalizada = config.rango.inicio.split('T');
    fechaArray = fechaPersonalizada[0].split('-'),
    horaArray = fechaPersonalizada[1].split(':');

    let listaDeVentas = ``,
    metodosDePagoHtml = ``,
    totalBruto = 0,
    totalIva = 0,
    totalGanacia = 0,
    [anio,mes,dia] = fechaArray,
    [hor,min,seg] = horaArray,
    metodosDePagos = {
        EFECTIVO: 0,
        DIVISAS: 0,
        TD: 0,
        TC: 0,
        PAGOMOVIL: 0,
        TRANSFERENCIA: 0,
    };

    let fecha = new Date(anio,mes-1,dia,hor,min,seg).toLocaleString('ves', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }),
    hora = new Date(anio,mes-1,dia,hor,min,seg).toLocaleTimeString('ves');

    /** RECORREMOS LAS FACTURAS */
    reporte.forEach(factura => {

        let formasDePagos = JSON.parse(factura.metodos),
        formasDePagoHtml= ``;
        formasDePagos.forEach((formaDePago, index) => {
            barra = index ? '|' : '';
            formasDePagoHtml+= `${barra} ${formaDePago.tipoDePago} MONTO:${darFormatoDeNumero(formaDePago.montoDelPago)}`;
            metodosDePagos = formasDePagoTotalizadas(metodosDePagos, formaDePago);
        }); 

        listaDeVentas += `
            <tr>
                <td> ${factura.codigo} </td>
                <td class="descripcion-producto"> 
                    ${factura.razon_social}<br>  
                    ${factura.identificacion} 
                </td>
                <td class="numero">${formasDePagoHtml}</td>
                <td class="numero">${factura.cantidad_articulos}</td>
                <td class="numero">${darFormatoDeNumero(factura.subtotal)}</td>
                <td class="numero">${factura.iva == 0 ? 'Excento' : darFormatoDeNumero(factura.iva * factura.subtotal) }</td>
                <td class="numero">${darFormatoDeNumero(factura.total)}</td>
            </tr>
        `;

        totalBruto +=  parseFloat(factura.total);
        totalIva +=  parseFloat(factura.iva * factura.subtotal);
    });
    
    /** CONFIGURAMOS LOS TOTALES DE LAS FORMAS DE PAGO */
    metodosDePagoHtml = `
        <td colspan="7" class="centrado">
            <b>FORMAS DE PAGOS:</b>
            EFECTIVO: ${darFormatoDeNumero(metodosDePagos.EFECTIVO)} |
            DIVISAS: ${darFormatoDeNumero(metodosDePagos.DIVISAS)} |
            TRANSFERENCIA: ${darFormatoDeNumero(metodosDePagos.TRANSFERENCIA)} |
            PAGO MOVIL: ${darFormatoDeNumero(metodosDePagos.PAGOMOVIL)} |
            TD PUNTO: ${darFormatoDeNumero(metodosDePagos.TD)} |
            TC PUNTO: ${darFormatoDeNumero(metodosDePagos.TC)}
        </td>
    `;
    // totalGanacia +=  parseFloat( subtotalBruto - (totalCosto + totalIva) );

    return `
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <img src="${URL_BASE_APP}/${empresa.imagen}" class="img" alt="">
                        </th>
                        <th colspan="8" class="titulo">
                            REPORTE DE VENTA DEL DIA
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" class="descripcion">
                            FECHA DE EMISIÓN DEL REPORTE: <br> 
                            ${fecha} Hora: ${hora}
                        </th>
                        
                        <th class="descripcion">EMPRESA:</th>
                        <th colspan="2" class="descripcion">${empresa.empresa.toUpperCase()}</th>
                        <th colspan="2" class="descripcion">TIPO DE REPORTE: GENERAL</th>
                       
                        
                    </tr>
                    <tr class="border">
                        <th class="descripcion">CODIGO FACTURA</th>
                        <th class="descripcion">CLIENTE</th>
                        <th class="numero">FORMA DE PAGO</th>
                        <th class="numero">CANTIDAD ART.</th>
                        <th class="numero">SUBTOTAL</th>
                        <th class="numero">IVA</th>
                        <th class="numero">TOTAL</th>
                    </tr>
                </thead>
                <tbody>

                    ${listaDeVentas}
                

                </tbody>

                <tfoot >
                    <tr class="border">
                        <td colspan="6" class="total-neto">TOTAL IVA (16%):</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalIva)} USD </td>
                    </tr>

                    <tr class="border">
                        <td colspan="6" class="total-bruto">TOTAL BRUTO:</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalBruto)} USD </td>
                    </tr>
                 
                   
                    <tr class="border">
                        ${metodosDePagoHtml}
                    </tr>
                    <tr class="border">
                        <td colspan="7" class="centrado"> TOTAL DE VENTAS: <b>${reporte.length}</b> </td>
                    </tr>
                </tfoot>
            </table>
    `;
};

/** REPORTE DETALLADO DEL DIA */
const reporteHtml = (reporte, empresa, config) => {

    /** arrglando fecha */
    let fechaPersonalizada = config.rango.inicio.split('T');
    fechaArray = fechaPersonalizada[0].split('-'),
    horaArray = fechaPersonalizada[1].split(':');

    let listaDeVentasDetallada = ``,
    subtotalBruto = 0,
    totalCosto = 0,
    totalIva = 0,
    totalGanacia = 0,
    [anio,mes,dia] = fechaArray,
    [hor,min,seg] = horaArray;

    let fecha = new Date(anio,mes-1,dia,hor,min,seg).toLocaleString('ves', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }),
    hora = new Date(anio,mes-1,dia,hor,min,seg).toLocaleTimeString('ves');

    reporte.forEach(producto => {
        listaDeVentasDetallada += `
            <tr>
                <td> ${producto.codigo} </td>
                <td class="descripcion-producto"> (${producto.codigo_producto}) - ${producto.descripcion} </td>
                <td class="numero">${producto.cantidad}</td>
                <td class="numero">${darFormatoDeNumero(producto.costoProveedor)}</td>
                <td class="numero">${producto.iva == 0 ? 'Excento' : darFormatoDeNumero(producto.iva * producto.subtotal) }</td>
                <td class="numero">${darFormatoDeNumero(producto.costo)}</td>
                <td class="numero">${darFormatoDeNumero(producto.subtotal)}</td>
            </tr>
        `;

        subtotalBruto +=  parseFloat(producto.subtotal);
        totalCosto +=  parseFloat(producto.costoProveedor * producto.cantidad);
        totalIva +=  parseFloat(producto.iva * producto.subtotal);
    });
    
    totalGanacia +=  parseFloat( subtotalBruto - (totalCosto + totalIva) );

    return `
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <img src="${URL_BASE_APP}/${empresa.imagen}" class="img" alt="">
                        </th>
                        <th colspan="8" class="titulo">
                            REPORTE DE VENTA DEL DIA
                        </th>
                    </tr>
                    <tr>
                        <th colspan="3" class="descripcion">
                            FECHA DE EMISIÓN DEL REPORTE: <br> 
                            ${fecha} Hora: ${hora}
                        </th>
                        
                        <th class="descripcion">EMPRESA:</th>
                        <th colspan="2" class="descripcion">${empresa.empresa.toUpperCase()}</th>
                        <th colspan="2" class="descripcion">TIPO DE REPORTE: DETALLADO</th>
                       
                        
                    </tr>
                    <tr class="border">
                        <th class="descripcion">CODIGO FACTURA</th>
                        <th class="descripcion">PRODUCTO</th>
                        <th class="numero">CANTIDAD</th>
                        <th class="numero">COSTO</th>
                        <th class="numero">IVA</th>
                        <th class="numero">PRECIO</th>
                        <th class="numero">SUBTOTAL</th>
                    </tr>
                </thead>
                <tbody>

                    ${listaDeVentasDetallada}
                

                </tbody>

                <tfoot >
                    <tr class="border">
                        <td colspan="6" class="total-bruto">SUBTOTAL BRUTO:</td>
                        <td  class="numero"> ${darFormatoDeNumero(subtotalBruto)} USD </td>
                    </tr>
                    <tr class="border">
                        <td colspan="6" class="total-neto">COSTO:</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalCosto)} USD </td>
                    </tr>
                    <tr class="border">
                        <td colspan="6" class="total-neto">COSTO IVA 16%:</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalIva)} USD </td>
                    </tr>
                    <tr class="border">
                        <td colspan="6" class="total-neto">TOTAL NETO (GANACIA):</td>
                        <td  class="numero"> ${darFormatoDeNumero(totalGanacia)} USD </td>
                    </tr>
                </tfoot>
            </table>
    `;
};


/** FORMAS DE PAGO */
const formasDePagoTotalizadas = (metodosDePagos, formaDePago) => {
    for (const key in metodosDePagos) {
        if (Object.hasOwnProperty.call(metodosDePagos, key)) {
            const monto = metodosDePagos[key];
            if(key == formaDePago.tipoDePago) metodosDePagos[key] = monto + formaDePago.montoDelPago;
        }
    }
    return metodosDePagos;
};