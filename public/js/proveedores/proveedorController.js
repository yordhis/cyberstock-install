const getProveedor = async (idProveedor, elemento) => {
          await fetch(`${URL_BASE}/getProveedor/${idProveedor}`, {
            method: "GET", // or 'PUT'
            // body: JSON.stringify(idProduct), // data can be `string` or {object}!
            headers: {
                      "Content-Type": "application/json",
                    },
            })
            .then(response => response.json())
            .catch(err => log(err))
            .then(data => {
                log(data.data)
                if(data.data){
                    elemento.innerHTML = `<label for="validationCustom04" class="form-label">Datos del proveedor</label> <br>
                    <h4>${data.data.empresa} | ${data.data.contacto}</h4>`;
                }
            })
       
};