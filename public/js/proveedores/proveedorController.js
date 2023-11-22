const getProveedor = async (idProveedor, elemento) => {
     return await fetch(`${URL_BASE}/getProveedor/${idProveedor}`, {
            method: "GET", // or 'PUT'
            // body: JSON.stringify(idProduct), // data can be `string` or {object}!
            headers: {
                      "Content-Type": "application/json",
                    },
            })
            .then(response => response.json())
            .catch(err => err)
            .then(data => data)
       
};

const storeProveedor = async (proveedor) => {
  return await fetch(`${URL_BASE}/storeProveedor`, {
      method: "POST", // or 'PUT'
      body: JSON.stringify(proveedor), // data can be `string` or {object}!
      headers: {
          "Content-Type": "application/json",
      },
  })
      .then((response) => response.json())
      .catch((err) => err)
      .then((data) => data);
};

const updateProveedor = async (url, proveedor) => {
  return await fetch(`${url}`, {
      method: "PUT", // or 'PUT'
      body: JSON.stringify(proveedor), // data can be `string` or {object}!
      headers: {
          "Content-Type": "application/json",
      },
  })
      .then((response) => response.json())
      .catch((err) => err)
      .then((data) => data);
};

