const animatedElements = document.querySelectorAll('.element-to-fadeIn');

function checkIfInView() {
    const windowHeight = window.innerHeight;
    const windowTopPosition = window.scrollY;
    const windowBottomPosition = (windowTopPosition + windowHeight);

    animatedElements.forEach(el => {
        const elementHeight = el.offsetHeight;
        const elementTopPosition = el.offsetTop;
        const elementBottomPosition = (elementTopPosition + elementHeight);

        // check if this current element is within viewport
        if ((elementBottomPosition >= windowTopPosition) &&
            (elementTopPosition <= windowBottomPosition)) {
            el.classList.add('animate__fadeIn');
        }
    });
}

window.addEventListener('scroll', checkIfInView);