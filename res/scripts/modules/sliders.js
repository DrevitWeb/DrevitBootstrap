/*Fonction générale du Slider à 4 choix*/
$('.slider4 .slider-value').each(function()
{
    var cls = $(this).attr('class'); //Nom de la classe
    $(this).parent().append('<div class="slider-widget"></div>'); //On ajoute l'élément slider
    $(this).parent().append('<div class="ui-slider-bottom"></div>'); //On ajoute le bas du slider
    var elem = $(this).parent(); //On stocke l'élément contenant le slider
    var options = {max: 3, orientation: "vertical", range: "min"}; //Options du slider
    elem.find('.slider-widget').slider(options); //Création du slider avec les options
});

/*Fonction générale du Slider à 3 choix*/
$('.slider3 .slider-value').each(function()
{
    var cls = $(this).attr('class'); //Nom de la classe
    $(this).parent().append('<div class="slider-widget"></div>'); //On créé l'élément Slider
    $(this).parent().append('<div class="ui-slider-bottom"></div>'); //On ajoute le bas du slider
    var elem = $(this).parent(); //Element contenant le Slider
    var options = {max: 2, orientation: "vertical", range: "min"}; //Options du Slider
    elem.find('.slider-widget').slider(options); //On créé le Slider avec ses options
});

/*Fonction générale du Slider Gamme à 4 choix*/
$('.range5.slider_range .slider-value').each(function()
{
    var cls = $(this).attr('class'); //Nom de la classe
    $(this).parent().append('<div class="slider-widget"></div>'); //On ajoute l'élément slider
    var elem = $(this).parent(); //On stocke l'élément contenant le slider
    var options = {max: 4, min: 0, values: [1, 3] , orientation: "vertical", range: true}; //Options du slider
    elem.find('.slider-widget').slider(options); //Création du slider avec les options
});

$('.slider4.classic').each(function()
{
    var custom = ".classic"; //Variable a définir pour un slider sur une page (ne pas oublier le .)
    var elem = $(this); //Element contenant le Slider
    elem.find('.slider-widget').slider(
    {
        /*Evenement changement du slider : on initialise la légende selon le choix actif*/
        change: function(event, ui)
        {
            updateFourSlider(elem, custom);
        },

        slide: function(event, ui)
        {
            updateFourSlider(elem, custom);
        }
    });
});

$('.slider3.classic').each(function()
{
    var custom = ".classic"; //Variable a définir pour un slider sur une page (ne pas oublier le .)
    var elem = $(this); //Element contenant le Slider
    elem.find('.slider-widget').slider(
    {
        /*Evenement changement du slider : on initialise la légende selon le choix actif*/
        change: function(event, ui)
        {
            updateThreeSlider(elem, custom);
        },

        slide: function(event, ui)
        {
            updateThreeSlider(elem, custom);
        }
    });
});

$('.slider4.arrow').each(function()
{
    var custom = ".arrow"; //Variable a définir pour un slider sur une page (ne pas oublier le .)
    var elem = $(this); //Element contenant le Slider
    elem.find('.slider-widget').slider(
        {
            /*Evenement changement du slider : on initialise la légende selon le choix actif*/
            change: function(event, ui)
            {
                updateFourSlider(elem, custom);
            },

            slide: function(event, ui)
            {
                updateFourSlider(elem, custom);
            }
        });
});

$('.slider3.arrow').each(function()
{
    var custom = ".arrow"; //Variable a définir pour un slider sur une page (ne pas oublier le .)
    var elem = $(this); //Element contenant le Slider
    elem.find('.slider-widget').slider(
        {
            /*Evenement changement du slider : on initialise la légende selon le choix actif*/
            change: function(event, ui)
            {
                updateThreeSlider(elem, custom);
            },

            slide: function(event, ui)
            {
                updateThreeSlider(elem, custom);
            }
        });
});

$('.ui-slider-bottom').click(function()
{
    var val = $(this).parent().find(".ui-slider").slider("value");
    if(val != 0)
    {
        $(this).parent().find(".ui-slider").slider("value", val-1);
    }
});

$('.slider4 .ui-slider-top').click(function()
{
    var val = $(this).parent().find(".ui-slider").slider("value");
    if(val != 3)
    {
        $(this).parent().find(".ui-slider").slider("value", val+1);
    }
});

$('.slider3 .ui-slider-top').click(function()
{
    var val = $(this).parent().find(".ui-slider").slider("value");
    if(val != 2)
    {
        $(this).parent().find(".ui-slider").slider("value", val+1);
    }
});

$('.range5 .ui-slider-top').click(function()
{
    $(this).parent().find(".ui-slider").slider("values", [$(this).parent().find(".ui-slider").slider("values", 0), 4]);
});

$('.slider4 ul li').click(function()
{
    if($(this).hasClass("feature1"))
    {
        $($($(this).parent()).parent()).find(".ui-slider").slider("value", 3);
    }
    if($(this).hasClass("feature2"))
    {
        $($($(this).parent()).parent()).find(".ui-slider").slider("value", 2);
    }
    if($(this).hasClass("feature3"))
    {
        $($($(this).parent()).parent()).find(".ui-slider").slider("value", 1);
    }
    if($(this).hasClass("feature4"))
    {
        $($($(this).parent()).parent()).find(".ui-slider").slider("value", 0);
    }
})

$('.slider3 ul li').click(function()
{
    if($(this).hasClass("feature1"))
    {
        $($($(this).parent()).parent()).find(".ui-slider").slider("value", 2);
    }
    if($(this).hasClass("feature2"))
    {
        $($($(this).parent()).parent()).find(".ui-slider").slider("value", 1);
    }
    if($(this).hasClass("feature3"))
    {
        $($($(this).parent()).parent()).find(".ui-slider").slider("value", 0);
    }
})

$().ready(function()
{
    window.setTimeout(function()
    {
        $('.ui-slider-top').parent().find('.ui-slider').slider("value", 2);
    }, 100);
});

function updateFourSlider(slider, className)
{
    window.setTimeout(function()
    {
        var sel = slider.find('.slider-widget').slider("value"); //Valeur du slider
        var input = slider.find(".slider-value");
        if (sel == 3)
        {
            $(className + ".slider.vertical.slider4 .feature1").addClass("active")
            $(className + ".slider.vertical.slider4 .feature2").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature3").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature4").removeClass("active")
            input.val($(className + ".slider.vertical.slider4 .feature1").attr("value"));
        }
        if (sel == 2)
        {
            $(className + ".slider.vertical.slider4 .feature1").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature2").addClass("active")
            $(className + ".slider.vertical.slider4 .feature3").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature4").removeClass("active")
            input.val($(className + ".slider.vertical.slider4 .feature2").attr("value"));
        }
        if (sel == 1)
        {
            $(className + ".slider.vertical.slider4 .feature1").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature2").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature3").addClass("active")
            $(className + ".slider.vertical.slider4 .feature4").removeClass("active")
            input.val($(className + ".slider.vertical.slider4 .feature3").attr("value"));
        }
        if (sel == 0)
        {
            $(className + ".slider.vertical.slider4 .feature1").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature2").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature3").removeClass("active")
            $(className + ".slider.vertical.slider4 .feature4").addClass("active")
            input.val($(className + ".slider.vertical.slider4 .feature4").attr("value"));
        }
    }, 300);
}

function updateThreeSlider(slider, className)
{
    window.setTimeout(function()
    {
        var sel = slider.find('.slider-widget').slider("value"); //Valeur du slider
        var input = slider.find(".slider-value");

        if (sel == 2)
        {
            $(className + ".slider.vertical.slider3 .feature1").addClass("active")
            $(className + ".slider.vertical.slider3 .feature2").removeClass("active")
            $(className + ".slider.vertical.slider3 .feature3").removeClass("active")
            input.val($(className + ".slider.vertical.slider3 .feature1").attr("value"));
        }
        if (sel == 1)
        {
            $(className + ".slider.vertical.slider3 .feature1").removeClass("active")
            $(className + ".slider.vertical.slider3 .feature2").addClass("active")
            $(className + ".slider.vertical.slider3 .feature3").removeClass("active")
            input.val($(className + ".slider.vertical.slider3 .feature2").attr("value"));
        }
        if (sel == 0)
        {
            $(className + ".slider.vertical.slider3 .feature1").removeClass("active")
            $(className + ".slider.vertical.slider3 .feature2").removeClass("active")
            $(className + ".slider.vertical.slider3 .feature3").addClass("active")
            input.val($(className + ".slider.vertical.slider3 .feature3").attr("value"));
        }
    }, 300);
}