log('conectado a inventario')
/** VARIABLES */
let elementoTablaCuerpo = d.querySelector('lista')

/** COMPONENTES */
const componenteFila = (data) => {
    return `
    <tr>
        <td>${data.numero}</td>
        <td>${data.codigo}</td>
        <td>${data.descripcion}</td>
        <td>${data.cantidad}</td>
        <td>${data.costo}</td>
        <td>${data.pvp} Bs</td>
        <td>${data.pvp_usd} $</td>
        <td>${data.marca}</td>
        <td>${data.categoria}</td>
        <td>
            <button type="button" class="btn btn-success" id="activarModalVer/${data.id}"><i class="bi bi-eye"></i></button>
            <button type="button" class="btn btn-danger" id="activarModalEliminar/${data.id}"><i class="bi bi-trash"></i></button>
            <button type="button" class="btn btn-warning" id="activarModalEditar/${data.id}"><i class="bi bi-pencil"></i></button>
        </td>
    </tr>
    `;
};

/** MANEJADORES DE EVENTO */
const hanledLoad = (e)=>{
    console.log('hola load');
    fetch(`${URL_BASE}/inventarios`, {
        method: "GET", // or 'PUT'
        // body: JSON.stringify(idProduct), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })

};

/** EVENTOS */
addEventListener('load', hanledLoad);

/** FUNCIONES O UTILIDADES EXTRAS */