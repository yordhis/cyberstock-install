log('conectado al custon modal');

const hanledOpenModal = (e) => {
    e.preventDefault();
    // log(e.target.parentElement.children[1].children[0].children[2].children[0])
    e.target.parentElement.children[1].classList.add('modal--show')
    if(e.target.parentElement.children[1].children[0].children[2].children[0]){
        e.target.parentElement.children[1].children[0].children[2].children[0].focus();
    }
   
    // element.classList.add('modal--show');
};

const hanledCloseModal = (e) => {
    e.preventDefault();
    // log(e.target.parentElement)
    // e.target.parentElement.children[1].classList.remove('modal--show')
    // log(e.target.parentElement.children[1].classList)
    // element.classList.add('modal--show');
};

const cargarAccionesDelCustomModal = async () => {
    const openModal = d.querySelectorAll('.hero__cta');

    openModal.forEach(element => {
        element.addEventListener('click', hanledOpenModal); 
    });
    
};

const customModal = (data) =>{
    return `
        <!-- Modal -->
        <section class="modal__custom modal--show">
            <div class="modal__container">
                
                <h2 class="modal__title">${data.titulo}</h2>
                <p class="modal__paragraph">${data.descripcion}</p>

                <form action="confirmarAccion" class="${data.idForm}">
                    <div class="form-floating mb-3">
                        <input type="text" name="estatus" value="1">
                        <button class="btn btn-success text-white fs-5" type="submit">Si, comfirmar acción.</button>
                    </div>
                </form>

                <form action="denegarAccion" class="${data.idForm}>
                    <div class="form-floating mb-3">
                        <input type="text" name="estatus" value="0">
                        <button class="btn btn-danger text-white fs-5" type="submit">No, cancelar acción.</button>
                    </div>
                </form>

                <!--<p class="btn btn-none text-danger fs-5 cerrar__ModalCustom" id="cerrarModalCustom"> CERRAR </p>-->
            </div>
        </section>
    `;
};