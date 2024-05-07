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


const barraDePorcentaje = (total, contador) => {
    let porcentaje = ( ( contador * 100 ) / total );
    return `
        <div class="progress" role="progressbar" aria-label="Animated striped example" aria-valuenow="${porcentaje}" aria-valuemin="${porcentaje}" aria-valuemax="100">
            <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width: ${porcentaje}%;">${porcentaje.toFixed(2)}%  Total a procesar: ${total} | Procesados: ${contador}</div>
        </div>
    `;
}

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
        <div class="alert ${estatusText} d-flex align-items-center alert-dismissible fade show alertaGlobal" role="alert" id="alertaGlobal">
                <i class='${icono} ${clasesExtras}' ></i>
                <p class='${clasesExtras}'> ${mensaje} </p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    `;
};

const darFormatoDeNumero = (numero) => {
    return  new Intl.NumberFormat("de-DE", {
      maximumFractionDigits: 2,
      minimumFractionDigits: 2
    }).format(numero)
    // return  new Intl.NumberFormat("de-DE", {
    //   maximumFractionDigits: 2,
    // }).format(numero)
};

/** REGISTRA LOS MOVIMIENTOS DE LOS USUARIO Y SUS VENTAS
 * @param data  : usuairo, nombre, transaccion, observacion, codigo, ubicacion, dispositivo, fecha.
 * @return number (200 o 401)
 */
const registrarAccionDelUsuario = async (data) =>{
    return await fetch(`${URL_BASE}/registrarAccionDelUsuario`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(data), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then( (res) => res.json() )
    .catch( (error) => error )
    .then( (response) => response ) 
};

/** EJECUTA LA ACCIÓN */
const ejecutarRegistroDeAccionDelUsuario = async (observacion, estatus) =>  {
    let monitorDeMovimiento = {
        usuario: d.querySelector("#usuario").value,
        nombre: d.querySelector("#nombreUsuario").value, 
        transaccion: URL_PATHNAME, 
        observacion: observacion,
        codigo: estatus,
        ubicacion: window.location.href, 
        dispositivo: await getIpClient() == undefined ? "127.0.0.1" : await getIpClient(), 
        fecha: Date()
    }, 
    resultadoM = "";

    return resultadoM = await registrarAccionDelUsuario(monitorDeMovimiento);

};

const  getIpClient = async () => {
    try {
      return await fetch('https://api.ipify.org?format=json')
      .then(res => res.json())
      .then(ip => ip.ip)
    } catch (error) {
      console.error(error);
    }
}

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
};

const alertJQuery = ( mensaje, estatus, title = "¡Alerta!", type="red",
    botones = {
        tryAgain: {
            text: 'Volver a intentar',
            btnClass: 'btn-orange',
            action: function () { }
        }
    }) => {

    return $.confirm({
        title: title + estatus,
        content: mensaje,
        type: type,
        typeAnimated: true,
        buttons: botones
    });

};

