let formularios = d.forms;

class Autorizacion {
    _password = ""
    setClave(pass){
        this._password=pass;
    }
    getClave(){
        return this._password;
    }
    async verificarClave(){
        return fetch(`${URL_BASE}/verificarClave`, {
            method: "POST",
            body: JSON.stringify(this._password),
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then(res => res.json())
        .then(response => response)
        
    }
}

/** MANAJADORES DE EVENTOS */
const hanledFormulario = async (e) => {
    if(e.target.id != "cerrarSesion"){
        e.preventDefault();

        let autorizacion = new Autorizacion,
        resultado="";

        /** Seteamos la clave en el objeto */
        autorizacion.setClave(await validarDataDeFormulario(e.target));

        /** mostrar carga */
        e.target.children[4].innerHTML = spinner;

        /** Validar que la clave concida con la de algun aministrador */
        resultado = await autorizacion.verificarClave()
     
        if(resultado.estatus == 200){
            e.target.children[4].innerHTML = componenteAlerta(resultado.mensaje, resultado.estatus);
            /** Enviamos el formulario */
            e.target.submit();
        }else{
            e.target.children[4].innerHTML = componenteAlerta(resultado.mensaje, resultado.estatus);
        }
        
        
    }
};

// /** LOS EVENTOS */
for (const iterator of formularios) {
    iterator.addEventListener('submit', hanledFormulario);
}


/** Validar formulario de cliente y retornar data */
async function validarDataDeFormulario(formulario){
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