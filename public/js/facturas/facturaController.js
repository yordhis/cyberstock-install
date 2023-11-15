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

};

const facturaStore = async (factura) => {
    await fetch(`${URL_BASE}/facturas`, {
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
            // if (response.estatus == 200) {
            //     resultado = confirm("Factura procesada correctamente, ¿Deseas imprimir el comprobante?");
            //     if (resultado) {
            //         imprimirElemento(htmlTicket(response.data));
                    
            //         resultadoOtraCapia = confirm("¿Deseas imprimir otra copia del comprobante?");
            //         if (resultadoOtraCapia) {
            //             imprimirElemento(htmlTicket(response.data));
            //         }
            //         window.location.href = "http://cyberstock.com/pos";
            //     } else {
            //         window.location.href = "http://cyberstock.com/pos";
            //     }
            // } else {
            //     alert(response.mensaje)
            // }
        })
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



function imprimirElemento(elemento) {
    var ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>Factura</title>');
    ventana.document.write('<base href="http://cyberstock.com/public" target="objetivo">');
    ventana.document.write(`<style>
        * {
            margin-top: 0%;
            font-size: 20px;
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
            width: 0px;
            max-width: 0px;
            word-break: break-all;
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
    metodosPagos = factura.metodos.split(','),
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

    // recorremos los metodos de pagos
    metodosPagos.forEach((pago)=>{
        metodosPagosHtml += `
            <tr>
                <td class="producto">${pago.split('|')[0]}</td>

                <td class="precio">Bs ${ parseFloat(pago.split('|')[1]).toFixed(2)  }</td>
            </tr>
        `;
    });

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
                    <th class="producto">CLIENTE: ${factura.cliente.nombre.toUpperCase()}</th>
                </tr>
                <tr>
                    <th class="producto">RIF: ${factura.cliente.tipo}${factura.cliente.identificacion}</th>
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
                <tr>
                    <td class="producto">TOTAL REF</td>
        
                    <td class="precio">Bs ${ parseFloat(factura.total).toFixed(2)  }</td>
                </tr>
              
                ${metodosPagosHtml}
            </tbody>
        </table>
        
        
        <p class="centrado">
            ¡GRACIAS POR SU COMPRA!
        </p>
        </div>
      `;
};


const imprimirFactura = async (codigoFactura) => {
    await fetch(`${URL_BASE}/imprimirFactura/${codigoFactura}`, {
        method: "GET", // or 'PUT'
        // body: JSON.stringify(codigoFactura), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then((res) => res.json())
    .catch((error) => console.error("Error:", error))
    .then((response) => {
        if (response.estatus == 200) {
            log(response.data)
            // alert("Factura procesada correctamente");
            // window.location.href = "http://stocklte.com/pos";
        }else{
            alert(response.mensaje)
        }
    })
};