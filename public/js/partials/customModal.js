log('conectado al custon modal');

const hanledOpenModal = (e) => {
    e.preventDefault();
    log(e.target.parentElement.children[1].children[0].children[2].children[0])
    e.target.parentElement.children[1].children[0].children[2].children[0].focus();
    e.target.parentElement.children[1].classList.add('modal--show')
   
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

    openModal.forEach(element => {
        element.addEventListener('click', hanledOpenModal); 
    });
    
};