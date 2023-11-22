
// Registrar producto en la DB
const storeProducto = async (product) => {
    await fetch(`${URL_BASE}/productos`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(product), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then((res) => res.json())
    .catch((error) => console.error("Error:", error))
    .then((response) => {
        console.log("Success:", response)
    });
}

const deleteProducto = async (idProduct) => {
    return await fetch(`${URL_BASE}/productos/${idProduct}`, {
        method: "DELETE", // or 'PUT'
        body: JSON.stringify(idProduct), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then((res) => res.json() )
    .catch((error) => error )
    .then((response) => response );
}

const updateProducto = async (url, data) => {
    return await fetch(url, {
        method: "PUT",
        body: JSON.stringify(data),
        headers: {
            "Content-Type": "application/json"
        }
    })
    .then(res => res.json())
    .catch(err => err)
    .then(response => response);
};

const getProductosFiltro = async ( url, filtro ) => {
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

const getProductos = async (url) => {

    return  await    fetch(url, {
                        method: "GET", // or 'PUT'
                        // body: JSON.stringify(idProduct), // data can be `string` or {object}!
                        headers: {
                        "Content-Type": "application/json",
                        },
                    })
                    .then(response => response.json())
                    .catch(err => log(err))
                    .then(data => data)    
}
     


const getProducto = (barcode) => {

    return new Promise(  (resolve, reject)=>{
         
        setTimeout(()=>{
            resolve({
                value: barcode,
                result:  fetch(`${URL_BASE}/getProducto/${barcode}`, {
                    method: "GET", // or 'PUT'
                    // body: JSON.stringify(idProduct), // data can be `string` or {object}!
                    headers: {
                      "Content-Type": "application/json",
                    },
                })
                .then(response => response.json())
                .catch(err => log(err))
                .then(data => data)
        
                })
            });
        }, 1500);

    // .then((res) => res.json())
    // .catch((error) => console.error("Error:", error))
    // .then((response) =>  response);
}

const showProducto = async (idProduct) => {
    await fetch(`${URL_BASE}/productos/${idProduct}`, {
        method: "GET", // or 'PUT'
        // body: JSON.stringify(idProduct), // data can be `string` or {object}!
        headers: {
          "Content-Type": "application/json",
        },
    })
    .then((res) => res.json())
    .catch((error) => console.error("Error:", error))
    .then((response) =>  response);
}

