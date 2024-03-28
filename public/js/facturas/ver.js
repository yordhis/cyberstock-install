let elementoMetodosDePago = d.querySelector('#metodosPagos'),
elementoVuelto = d.querySelector('#vuelto'),
elementoAccionesFactura = d.querySelectorAll('.acciones-factura'),
elementoCargando = d.querySelector('#cargando'),
elementoCodigoFactura = d.querySelector('#codigoFactura'),
factura = {};

const hanledLoad = async () => {
    log(elementoCodigoFactura.textContent.trim())
    log(typeof(elementoCodigoFactura.textContent.trim()))
    factura = await getFactura(elementoCodigoFactura.textContent.trim());
    log(factura)
 
    /** Si la factura existe mostramosl o metodos de pagos */
    if (factura.estatus == 200) {
        metodos = JSON.parse(factura.data.metodos);
        let abonado = 0;
        
        metodos.forEach(metodo => {
            elementoMetodosDePago.innerHTML += ` 
                <div class="d-flex justify-content-between  m-0 p-0" >
                    <div class="p-2 bd-highlight">${metodo.tipoDePago == 'DIVISAS' ? 'EFECTIVO 2': metodo.tipoDePago}</div>
                    <div class="p-2 bd-highlight">Bs ${metodo.tipoDePago == 'DIVISAS' ? darFormatoDeNumero(metodo.montoDelPago * factura.data.tasa) : darFormatoDeNumero(metodo.montoDelPago)}</div>
                </div>
            `;
          
            if(metodo.tipoDePago == 'DIVISAS') abonado += parseFloat(metodo.montoDelPago) * factura.data.tasa;
            else abonado += parseFloat(metodo.montoDelPago);
        
        });
         
        abonado = abonado - factura.data.total * factura.data.tasa;
        elementoVuelto.innerHTML = ` 
            <div class="p-2 bd-highlight">CAMBIO</div>
            <div class="p-2 bd-highlight">Bs ${ darFormatoDeNumero(abonado)}</div>
        `;
       
    } else {
        alert(factura.mensaje)
    }
}

/** MANEJADOR DE EVENTOS */
const hanledImprimirFactura = async (e) => {
    let codigoFactura = '';
    if(e.target.localName == 'i') codigoFactura = e.target.parentElement.id;
    if(e.target.localName == 'bottom') codigoFactura = e.target.id;

    elementoCargando.innerHTML = spinner();
    resultado = await getFactura(e.target.id);
    // log(resultado);
    if (resultado.estatus == 200) {
        let ticket = htmlTicket(resultado.data);
        setTimeout(()=>imprimirElementoPos(ticket), 1000);
        elementoCargando.innerHTML = '';
    } else {
        alert(resultado.mensaje)
        elementoCargando.innerHTML = '';
    }
};

/** EVENTOS */

addEventListener('load', hanledLoad);

elementoAccionesFactura.forEach(element => {
    element.addEventListener('click', hanledImprimirFactura);
});