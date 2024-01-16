function preload() {
    var preloadFalse = document.querySelectorAll(".preloadFalse");
    var preloadTrue = document.querySelectorAll(".preloadTrue");
        if (preloadFalse.length > 0) {
          setTimeout(() => {
                preloadFalse[0].classList.remove('d-none');
                preloadFalse[0].classList.remove('preloadFalse');
                preloadTrue[0].remove();
            }, 1500);
        }
}


preload();

