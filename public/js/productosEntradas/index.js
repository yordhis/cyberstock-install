let btnProcesarEntrada = d.querySelector("#btnProcesarEntrada"),
btnAgregarAlCarro = d.querySelector('#agregarAlCarro'),
inputsEntrys = d.querySelectorAll('.input-entrada'),
inputIdentificacion = document.querySelector('#cedula'),
dataProveedor = d.querySelector('#dataProveedor'),
inputBarcode = d.querySelector('#codigo_producto'),
inputQuantity = d.querySelector('#cantidad'),
inputUnitCost = d.querySelector('#costo_unitario'),
inputSubtotal = d.querySelector('#subtotal'),
tbodyEntryProduct = d.querySelector('#carritoEntrada'),
productos = [],
producto = null;

log(inputIdentificacion)
const addRow = (products) => {
      products.forEach((product, key) =>{
            tbodyEntryProduct.innerHTML += `
            <tr>
                <td>
                    ${product.codigo_producto}
                </td>
                <td>
                    ${product.descripcion}
                </td>
                <td>
                    ${product.cantidad}
                </td>
                <td>
                    ${product.costo_unitario} 
                </td>
                <td>
                    ${product.subtotal} 
                </td>
                <td>
                    <bottom id="${key}">
                        <i class="fa fa-trash"></i> 
                    </bottom>
                </td>
            </tr>
            `;
     })    
};

const handleInputBarcode = async (e) => {
    // e.preventDefault();
    log(e.target.value)
    if (e.target.value.length > 3) {
        resultado = await getProductoData(e.target.value)
     
        producto = await resultado.result;
        log(producto);
        inputsEntrys.forEach(input => {
            if(input.id == "descripcion") input.value = producto.data.descripcion;
        });
        
     
        // return inputBarcode.addEventListener('keypress', handleEnterKey);
    }
};

const handleEnterKeyTwo = (e)=>{
    e.prevendefault()
    log(e.target.value)
    let entryDataProduct = null;
    if (e.key == "Enter") {
        entryDataProduct = inputsEntrys.forEach(input => {
            if(input.id == "codigo_producto") return input.value;
            if(input.id == "descripcion") return input.value;
            if(input.id == "cantidad") return input.value;
            if(input.id == "costo_unitario") return input.value;
            if(input.id == "subtotal") return input.value;
        });
        log(entryDataProduct)
        // products.push(entryDataProduct);
        // addRow(products)
    }
};

const handleSubtotal = (e) => {
    let quantity = parseFloat(e.target.value),
    unitCost = parseFloat(inputUnitCost.value);
    if (quantity && unitCost) {
        inputSubtotal.value = quantity * unitCost
    }else{
        log("Los datos ingresados no son números!")
    }
};

const handleClicSubmit = (e) => {
    console.log(e.target.localName);
    console.log(e.target.value);
    console.log('hizo submit')
};

const hanledClicAgregarAlCarrito = (e) =>{
   
    console.log(e.target.localName);
    console.log(e.target.value);
    log('hizo submit')
};

const hanledIdentificacionProveedor = async (e) =>{
    log("keyup")
    if(e.target.value.length > 5){
        await getProveedor(e.target.value, dataProveedor)
    }
};

inputIdentificacion.addEventListener('keyup', hanledIdentificacionProveedor);

inputBarcode.addEventListener('keyup', handleInputBarcode);

tbodyEntryProduct.addEventListener('keyup', handleEnterKeyTwo);

inputQuantity.addEventListener('change', handleSubtotal);

btnProcesarEntrada.addEventListener('clic', handleClicSubmit);

btnAgregarAlCarro.addEventListener('clic', hanledClicAgregarAlCarrito);



