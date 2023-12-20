const componenteFila = (data) =>{
    return `
        <li class="notification-item">
            <i class="bi bi-exclamation-circle text-warning"></i>
            <div>
            <h4>El producto se agota!</h4>
            <h6>${data.codigo} | ${data.descripcion}</h6>
            <h6>En existencia:${data.cantidad}</h6>
            <p>fecha de notificación: ${new Date(data.created_at).toLocaleString()}</p>
            </div>
        </li>

        <li>
            <hr class="dropdown-divider">
        </li>
    `;
}

const hanledNotificaciones = async (e) =>{
    let elementoTotalNotificaciones = document.querySelector('#total_notificaciones_toolti'),
    elementoTotal = document.querySelector('#total_notificaciones'),
    lista = document.querySelector('#listaDeNotificaciones');

    elementoTotalNotificaciones.innerHTML = "...";

    resultados = await getNotificaciones();
    
    resultados.data.forEach(item => {
        lista.innerHTML += componenteFila(item)
    });

    elementoTotalNotificaciones.textContent = resultados.total;
    elementoTotal.textContent = resultados.total;


};


addEventListener('load', hanledNotificaciones);


const getNotificaciones = async () => {
    return await fetch(`${window.location.protocol}//${window.location.host}/api/getNotificaciones`, {
        method: "GET", 
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then( (response) =>  response.json() )
        .catch( (err) => err )
        .then( (data) => data );
};