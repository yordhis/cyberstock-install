let btnImprimirFactura = document.querySelectorAll('.btnImprimirFactura');

log('conectado con facturas')
// log(btnImprimirFactura)


const hanledImprimirFactura = (e) => {
    log(e.target)
};


addEventListener('load', (e)=>{

    log(btnImprimirFactura)
    // btnImprimirFactura.forEach((btnImprimir)=>{
    //     btnImprimir.addEventListener('click', (e)=>{
    //         log(e.target)
       
    //     });
    // });

})