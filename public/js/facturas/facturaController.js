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


const facturaStoreSalida  = async (factura) => {
    log('llego aqui')
     await fetch(`${URL_BASE}/storeSalida`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(factura), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((res) => res.json())
        .catch((error) => console.error("Error:", error))
        .then((response) => {
            log(response)
            if (response.estatus == 200) {
                resultado = confirm("Factura procesada correctamente, ¿Deseas imprimir el comprobante?");
                if (resultado) {
                    imprimirElemento(htmlTicket(response.data));
                    
                    resultadoOtraCapia = confirm("¿Deseas imprimir otra copia del comprobante?");
                    if (resultadoOtraCapia) {
                        imprimirElemento(htmlTicket(response.data));
                    }
                    window.location.href = "http://cyberstock.com/inventarios/crearSalida";
                } else {
                    window.location.href = "http://cyberstock.com/inventarios/crearSalida";
                }
            } else {
                alert(response.mensaje)
            }
        })
};

const facturaStoreEntrada  = async (factura) => {
    log('llego aqui')
     await fetch(`${URL_BASE}/facturasInventarios`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(factura), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((res) => res.json())
        .catch((error) => console.error("Error:", error))
        .then((response) => {
            log(response)
            if (response.estatus == 200) {
                resultado = confirm("Factura procesada correctamente, ¿Deseas imprimir el comprobante?");
                if (resultado) {
                    imprimirElemento(htmlTicketEntrada(response.data));
                    
                    resultadoOtraCapia = confirm("¿Deseas imprimir otra copia del comprobante?");
                    if (resultadoOtraCapia) {
                        imprimirElemento(htmlTicketEntrada(response.data));
                    }
                    window.location.href = "http://cyberstock.com/inventarios/crearEntrada";
                } else {
                    window.location.href = "http://cyberstock.com/inventarios/crearEntrada";
                }
            } else {
                alert(response.mensaje)
            }
        })
};

const htmlTicketEntrada = (factura) => {
    // Declaracion de variables
    let carritoHtml = '', 
    descuentoHtml = '',
    metodosPagosHtml = '',
    // metodosPagos = factura.metodos.split(','),
    ivaHtml = '';

    // Recorremos el carrito
    factura.carrito.forEach(producto => {
        carritoHtml+=`
            <tr>
                <td class="producto">${producto.cantidad} X ${producto.descripcion}</td>

                <td class="precio">Bs ${parseFloat(producto.subtotal * factura.tasa).toFixed(2) }</td>
            </tr>
        `;
    });

    // Verificacamos si se aplico el impuesto
    if( factura.iva > 0 ){
        ivaHtml = `
            <tr>
                <td class="producto">IVA </td>

                <td class="precio">Bs ${parseFloat(factura.subtotal * factura.tasa * (factura.iva/100)).toFixed(2)  }</td>
            </tr>
        `;
    }

    // Verificamos si se aplico un descuento
    if(factura.descuento > 0){
        descuentoHtml = ` 
            <tr>
                <td class="producto">Descuento ${factura.descuento}%</td>
                <td class="precio">Bs ${((factura.subtotal * (factura.descuento/100)) * factura.tasa).toFixed(2)}</td>
            </tr>
        `;
    }


    return `
        <div class="ticket" id="ticket">
        
            <img src="${factura.pos.imagen}" alt="Logotipo">
        
        
        <p class="centrado">
            <br>${factura.pos.empresa}
            <br>${factura.pos.codigo}
            <br>${factura.pos.direccion}
            
        </p>
        <table>
            <thead>
                <tr>
                    <th class="producto">FACTURA DE ENTRADA</th>
        
                    <th class="precio">${factura.codigo}</th>
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
                
            </thead>
            <tbody>
                
                    ${carritoHtml}
                
        
        
                <tr>
                    <td class="producto">
                        |Total de Articulos: ${factura.totalArticulo } | <br>
                        SUB-TOTAL <br>
                    </td>
        
                    <td class="precio"><br> Bs  ${parseFloat(factura.subtotal * factura.tasa).toFixed(2) }</td>
                </tr>
              
                ${descuentoHtml}

                ${ivaHtml}

                <tr>
                    <td class="producto">TOTAL</td>
        
                    <td class="precio">Bs ${ parseFloat(factura.total * factura.tasa).toFixed(2)  }</td>
                </tr>
              
                
            </tbody>
        </table>
        
        
        <p class="centrado">
            ¡GRACIAS POR SU COMPRA!
        </p>
        </div>
      `;
};


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
        logo = `<img src="${factura.pos.imagen}" alt="Logotipo">`;
    } else {
        logo= '';
    }

    return `
        <div class="ticket" id="ticket">
        
        ${logo}
        
        <p class="centrado">
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
                    <th class="producto"> N° FACTURA </th>
        
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

const imprimirElemento = (elemento) => {
    var ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>Factura</title>');
    ventana.document.write('<base href="http://cyberstock.com/public" target="objetivo">');
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