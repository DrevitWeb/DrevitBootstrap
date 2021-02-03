/*Hamburger*/

$(document).ready(function() {
    $('.btn').click(function(e){
        e.preventDefault();
        var $this = $(this);
        if($this.hasClass('active'))
        {
            $this.removeClass('active');
        }
        else
        {
            $this.addClass('active');
        }
    });
});