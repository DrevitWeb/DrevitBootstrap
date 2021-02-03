$(document).ready(function () {
    $(".slideshow").each(function () {
        let i = 1;
        let first = true;
        let current = 1;
        let nbImages =  $(this).find("img").length;

        let slideshow = $(this)

        $(this).find("img").each(function () {
            let img = $(this);
            let slide = $("<div class='slide slide-"+i+" "+((first)?"active":"")+"'>"+img.prop('outerHTML')+"</div>")
            img.replaceWith(slide);
            i++;
            first = false;
        })

        let counter = setInterval(function () {
            current = nextSlide(slideshow, current, nbImages);
        }, 3000);

        $(this).hasClass("tooltip")
        {
            let rtip = $("<div class='right-tip'>&gt;</div>");
            let ltip = $("<div class='left-tip'>&lt;</div>");
            $(this).append(ltip);
            $(this).append(rtip);

            rtip.click(function () {
                clearInterval(counter);
                current = nextSlide(slideshow, current, nbImages);
                counter = setInterval(function () {
                    current = nextSlide(slideshow, current, nbImages);
                }, 3000);
            })

            ltip.click(function () {
                clearInterval(counter)
                current = previousSlide(slideshow, current, nbImages);
                counter = setInterval(function () {
                    current = nextSlide(slideshow, current, nbImages);
                }, 3000);
            })
        }
    })
})

function nextSlide(slideshow, current, nbImages)
{
    if(slideshow.hasClass("fade"))
    {
        slideshow.find(".slide-"+current).removeClass("active");
        if(current !== nbImages)
        {
            current++;
        }
        else
        {
            current = 1;
        }
        slideshow.find(".slide-"+current).addClass("active");
    }

    if(slideshow.hasClass("sliding-left"))
    {
        slideshow.find(".slide-"+current).removeClass("active");
        if(current !== nbImages)
        {
            current++;
        }
        else
        {
            current = 1;
        }
        slideshow.find(".slide-"+current).addClass("active");
        slideshow.find(".slide-1").css("margin-left", "-"+(current-1)*100+"%")
    }

    if(slideshow.hasClass("sliding-top"))
    {
        slideshow.find(".slide-"+current).removeClass("active");
        if(current !== nbImages)
        {
            current++;
        }
        else
        {
            current = 1;
        }
        slideshow.find(".slide-"+current).addClass("active");
        //slideshow.find(".slide-1").css("margin-top", "-"+(current-1)*56.25 +"%")
    }

    return current;
}

function previousSlide(slideshow, current, nbImages)
{
    if(slideshow.hasClass("fade"))
    {
        slideshow.find(".slide-"+current).removeClass("active");
        if(current !== 1)
        {
            current--;
        }
        else
        {
            current = nbImages;
        }
        slideshow.find(".slide-"+current).addClass("active");
    }

    if(slideshow.hasClass("sliding-left"))
    {
        slideshow.find(".slide-"+current).removeClass("active");
        if(current !== 1)
        {
            current--;
        }
        else
        {
            current = nbImages;
        }
        slideshow.find(".slide-"+current).addClass("active");
        slideshow.find(".slide-1").css("margin-left", "-"+(current-1)*100+"%")
    }

    if(slideshow.hasClass("sliding-top"))
    {
        slideshow.find(".slide-"+current).removeClass("active");
        if(current !== 1)
        {
            current--;
        }
        else
        {
            current = nbImages;
        }
        slideshow.find(".slide-"+current).addClass("active");
        //slideshow.find(".slide-1").css("margin-top", "-"+(current-1)*56.25 +"%")
    }

    return current;
}