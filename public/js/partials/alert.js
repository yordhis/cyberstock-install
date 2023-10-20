/** Constante con las respuesta y estilos configurados */
const respuesta = {
    mensaje: "No Funcionó",
    activo: null,
    estatus: 404,
    clases: {
        "200": "alert-success",
        "201": "alert-success",
        "301": "alert-warning",
        "401": "alert-warning",
        "404": "alert-danger",
        },
    icono:{
        "200": "bi bi-check-circle me-1",
        "201": "bi bi-check-circle me-1",
        "301": "bi bi-exclamation-triangle me-1",
        "401": "bi bi-exclamation-octagon me-1",
        "404": "bi bi-exclamation-octagon me-1",
    } 
    
};

/**
 * Esta funcion me retorna los valores de los parametros enviados por
 * la url.
 * @param String name
 * @return String
 */
function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
    results = regex.exec(location.search);
    return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
};

let estatus = getParameterByName('estatus'),
mensaje = getParameterByName('mensaje');

if(mensaje){
    let alertHtml = document.getElementById('alert');
    alertHtml.innerHTML = `
        <div class="row">
        <div class="col-sm-8"></div>
        <div class="col-sm-4">
        <div class="alert ${respuesta.clases[estatus]} alert-dismissible fade show" role="alert">
            <i class="${respuesta.icono[estatus]}"></i>
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        </div>
        </div>
    `;

    setTimeout(()=>{
        alertHtml.innerHTML = "";
    }, 5000);
}



