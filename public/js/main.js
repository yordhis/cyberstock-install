console.log("conectado al master");

const URL_BASE = `${window.location.protocol}//${window.location.host}/api`,
URL_BASE_APP = `${window.location.protocol}//${window.location.host}`,
URL_PATHNAME = `${window.location.pathname}`,
d = document,
log = console.log,
VENTA = {
    id:1,
    name: "VENTA"
},
spinner = (clases = "text-primary") => `
    <div class="spinner-border ${clases}" role="status" id="spinnerGlobal">
        <span class="visually-hidden">CARGANDO...</span>
    </div>
`;

const componenteAlerta = (mensaje, estatus, clasesExtras = "") => {
    let estatusText = '',
    icono = '';

    switch (estatus) {
        case 200:
            estatusText = "alert-success";
            icono = 'bx bxs-check-circle';
            break;
        case 404:
            estatusText = "alert-danger";
            icono = 'bx bx-x';
            break;
        case 401:
            estatusText = "alert-warning";
            icono = 'bx bxs-message-alt-error';
            break;
        case 500:
            estatusText = "alert-danger";
            icono = 'bx bxs-coffee-alt';
            break;
        default:
            estatusText = "alert-primary";
            icono = 'bx bxs-message-alt-error';
            break;
    }


    return `
        <div class="alert ${estatusText} d-flex align-items-center alertaGlobal" role="alert" id="alertaGlobal">
                <i class='${icono} ${clasesExtras}' ></i>
                <p class='${clasesExtras}'> ${mensaje} </p>
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