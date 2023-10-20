const getCliente = (idCliente) => {

    return new Promise(  (resolve, reject)=>{
         
        setTimeout(()=>{
            resolve({
                value: idCliente,
                result:  fetch(`${URL_BASE}/getCliente/${idCliente}`, {
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
};