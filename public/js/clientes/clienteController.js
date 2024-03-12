

const getCliente = async (filtro) => {
    return await fetch(`${URL_BASE}/getCliente`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(filtro), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then( (response) =>  response.json() )
        .catch( (err) => err )
        .then( (data) => data );
};

const storeCliente = async (cliente) => {
    return await fetch(`${URL_BASE}/storeCliente`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(cliente), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((response) => response.json())
        .catch((err) => err)
        .then((data) => data);
};

const updateCliente = async (url, cliente) => {
    return await fetch(`${url}`, {
        method: "PUT", // or 'PUT'
        body: JSON.stringify(cliente), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
        .then((response) => response.json())
        .catch((err) => err)
        .then((data) => data);
};
