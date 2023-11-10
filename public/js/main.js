console.log("conectado al master");
const URL_BASE = "http://cyberstock.com/api",
URL_BASE_APP = "http://cyberstock.com",
d = document,
log = console.log,
VENTA = {
    id:1,
    name: "VENTA"
},
spinner = `
    <div class="spinner-border text-success" role="status">
    <span class="visually-hidden">Loading...</span>
    </div>
`;

const repuesta = (mensaje, estatus) => {

    switch (estatus) {
        case 200:
            estatusText = "alert-success"
            break;
        case 404:
            estatusText = "alert-danger"
            break;
        case 401:
            estatusText = "alert-warning"
            break;
        case 500:
            estatusText = "alert-primary"
            break;
        default:
            estatusText = "alert-primary"
            break;
    }


    return `
        <div class="alert ${estatusText} d-flex align-items-center" role="alert">
            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:">
                <use xlink:href="#check-circle-fill"/></svg>
            <div>
                ${mensaje}
            </div>
        </div>
    `;
};