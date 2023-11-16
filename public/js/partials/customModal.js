log('conectado al custon modal');

const hanledOpenModal = (e) => {
    e.preventDefault();
    log(e.target.parentElement.children[1].classList)
    e.target.parentElement.children[1].classList.add('modal--show')
    log(e.target.parentElement.children[1].classList)
    // element.classList.add('modal--show');
};

const hanledCloseModal = (e) => {
    e.preventDefault();
    log(e.target.parentElement)
    // e.target.parentElement.children[1].classList.remove('modal--show')
    // log(e.target.parentElement.children[1].classList)
    // element.classList.add('modal--show');
};

const cargarAccionesDelCustomModal = async () => {
    const openModal = d.querySelectorAll('.hero__cta');
    // modal = d.querySelectorAll('.modal__custom');
    // closeModal = d.querySelectorAll('.modal__close');
    // log(modal)


    openModal.forEach(element => {
        element.addEventListener('click', hanledOpenModal); 
    });
    
    // closeModal.forEach(element => {
    //     element.addEventListener('click', hanledCloseModal);
    // });
};