console.log("conectado al master");
const URL_BASE = `/api`,
URL_BASE_APP = ``,
d = document,
log = console.log,
VENTA = {
    id:1,
    name: "VENTA"
},
spinner = `
    <div class="spinner-border text-success" role="status" id="spinnerGlobal">
    <span class="visually-hidden">CARGANDO...</span>
    </div>
`;

const componenteAlerta = (mensaje, estatus) => {
    let estatusText = '',
    icono = '';

    switch (estatus) {
        case 200:
            estatusText = "alert-success"
            icono = '#check-circle-fill';
            break;
        case 404:
            estatusText = "alert-danger";
            icono = '#exclamation-triangle-fill';
            break;
        case 401:
            estatusText = "alert-warning";
            icono = '#exclamation-triangle-fill';
            break;
        case 500:
            estatusText = "alert-danger";
            icono = '#check-circle-fill';
            break;
        default:
            estatusText = "alert-primary";
            icono = '#exclamation-triangle-fill';
            break;
    }


    return `
        <div class="alert ${estatusText} d-flex align-items-center alertaGlobal" role="alert" id="alertaGlobal">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="${icono}"/></svg>
            <div>
                ${mensaje}
            </div>
        </div>
    `;
};

const darFormatoDeNumero = (numero) => {
    return  new Intl.NumberFormat("de-DE", {
      maximumFractionDigits: 2,
    }).format(numero)
};

const formatoUSD = (numero) => {
    return  new Intl.NumberFormat("en-US", {
      maximumFractionDigits: 2,
    }).format(numero)
};

const quitarFormato = (numeroString) =>{
    let arraysinformato = '',
    arregloDeNumeros = numeroString.split('');
    
    if(numeroString.includes(',')){
        arraysinformato = arregloDeNumeros.map(item => {
            if(item == ".") return "";
            if(item == ",") return ".";
            return item;
        });
    }else{
        arraysinformato = arregloDeNumeros;
    }
    return parseFloat(arraysinformato.join(''));
}