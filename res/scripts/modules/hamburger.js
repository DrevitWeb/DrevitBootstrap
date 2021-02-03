/*Hamburger*/

$(document).ready(function() {
    $('.hamburger').click(function(e){
        e.preventDefault();
        var $this = $(this);
        if($this.hasClass('is-opened'))
        {
            $this.addClass('is-closed').removeClass('is-opened');
        }
        else
        {
            $this.addClass('is-opened').removeClass('is-closed');
        }
    });
});