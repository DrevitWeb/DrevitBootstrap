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

function circle_collision(h1, h2) {
    let dx = h1.left + h1.radius - h2.left - h2.radius;
    let dy = h1.top + h1.radius - h2.top - h2.radius;
    let distance = Math.sqrt(dx * dx + dy * dy);

    return distance < h1.radius + h2.radius && h1 !== h2;

}