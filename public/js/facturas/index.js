let btnImprimirFactura = d.querySelector('#imprimirFactura');

log('conectado con facturas')
log(btnImprimirFactura)


const hanledImprimirFactura = (e) => {
    e.preventDefault()
    log(e.target)
};


btnImprimirFactura.addEventListener('click', hanledImprimirFactura);