/** IMRPIMIR ELEMTO CON FORMATO DE FORMULA LIBRE */
const imprimirElementoFormulaLibre = (elemento) => {
    var ventana = window.open('', 'PRINT', 'height=400,width=600');
    ventana.document.write('<html><head><title>Factura</title>');
    // ventana.document.write(`<base href="http://localhost/generador-de-barcode/" target="objetivo">`);
    ventana.document.write(`<style>
            * {
                margin: 0px;
                font-size: 8px;
                font-family: 'Times New Roman';
            }

            body{
                margin: 0px;
                padding: 0px;
              
            }

            img{
                width: 150px;
                height: 50px;
                margin: 3px;
            }

    </style>`);
    ventana.document.write('</head><body >');
    ventana.document.write(elemento);
    ventana.document.write('</body></html>');
    ventana.document.close();
    ventana.focus();
    ventana.print();
    ventana.close();
    return true;
};

/** FORMULA LIBRE - NOTA DE ENTREGA */
const hojaDeCodigos = (barcode) => {
    let list_img_barcode = ``;

    for (let index = 0; index < 100; index++) {
        list_img_barcode += `
            <img alt="Barcode Generator TEC-IT & Cyber Staff, C.A."
            src="https://barcode.tec-it.com/barcode.ashx?data=${barcode}&code=Code128"
            id="generadorDeCodigoDeBarra"
            >
         `;

    }

    return `
        ${list_img_barcode}
    `;
};

/** Manejador del evento imprimir codigos de barra */
const hanledBarcode = (e) => {
    console.log("generando codigo de barra");
    imprimirElementoFormulaLibre(hojaDeCodigos(e.target.id));
};



async function cargarEventosDelBotonImprimirBarcode() {
    let botonesDeImprimirBarcode = document.querySelectorAll(".button__print__barcode");

    console.log(botonesDeImprimirBarcode);

    botonesDeImprimirBarcode.forEach(boton => {
        console.log(boton);
        boton.addEventListener('click', hanledBarcode);
    });
}