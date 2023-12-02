/**
 * 
 * @param {*} url 
 * @param {tipo, rango:{inicio,fin}} config 
 * @param {tipo: "dia, semanal, mensual, personalizada"}
 * @returns 
 */

const storeReportes = async (url, config) => {
    return await fetch(url,{
        method: "POST",
        body: JSON.stringify(config),
        headers: {
            "Content-Type": "application/json",
        },
    })
    .then(res => res.json())
    .catch(err => err)
    .then(data => data);
};