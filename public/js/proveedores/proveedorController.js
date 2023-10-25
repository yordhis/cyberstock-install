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
                log(data)
                if(data.estatus == 200){
                  if (data.data.length) {
                    elemento.innerHTML = `<label for="validationCustom04" class="form-label">Datos del proveedor</label> <br>
                    <h4>${data.data[0].empresa} | ${data.data[0].contacto}</h4>`;
                  }else{
                    elemento.innerHTML = ` <span class="text-danger"> No hay registro de este proveedor </span>`;
                  }
                }else if(data.estatus == 500){
                  log(data)
                  elemento.innerHTML = ` <span class="text-danger"> Error al conectarse al servidor </span>`;
                }
            })
       
};