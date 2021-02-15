$(document).ready(function(){
    $('#menu-mobile ul li a i').click(function(e){
        if($(e.target.closest('li')).hasClass("expanded"))
        {

            $(e.target.closest('li')).removeClass("expanded");
        }
        else
        {
            $('#menu-mobile ul li').removeClass("expanded");
            $(e.target.closest('li')).addClass("expanded");
        }
    })
})


/*Menus*/

$(document).ready(function(){
    $(".expand").click(function(){
        if(!$(this).parent().hasClass("expanded"))
        {
            $('.expanded').each(function(){
                $(this).removeClass("expanded");
            })
            $(this).parent().addClass("expanded");
        }
        else
        {
            $(this).parent().removeClass("expanded");
        }
    });
});


/*Boutons*/

$(".btn").click(function(e){
    if(!$(e.target).hasClass("link"))
    {
        e.preventDefault();
    }
    if($(e.target).hasClass("active"))
    {
        $(e.target).removeClass("active");
    }
    else
    {
        $(e.target).addClass("active");
    }
})

/*Cropbox*/

    $(window).load(function() {
        var options =
            {
                thumbBox: '.thumbBox',
                spinner: '.spinner',
                imgSrc: 'avatar.png'
            }
        var cropper = $('.imageBox').cropbox(options);
        $('#file').on('change', function(){
            var reader = new FileReader();
            reader.onload = function(e) {
                options.imgSrc = e.target.result;
                cropper = $('.imageBox').cropbox(options);
            }
            reader.readAsDataURL(this.files[0]);
            this.files = [];
        })
        $('#btnCrop').on('click', function(){
            var img = cropper.getDataURL();
            $('.cropped').append('<img src="'+img+'" alt="cropped">');
            $("input#avatar").val(img);
        })
        $('#btnZoomIn').on('click', function(){
            cropper.zoomIn();
        })
        $('#btnZoomOut').on('click', function(){
            cropper.zoomOut();
        })
    });
