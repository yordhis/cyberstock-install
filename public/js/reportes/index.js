let elementoReporteRapido = d.querySelectorAll('.reporte-rapido');

log(elementoReporteRapido);

/** MANEJADORES DE EVENTOS */
const hanledReporteRapido = (e) => {
    log(e.target);

    switch (e.target.id) {
        case 'reporteDelDia':
            
            break;
        case 'reporteSemanal':
            
            break;
        case 'reporteDelMes':
            
            break;
    
        default:
            break;
    }
};

/** EVENTOS */
elementoReporteRapido.forEach(elemento => {
    elemento.addEventListener('click', hanledReporteRapido);
})


/** UTILIDADES Y ADAPTADORES */