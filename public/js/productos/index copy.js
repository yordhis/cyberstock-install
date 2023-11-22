
// Elementos categorias
let selectCategories = document.getElementById('selectCategories'),
tbodyCategories = document.getElementById('tbodyCategories'),
addCategorie = document.getElementById('addCategorie'),
addCategorie1 = document.getElementById('addCategorie1'),

// Elementos de Marcas
selectMarks = document.getElementById('selectMarks'),
bodyTableMarks = document.getElementById('bodyTableMarks'),
addMark = document.getElementById('addMark'),

// Elementos Productos
tbodyProducts = document.getElementById('tbodyProducts'),
paginationProducts = document.getElementById('paginationProducts'),

// Elementos DOM
temporaryMessageCheckbox = document.getElementById('C1'),
btn_successful2 = document.getElementById('btn_successful2'),
btn_successful = document.getElementById('btn_successful'),
btn_register = document.getElementById('btn_register'),
btn_ventas = document.getElementById('btn_ventas'),
buttomSubmitProduct = document.getElementById('buttomSubmitProduct'),
temporaryMessage = document.getElementById('temporaryMessage');



/** Obtener categorias y setear en el select */
const setProductos = async (element, url = `${URL_BASE}/getProducts`) => {
    await fetch(url)
    .then(response => response.json())
    .then((products) => {
       console.log(products);
       element.innerHTML = "";
    //    paginationProducts.innerHTML = "";
        switch (element.localName) {
                case "select":
                    element.innerHTML = ` <option selected disabled>Seleccione producto</option>`;
                    products.forEach(product => {
                        element.innerHTML += `
                            <option value="${product.id}">${product.name}</option>
                        `;
                    });
                    break;
                case "tbody":
                    contador = 1;
                    if (!products.data.data.length) {
                        element.innerHTML += `
                        <tr>
                            <td colspan="6">${products.message}</td>
                        </tr>
                        `;
                    } else {
                        products.data.data.forEach(product => {
                            element.innerHTML += `
                            <tr>
                            <td>${contador}</td>
                            <td>${product.barcode}</td>
                            <td>${product.description}</td>
                            <td>${product.mark.name}</td>
                            <td>${product.categorie.name}</td>
                                <td>
                                    <a href="${URL_BASE}/productUpdate/${product.id}" class="action" id="actionEdit${product.id}">
                                       <i class='fa fa-pencil'></i>
                                    </a>
    
                                    <a href="${URL_BASE}/productDelete/${product.id}" class="action" id="actionDelete${product.id}">
                                       <i class='fa fa-trash'></i>
                                    </a>
                                </td>
                            </tr>
                            `;
                            contador++;
                        });
                            // Insertamos botones de paginación
                            siguientePage = products.data.next_page_url != null ? products.data.current_page +1 : 0; 
                            paginationProducts.innerHTML = `
                            <button>
                                <a href="${products.data.prev_page_url}" style="" class="ant" id="prevPageProduct"  >Anterior << </a>
                                <span>${products.data.current_page -1}</span>
                                <span>${products.data.current_page}</span>
                                <span>${siguientePage}</span>
                                <a href="${products.data.next_page_url}" style="text-decoration:none;"  class="next" id="nextPageProduct" > >> Siguiente</a>
                            </button>
                            `;   
                    }
                break;
            
                default:
                    element.innerHTML = `<span> No hay datos</span>`
                    break;
        }

    
            
    })
    .catch(error => {
        console.log(`Error al consultar los productos. Mensaje de error: ${error}`);
    });
};

/** Obtener categorias y setear en el select */
const setCategorias = async (element) => {
    await fetch(`${URL_BASE}/categories`)
    .then(response => response.json())
    .then((categories) => {
            switch (element.localName) {
                case "select":
                    element.innerHTML = ` <option selected disabled>Seleccione categoria</option>`;
                    categories.data.forEach(categorie => {
                        element.innerHTML += `
                            <option value="${categorie.id}">${categorie.name}</option>
                        `;
                    });
                    break;
                case "tbody":
                    contador = 1;
                    categories.data.forEach(categorie => {
                        element.innerHTML += `
                        <tr>
                            <td>${contador}</td>
                            <td>${categorie.name}</td>
                            <td>
                                <a href="${URL_BASE}/categorieUpdate/${categorie.id}" class="action" id="actionEdit${categorie.id}">
                                <i class='fa fa-pencil'></i>
                                </a>

                                <a href="${URL_BASE}/categorieDelete/${categorie.id}" class="action" id="actionDelete${categorie.id}">
                                <i class='fa fa-trash'></i>
                                </a>
                            </td>
                        </tr>
                        `;
                        contador++;
                    });
                break;
            
                default:
                    element.innerHTML = `<span> No hay datos</span>`
                    break;
            }
            
    })
    .catch(error => {
        console.log(`Error al consultar las categorias. Mensaje de error: ${error}`);
    });
};


/** Obtener Marcas y setear en el select */
const setMarks = async (element) => {
    await fetch(`${URL_BASE}/marks`)
    .then(response => response.json())
    .then((marks) => {
            switch (element.localName) {
                case "select":
                    element.innerHTML = ` <option selected disabled>Seleccione Marca</option>`;
                    marks.forEach(mark => {
                        element.innerHTML += `
                            <option value="${mark.id}">${mark.name}</option>
                        `;
                    });
                    break;
                case "tbody":
                    contador = 1;
                    marks.forEach(mark => {
                        element.innerHTML += `
                        <tr>
                            <td>${contador}</td>
                            <td>${mark.name}</td>
                            <td>
                                <a href="${URL_BASE}/markUpdate/${mark.id}" class="action" id="actionEdit${mark.id}">
                                <i class='fa fa-pencil'></i>
                                </a>

                                <a href="${URL_BASE}/markDelete/${mark.id}" class="action" id="actionDelete${mark.id}">
                                <i class='fa fa-trash'></i>
                                </a>
                            </td>
                        </tr>
                        `;
                        contador++;
                    });
                break;
            
                default:
                    element.innerHTML = `<span> No hay datos</span>`
                    break;
            }
    })
    .catch(error => {
        console.log(`Error al consultar las Marcas. Mensaje de error: ${error}`);
    });
};



/** Funcion que retorna un span con el mensaje temporal */
const temporaryMessages = (message)=>{
    return `
        <span class="mark-like">✓✓</span>
        <h3>${message}</h3>
    `;
}

/** Ejecución de las funciones */
setCategorias(selectCategories);
setCategorias(tbodyCategories);
setMarks(selectMarks);
setMarks(bodyTableMarks);
setProductos(tbodyProducts);


/** Eventos */
// Añadir categoria
addCategorie.addEventListener('keyup', (e)=>{
    if (e.key == "Enter") {
        console.log('procesando registro de categoria');
        // Creamos la categoria (Se debe validar)
        storeCategoria(e.target.value)
    
        // Escoden el formulario
        btn_successful2.checked = false;
 
        // Insertamos el mensaje
        temporaryMessage.innerHTML = temporaryMessages('La categoria se creo correctamente');
 
        // Activamos el mensaje temporal
        temporaryMessageCheckbox.checked=true;

        setTimeout(()=>{
            e.target.value = "";
            setCategories(selectCategories);
            setTimeout(()=>{
                temporaryMessageCheckbox.checked=false;
            }, 1500)
        }, 3000) 
    }
});

addCategorie1.addEventListener('keyup', (e)=>{
    if (e.key == "Enter") {
        console.log('procesando registro de categoria');
        // Creamos la categoria (Se debe validar)
        storeCategoria(e.target.value)
    
        // Escoden el formulario
        // btn_successful2.checked = false;
 
        // Insertamos el mensaje
        temporaryMessage.innerHTML = temporaryMessages('La categoria se creo correctamente');
 
        // Activamos el mensaje temporal
        temporaryMessageCheckbox.checked=true;
        console.log(temporaryMessageCheckbox);
        console.log(temporaryMessage);

        setTimeout(()=>{
            e.target.value = "";
            setCategories(tbodyCategories);
            setTimeout(()=>{
                temporaryMessageCheckbox.checked=false;
            }, 1500)
        }, 3000) 
    }
});

// Añadir Marca
addMark.addEventListener('keyup', (e)=>{
    if (e.key == "Enter") {
        console.log('Procesando registro de Marca');
        // Crear Marca
        storeMark(e.target.value);

         // Escoden el formulario
         btn_successful.checked = false;
 
         // Insertamos el mensaje
         temporaryMessage.innerHTML = temporaryMessages('La Marca se creo correctamente');
  
         // Activamos el mensaje temporal
         temporaryMessageCheckbox.checked=true;
       
        setTimeout(()=>{
            e.target.value = "";
            setMarks(selectMarks);
            setTimeout(()=>{
                temporaryMessageCheckbox.checked=false;
            }, 1500)
        }, 3000) 
    }
});

// Crear producto
buttomSubmitProduct.addEventListener('click', (e)=>{
    // btn_register.checked=true;
    let forms = document.forms,
    product = {};
    for (let i = 0; i < forms[0].length - 2; i++) {
        const element = forms[0][i];
        // creamos el objeto producto
        product[element.name] = element.value;
    }
    setTimeout(()=>{
        storeProduct(product);
        setTimeout(() => {
            forms[0].reset();
            btn_ventas.checked=false;
            setProducts(tbodyProducts);
        }, 1500);
    }, 2000);
});

// Editar producto
tbodyProducts.addEventListener('click', (e)=>{
    e.preventDefault();
    console.log(e.target);
    if(e.target.localName == "a" || e.target.localName == "i"){
        if(e.target.localName == "i") {
            urlRequest = e.target.parentNode.href;
            urlPath = e.target.parentNode.pathname;
            idProduct = urlPath.split('/')[3]
        }else{
            urlRequest = e.target.href;
            urlPath = e.target.pathname;
            idProduct = urlPath.split('/')[3]
        }
        // console.log(urlRequest);
        // console.log(urlPath.split('/')[2]);
        switch (urlPath.split('/')[2]) {
            case "productUpdate":
                btn_ventas.checked = true;
                dataProduct =  showProduct(idProduct).then(res=>console.log(res));
                console.log(dataProduct);
                // let forms = document.forms,
                // product = {};
                // for (let i = 0; i < forms[0].length - 2; i++) {
                //     const element = forms[0][i];
                //     // creamos el objeto producto
                //    element.value;
                // }


                break;
            case "productDelete":
                confirmResult = confirm("¿Seguro que desea eliminar el producto?")
                if (confirmResult) {
                    deleteProduct(idProduct);
                    setTimeout(() => {
                        setProducts(tbodyProducts)
                    }, 2000);
                }

                break;
        
            default:
                break;
        }
    }
});

// Paginación
paginationProducts.addEventListener('click', (e)=>{
        e.preventDefault();
        if(e.target.localName == "a"){
            let url = e.target.href;
            if(e.target.pathname != "/null") setProducts(tbodyProducts, url);
        }
});



// fetch(url, {
//   method: "POST", // or 'PUT'
//   body: JSON.stringify(data), // data can be `string` or {object}!
//   headers: {
//     "Content-Type": "application/json",
//   },
// })
//   .then((res) => res.json())
//   .catch((error) => console.error("Error:", error))
//   .then((response) => console.log("Success:", response));
