

/** Crear Marcas */
const storeMark = (mark)=>{
    fetch(`${URL_BASE}/marks`, {
        method: "POST", // or 'PUT'
        body: JSON.stringify(mark), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then((marks) => marks.json())
    .catch((error) => console.error("Error:", error))
    .then((response) => console.log("Success:", response));
}

/** Eliminar Marca */
const deleteMark = (idMark)=>{
    fetch(`${URL_BASE}/marks/${idMark}`, {
        method: "DELETE", // or 'PUT'
        body: JSON.stringify(mark), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then((marks) => marks.json())
    .catch((error) => console.error("Error:", error))
    .then((response) => console.log("Success:", response));
}

/** Actualizar Marca */
const updateMark = (mark)=>{
    fetch(`${URL_BASE}/marks/${mark.id}`, {
        method: "PUT", // or 'PUT'
        body: JSON.stringify(mark), // data can be `string` or {object}!
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then((marks) => marks.json())
    .catch((error) => console.error("Error:", error))
    .then((response) => console.log("Success:", response));
}