
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
    await fetch(`${URL_BASE}/productos/${idProduct}`, {
        method: "DELETE", // or 'PUT'
        body: JSON.stringify(idProduct), // data can be `string` or {object}!
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

const getProductoData = (barcode) => {

    return new Promise(  (resolve, reject)=>{
         
        setTimeout(()=>{
            resolve({
                value: barcode,
                result:  fetch(`${URL_BASE}/getProductoData/${barcode}`, {
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

