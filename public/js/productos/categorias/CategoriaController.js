/** Obtener todas las categorias */
const getCategorias = async (url) => {
    return await fetch(url,{
        method: "GET",
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then( res => res.json() )
    .catch( err => err )
    .then( response => response );
}

/** Crear Categoria */
const storeCategoria = async (categoria)=>{
    // console.log(JSON.stringify(addCategorie.value));
    await fetch(`${URL_BASE}/categories`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(categoria), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then((response) => response.json())
    .catch((error) => console.error("Error:", error))
}

/** Eliminar Categoria */
const deleteCategoria = async (idCategoria)=>{
    // console.log(JSON.stringify(addCategorie.value));
    await fetch(`${URL_BASE}/categories/${idCategoria}`, {
        method: "DELETE", // or 'PUT'
        // body: JSON.stringify(idCategoria), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then((response) => response.json())
    .catch((error) => console.error("Error:", error))
}

/** Actualizar Categoria */
const updateCategoria = async (idCategoria)=>{
    // console.log(JSON.stringify(addCategorie.value));
    await fetch(`${URL_BASE}/categories/${idCategoria}`, {
        method: "PUT", // or 'PUT'
        // body: JSON.stringify(idCategoria), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then((response) => response.json())
    .catch((error) => console.error("Error:", error))
}
