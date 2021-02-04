$(document).ready(function () {
    let opened = $(".hexagon.opened");
    opened.css("z-index", 10000);
    setTimeout(function () {
        opened.css("z-index", 0);
    }, 1500)
    $(".hexagon.move").addClass("animate")
    $(".hexagon.move").removeClass("opened");
    $(".hexagon.bordered").each(function () {
        let hc = $(this).html();
        let content = $("<div class='border'></div>");
        content.html(hc);
        $(this).html("");
        $(this).append(content);
    })


    $(".left").each(function(){
        let i = 1;
        $(this).find(".hexa-row + .hexa-row").each(function () {
            $(this).css("transform", "translateY(-"+i*15+"%)"+((i%2 === 1)?" translateX(5vw)":""))
            i++;
        })
    });

    $(".right").each(function(){
        let j = 1;
        $(this).find(".hexa-row + .hexa-row").each(function () {
            $(this).css("transform", "translateY(-"+j*15+"%)"+((j%2 === 1)?" translateX(-5vw)":""))
            j++;
        })
    });
    /*$("body").on("click", ".hexagon.animate", function (e) {*/
    $(".hexagon.animate").click( function (e) {
        if(!$(this).hasClass("opened")) {
            console.log(this)
            $(".hexagon.move").addClass("animate");
            $(".hexagon.move").removeClass("opened");
            $(".hexagon.move").css("transform", "");
            $(this).removeClass("animate");
            e.preventDefault();
            let w = window.innerWidth;
            let h = window.innerHeight;
            let x = offset(e.target).left;
            let y = offset(e.target).top;
            let ew = e.target.offsetWidth;
            let eh = e.target.offsetHeight;

            let nx = w / 2 - x - ew / 2;
            let ny = h / 2 - y - eh / 2;
            $(this).addClass("opened");

            let matrix = new WebKitCSSMatrix(this.style.transform);
            this.style.transform = matrix.translate(nx, ny).scale(5, 5).rotate(0, 180, 29);
            /*if($(this).hasClass("bordered"))
            {
                let content = $(this).find(".border").children();
                let matrix = new WebKitCSSMatrix(content.css("transform"));
                content.css("transform", matrix.rotate(0,0,-28.5));
            }
            else
            {

                let content = $(this).children();
                let matrix = new WebKitCSSMatrix(content.css("transform"));
                content.css("transform", matrix.rotate(0,0,-28.5));
            }*/
            $(this).css("z-index", 10000);
        }
    });

    $("*").click(function (e) {
        if(!$(e.target).hasClass("hexagon") && !$(e.target).hasClass("border") && !$(e.target).parents('.hexagon').length)
        {
            let opened = $(".hexagon.opened");
            opened.css("z-index", 10000);
            setTimeout(function () {
                opened.css("z-index", 0);
            }, 1500)
            $(".hexagon.move").removeClass("opened");
            $(".hexagon.move").addClass("animate");
            $(".hexagon.move").css("transform", "");
        }
    })

    let hexas = [];

    $(".hexa_free").find(".hexagon").each(function () {
        let left = randomIntFromInterval(0, 16);
        let size = randomIntFromInterval(5,12);
        $(this).css("left", left*2.5+"%");
        $(this).css("height", size+'vw');
        $(this).css("width", size+'vw');

        let hexa = {
            hexa: $(this),
            left: $(this).position().left,
            top: $(this).position().top,
            radius : $(this).width()/2*1.01
        }
        hexas.push(hexa);
    });

    let i = 0;

    while(isCollision(hexas))
    {
        i++;
        hexas.forEach(function (hexa1, i1) {
            hexas.forEach(function(hex, i2){
                if(circle_collision(hexa1, hex))
                {
                    let dx = Math.abs(hexa1.left - hex.left);
                    let dr = hexa1.radius + hex.radius;

                    if(hexa1.top >= hex.top)
                    {
                        let dy = hexa1.top - hex.top;

                        let ny = Math.sqrt(Math.abs(dr^2 - dx^2 - dy));
                        hexas[i1].top = hexa1.top + ny + 10;
                    }
                    else
                    {
                        let dy = hex.top - hexa1.top;

                        let ny = Math.sqrt(Math.abs(dr^2 - dx^2 - dy));
                        hexas[i2].top = hex.top + ny + 10;
                    }
                }
            })
        })
    }

    console.log(isCollision(hexas))

    let j = 0;
    hexas.forEach(function (hexa1) {
            hexa1.hexa.css({
                left: hexa1.left+"px",
                top: hexa1.top+"px"
            });
        j++;
    })
});

function isCollision(hexas) {
    let collision = false;
    hexas.forEach(function (h1) {
        hexas.forEach(function (h2) {
            if(circle_collision(h1, h2))
            {
                collision = true;
            }
        })
    })

    return collision;
}