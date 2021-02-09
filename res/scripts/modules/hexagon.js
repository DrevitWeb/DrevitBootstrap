$(document).ready(function () {
    let opened = $(".hexagon.opened");
    opened.css("z-index", 10000);
    setTimeout(function () {
        opened.css("z-index", 0);
    }, 500)
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
            let opened = $(".hexagon.opened");
            opened.addClass("moving");
            opened.css("z-index", 10000);
            setTimeout(function () {
                $(".moving").css("z-index", 0);
                opened.removeClass("moving");
            }, 500)
            $(".hexagon.move").removeClass("opened");
            $(".hexagon.move").addClass("animate");
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
            $(this).css("z-index", 1000000);
        }
    });

    $("*").click(function (e) {
        if(!$(e.target).hasClass("hexagon") && !$(e.target).hasClass("border") && !$(e.target).parents('.hexagon').length)
        {
            let opened = $(".hexagon.opened");
            opened.addClass("moving");
            opened.css("z-index", 10000);
            setTimeout(function () {
                $(".moving").css("z-index", 0);
                opened.removeClass("moving");
            }, 500)
            $(".hexagon.move").removeClass("opened");
            $(".hexagon.move").addClass("animate");
            $(".hexagon.move").css("transform", "");
        }
    })


    $(".hexa_free").find(".hexagon").each(function () {
        let size = randomIntFromInterval(5, 10);
        if($(this).hasClass("move")) size = 10;
        $(this).css("height", size + 'vw');
        $(this).css("width", size + 'vw');
    });

    let placed = false;
    setTimeout(function(e){
        if(!placed) {
            placed = true;
            let hexas = [];
            let k = 0;
            $(".hexa_free").find(".hexagon").each(function () {

                let left = geoDist(1, 100, 0.8);
                console.log("Initial"+(this.offsetLeft + $(this).width()/2)+" "+$(window).width())
                while((left / 100 * $($(this).parent()).width()) >= $(window).width())
                {
                    left = geoDist(1, 100, 0.8);
                    console.log((this.offsetLeft + $(this).width()/2) + " " + (left / 100 * $($(this).parent()).width()))
                }
                if($(this).hasClass("move")) left = randomIntFromInterval(10,90)

                let hexa = {
                    hexa: $(this),
                    k: k,
                    left: left / 100 * $($(this).parent()).width(),
                    top: $(this).position().top,
                    radius: $(this).width() / 2 * 1.01,
                    content: $(this).hasClass("move")
                }
                hexas.push(hexa);
                k++;
            });

            //Ajax collision calc
            calcPosition(hexas);
        }
    }, 500)
});

let calc = 0;

function calcPosition(hexas)
{
    console.log("a")
    calc++;
    ajaxRequest("POST", "class/controllers/modulesController.php?func=calcHexasPosition", function (data) {
        let hexasParsed = JSON.parse(data);
        hexasParsed.forEach(function (hexa, i) {
            hexa.hexa = hexas[hexa.k].hexa;
        })
        placeHexas(hexasParsed);
    }, "windows_height=" + $(window).height() + "&windows_width=" + $(window).width() + "&hexas=" + JSON.stringify(hexas), "", function (a, data) {
        let hexasParsed = JSON.parse(data);
        hexasParsed.forEach(function (hexa, i) {
            hexa.hexa = hexas[hexa.k].hexa;
        })
        console.log(hexasParsed);
        if(calc >= 10){placeHexas(hexasParsed);return;}
        hexas.forEach(function (hexa, i) {
            let left = geoDist(1, $(window).width(), 0.8);
            if(hexa.hexa.hasClass("move")) left = randomIntFromInterval(0.1*$(window).width(),0.9*$(window).width())
            console.log(left)
            hexas[i].left = left;
        })
        calcPosition(hexas);
    })
}

function placeHexas(hexas)
{

    hexas.forEach(function (hexa1) {
        hexa1.hexa.animate({
            left: hexa1.left + "px",
        })
        setTimeout(function () {
            hexa1.hexa.animate({
                left: hexa1.left+"px",
                top: hexa1.top+"px"
            }, 1000, function () {

            });
            if(hexa1.modified)
            {
                //hexa1.hexa.css("background", "#FFFFFF")
            }
            hexa1.hexa.addClass("end");
        }, (hexa1.step !== undefined)?hexa1.step*1:0)
    })
}