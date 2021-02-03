$(window).scroll(function(){
        $('.m_invisible').each(function () {

            if (elementInView($(this)) && $(this).hasClass("animated") && $(this).hasClass("progress_bar") && $(this).hasClass("m_invisible")) {
                let progression = $(this).attr("progression");
                console.log(progression)
                $(this).find(".progression").animate({"width": progression + "%"}, 500, "easeOutQuad", function () {
                    $(this).removeClass("m_invisible");
                })
                $(this).addClass("m_visible");
            }

            if (elementInView($(this)) && $(this).hasClass("m_invisible")) {
                $(this).addClass("m_visible");

                $(this).removeClass("m_invisible");
            }
        })
});

$(document).ready(function(){
    $('.m_invisible').each(function () {


        if(elementInView($(this)) && $(this).hasClass("animated")  && $(this).hasClass("progress_bar")  && $(this).hasClass("m_invisible"))
        {
            let progression = $(this).attr("progression");
            $(this).find(".progression").animate({"width": progression+"%"}, 500, "easeOutQuad", function () {
                $(this).removeClass("m_invisible");
            })
            $(this).addClass("m_visible");
        }

        if (elementInView($(this)) && $(this).hasClass("m_invisible"))
        {
            $(this).addClass("m_visible");

            $(this).removeClass("m_invisible");
        }
    })
});