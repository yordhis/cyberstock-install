log('conectado a los controladores')

const getInventarios = async (url) => {
    return await fetch(`${url}`, {      
        method: "GET", // or 'PUT'
        //body: JSON.stringify(filtro), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .catch(err => log(err))
    .then(data => data)
};

const getInventariosFiltro = async ( url, filtro ) => {
    return await fetch(`${url}`, {      
        method: "POST", // or 'PUT'
        body: JSON.stringify(filtro), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then(response => response.json())
    .catch(err => err)
    .then(data => data)
};

const editarProductoDelInventario = async (url, data) => {
    return await fetch(`${url}`, {
        method: "PUT", // or 'PUT'
        body: JSON.stringify(data), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then( (response) => response.json() )
    .catch( (error) =>  error)
    .then( (data) => data );
}

const deleteProductoDelInventario = async (idProducto) => {
    return await fetch(`${URL_BASE}/deleteProductoDelInventario/${idProducto}`, {
        method: "DELETE", // or 'PUT'
        // body: JSON.stringify(mark), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then((response) => response.json())
    .catch((error) =>  error)
    .then((data) => data);
}
