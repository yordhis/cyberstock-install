

class Autorizacion {
    _password = ""
    setClave(pass){
        this._password=pass;
    }
    getClave(){
        return this._password;
    }
    async verificarClave(){
        return fetch(`${URL_BASE_TWO}/verificarClave`, {
            method: "POST",
            body: JSON.stringify(this._password),
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then(res => res.json())
        .then(response => response)
        
    }
};

const URL_BASE_TWO = `${window.location.protocol}//${window.location.host}/api`

const spinnerTwo = (clases = "text-primary") => `
    <div class="spinner-border ${clases}" role="status" id="spinnerGlobal">
        <span class="visually-hidden">CARGANDO...</span>
    </div>
`;

const componenteAlertaTwo = (mensaje, estatus, clasesExtras = "") => {
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

/** MANAJADORES DE EVENTOS */
const hanledFormularioDeAutorizacion = async (e) => {
    if(e.target.id != "cerrarSesion" && e.target.id != "filtro"){
        e.preventDefault();

        let autorizacion = new Autorizacion,
        resultado="";

        /** Seteamos la clave en el objeto */
        autorizacion.setClave(await validarDataDeFormularioAutorizacion(e.target));

        /** mostrar carga */
        e.target.children[4].innerHTML = spinnerTwo();

        /** Validar que la clave concida con la de algun aministrador */
        resultado = await autorizacion.verificarClave()
     
        if(resultado.estatus == 200){
            e.target.children[4].innerHTML = componenteAlertaTwo(resultado.mensaje, resultado.estatus);
            /** Enviamos el formulario */
            e.target.submit();
        }else{
            e.target.children[4].innerHTML = componenteAlertaTwo(resultado.mensaje, resultado.estatus);
        }
        
        
    }
};

// /** LOS EVENTOS */
let formulariosAuth = document.forms;
for (const iterator of formulariosAuth) {
    iterator.addEventListener('submit', hanledFormularioDeAutorizacion);
}


/** Validar formulario de cliente y retornar data */
async function validarDataDeFormularioAutorizacion(formulario){
    let esquema = {},
    banderaDeALertar = 0;
    for (const iterator of formulario) {
        if(iterator.localName == "input" && iterator.name == "clave"){
            if(!iterator.value.length) iterator.classList.add(['border-danger']), banderaDeALertar++, iterator.nextElementSibling.textContent=`El campo ${iterator.name} es obligatorio`; 
            else iterator.classList.remove(['border-danger']), iterator.classList.add(['border-success']), iterator.nextElementSibling.textContent="";
            // Asignamos el valor al esquema
            esquema['password'] = iterator.value;
        }
    }
    if(banderaDeALertar) return false;
    else return esquema;
}