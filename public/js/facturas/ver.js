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
    await getFactura(e.target.id)
    .then(res =>{
        if (res.estatus == 200) {
            elementoCargando.innerHTML = '';
            $.confirm({
                title: '¡Datos preparado para imprimir!',
                content: 'Seleccione formato de impresión de la factura.',
                theme: 'modern',
                buttons: {
                    nota:{
                        text: 'Imprimir Nota de entrega en formato libre',
                        btnClass: 'btn-green',
                        action: function () {
                            let formulaLN = formulaLibreHtml(res.data);
                            setTimeout(() => imprimirElementoFormulaLibre(formulaLN), 1000);
                            return false; 
                        } 
                    }, 
                        
                    factura:{
                        text: 'Imprimir Factura en formato libre',
                        btnClass: 'btn-green',
                        action: function () {
                            let formulaLF = formulaLibreFacturaHtml(res.data);
                            setTimeout(() => imprimirElementoFormulaLibre(formulaLF), 1000);
                            return false; 
                        } 
                    }, 

                    ticket:{
                        text: 'Imprimir Ticket',
                        btnClass: 'btn-green',
                        action: function () {
                            let hTicket = htmlTicket(res.data);
                            setTimeout(() => imprimirElementoPos(hTicket), 1000);
                            return false; 
                        } 
                    }, 

                    cancel: function () {}
                }
            });

        }else{
            $.alert({
                title: 'Ups! algo salio mal',
                theme: 'modern',
                content: 'Vuelve a intentar, y si el error persiste contacta con soporte.',
                buttons:{
                    error:{
                        text: 'Ver detalles del error',
                        btnClass: 'btn-red',
                        action: function () {
                            $.alert(res.mensaje);
                        } 
                    }
                }
            })
            elementoCargando.innerHTML = '';
        }
    });

};

/** EVENTOS */

addEventListener('load', hanledLoad);

elementoAccionesFactura.forEach(element => {
    element.addEventListener('click', hanledImprimirFactura);
});