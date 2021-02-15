/*Panels*/

$(document).ready(function(){
    let panel = $(".slide_panel");
    panel.css("position", "absolute");
    panel.addClass("closed");
    panel.each(function () {
        let panel = $(this);
        let button = $("<div class='panel_button'><div class=\"hamburger hamburger-draw\">\n" +
            "        <svg x=\"0\" y=\"0\" width=\"54px\" height=\"54px\" viewBox=\"0 0 54 54\">\n" +
            "            <circle r=\"25\" cx=\"27\" cy=\"27\"></circle>\n" +
            "        </svg>\n" +
            "        <span class=\"bar\"> </span>\n" +
            "    </div></div>");

        button.click(function(){
            if(!panel.hasClass("closed"))
            {
                panel.addClass("closed");
                panel.css({"position" : "absolute"});
            }
            else
            {
                panel.removeClass("closed");
                panel.css({"position" : "fixed"});
            }
        });

        panel.append(button);

    })
});