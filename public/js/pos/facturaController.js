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
            if(resultado){
                imprimirElemento(htmlTicket)
            }else{
                window.location.href = "http://cyberstock.com/pos";
            }
        }else{
            alert(response.mensaje)
        }
    })
}

function imprimirElemento(elemento){
    var ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>' + document.title + '</title>');
    ventana.document.write('</head><body >');
    ventana.document.write(elemento);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.print();
    ventana.close();
    return true;
  }

  const htmlTicket = `
  <div class="ticket" id="ticket">
  @if ($pos[0]->estatusImagen)
      <img src="" alt="Logotipo">
  @endif

  <p class="centrado">
      
      <br>J-505256564
      <br>Barins Venezuela
      <br>ZONA POSTAL 5201
  </p>
  <table>
      <thead>
          <tr>
              <th class="producto">FACTURA</th>

              <th class="precio">000001</th>
          </tr>
          <tr>
              <th class="producto">FECHA</th>

              <th class="precio">11-02-2023
              </th>
          </tr>
          <tr>
              <th class="producto">HORA</th>

              <th class="precio">02:45pm
              </th>
          </tr>
         
      </thead>
      <tbody>
          
              <tr>
                  <td class="producto">10 X productos</td>

                  <td class="precio">Bs 100</td>
              </tr>
              <tr>
                  <td class="producto">10 X productos</td>

                  <td class="precio">Bs 100</td>
              </tr>
              <tr>
                  <td class="producto">10 X productos</td>

                  <td class="precio">Bs 100</td>
              </tr>
              <tr>
                  <td class="producto">10 X productos</td>

                  <td class="precio">Bs 100</td>
              </tr>
         


          <tr>
              <td class="producto">
                  |Total de Articulos:30| <br>
                  SUB-TOTAL <br>
              </td>

              <td class="precio"><br> Bs
                  100</td>
          </tr>
          <tr>
              <td class="producto">IVA </td>

              <td class="precio">Bs
                 10545
              </td>
          </tr>
          <tr>
              <td class="producto">TOTAL</td>

              <td class="precio">Bs 54546</td>
          </tr>
          <tr>
              <td class="producto">EFECTIVO 3</td>

              <td class="precio">Bs 31354</td>
          </tr>
      </tbody>
  </table>


  <p class="centrado">
      ¡GRACIAS POR SU COMPRA!
  </p>
</div>
  `;
// const imprimirFactura = async (codigoFactura) => {
//     await fetch(`${URL_BASE}/facturas/${codigoFactura}`, {
//         method: "POST", // or 'PUT'
//         // body: JSON.stringify(codigoFactura), // data can be `string` or {object}!
//         headers: {
//           "Content-Type": "application/json",
//         },
//     })
//     .then((res) => res.json())
//     .catch((error) => console.error("Error:", error))
//     .then((response) => {
//         if (response.estatus == 200) {
//             alert("Factura procesada correctamente");
//             window.location.href = "http://stocklte.com/pos";
//         }else{
//             alert(response.mensaje)
//         }
//     })
// }