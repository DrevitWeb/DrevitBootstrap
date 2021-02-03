$(document).ready(function () {
    $(".progress_bar").each(function () {
        let progression = $(this).attr("progression");
        let bar = $("<div class='progression'></div>");

        if($(this).hasClass("animated") && !$(this).hasClass("m_invisible"))
        {
            bar.animate({"width": progression+"%"}, 500, "easeOutQuad")
        }
        else if(!$(this).hasClass("m_invisible"))
        {
            bar.css("width", progression+"%");
        }

        bar.css("height", "100%");

        if(($(this).hasClass("value_center") || $(this).hasClass("value_end") || $(this).hasClass("value_start")))
        {
            bar.html("<span>"+progression + "%</span>");
        }
        if($(this).hasClass("cursor"))
        {
            let cursor = $("<div class='cursor_bar'></div>");
            bar.append(cursor);
        }

        $(this).append(bar)
    })
})