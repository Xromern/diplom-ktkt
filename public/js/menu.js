$(document).ready(function(){
    $(".button-open-menu a").click(function(){
        $(".overlay").fadeToggle(200);
        $(this).toggleClass('btn-open').toggleClass('btn-close');
    });
});
$('.overlay').on('click', function(){
    $(".overlay").fadeToggle(200);
    $(".button a").toggleClass('btn-open').toggleClass('btn-close');
    open = false;
});
remove();
function remove() {
    if($(this).scrollTop()>70){
        $('.container-logo').addClass('remove-logo');
        $('.navigation-menu').addClass('navigation-menu-static');
    }
    else if ($(this).scrollTop()<70){
        $('.container-logo').removeClass('remove-logo');
        $('.navigation-menu').removeClass('navigation-menu-static');

    }
}

jQuery(function($) {
    $(window).scroll(function(){
        remove();
    })


});
