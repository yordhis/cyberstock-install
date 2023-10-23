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
            if (response.estatus == 200) {
                resultado = confirm("Factura procesada correctamente, ¿Deseas imprimir el comprobante?");
                if (resultado) {
                    imprimirElemento(htmlTicket(response.data))
                    window.location.href = "http://cyberstock.com/pos";
                } else {
                    window.location.href = "http://cyberstock.com/pos";
                }
            } else {
                alert(response.mensaje)
            }
        })
}

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
}

const htmlTicket = (factura) => {
    let carritoHtml = '', 
    ivaHtml = '';
    factura.carrito.forEach(producto => {
        carritoHtml+=`
            <tr>
                <td class="producto">${producto.cantidad} X ${producto.descripcion}</td>

                <td class="precio">Bs ${producto.subtotal * factura.tasa }</td>
            </tr>
        `;
    });

    if( factura.iva > 0 ){
        ivaHtml = `
            <tr>
                <td class="producto">IVA </td>

                <td class="precio">Bs ${parseFloat(factura.subtotal * factura.tasa * (factura.iva/100)).toFixed(2)  }</td>
            </tr>
        `;
    }

    return `
        <div class="ticket" id="ticket">
        
            <img src="${factura.pos.imagen}" alt="Logotipo">
        
        
        <p class="centrado">
            <br>${factura.pos.empresa}
            <br>${factura.pos.rif}
            <br>${factura.pos.direccion}
            <br>ZONA POSTAL ${factura.pos.postal}
        </p>
        <table>
            <thead>
                <tr>
                    <th class="producto">FACTURA</th>
        
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

                ${ivaHtml}

                <tr>
                    <td class="producto">TOTAL</td>
        
                    <td class="precio">Bs ${ parseFloat(factura.total * factura.tasa).toFixed(2)  }</td>
                </tr>
                <tr>
                    <td class="producto">EFECTIVO 1</td>
        
                    <td class="precio">Bs ${parseFloat(factura.total * factura.tasa).toFixed(2) }</td>
                </tr>
            </tbody>
        </table>
        
        
        <p class="centrado">
            ¡GRACIAS POR SU COMPRA!
        </p>
        </div>
      `;
}


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
}