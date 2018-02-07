$(document).ready(function(){
    $(".display").click(function() {        
        $(this).toggleClass('glyphicon-minus');
        $(this).prev(".sub-menu").toggle();       

    });
});
