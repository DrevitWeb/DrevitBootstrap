function offset(el) {
    let rect = el.getBoundingClientRect(),
        scrollLeft = window.pageXOffset || document.documentElement.scrollLeft,
        scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    return { top: rect.top + scrollTop, left: rect.left + scrollLeft }
}

function elementInView(elem){
    return ($(window).height() + $(window).scrollTop()) > $(elem).offset().top;
}

function randomIntFromInterval(min, max) { // min and max included
    return Math.floor(Math.random() * (max - min + 1) + min);
}

function geoDist(min, max, prob) {
    let q = 0;
    let p = Math.pow(prob, 1 / (max - min));
    while (true) {
        q = Math.ceil(Math.log(1-Math.random()) / Math.log(p)) + (min - 1);
        if (q <= max) {
            return q;
        }
    }
}