$(function () {
    $('.popup-modal').magnificPopup({type: 'inline', preloader: false, focus: '#username', modal: true, });
    $(document).on('click', '.popup-modal-dismiss', function (e) {
        e.preventDefault();
        $.magnificPopup.close();
    });
});
$(window).on('load',function(){
    $('.ticker-container').css('display','block');
});
function centerModal() {
    $(this).css('display', 'block');
    var $dialog = $(this).find(".modal-dialog");
    var offset = ($(window).height() - $dialog.height()) / 2;
    $dialog.css("margin-top", offset);
}
$('.modal').on('show.bs.modal', centerModal);
$(window).on("resize", function () {
    $('.modal:visible').each(centerModal);
});
$(document).ready(function() {
    $(".tablesorter").tablesorter(); 
    $('[data-toggle="tooltip"]').tooltip();
    $("abbr.timeago").timeago(); $("span.timeago").timeago(); 

    $(".display").click(function() {        
        $(this).toggleClass('glyphicon-minus');
        $(this).prev(".sub-menu").toggle();       
    });
    document.addEventListener("DOMContentLoaded", function(event) {
        document.querySelectorAll('img').forEach(function(img){
            img.onerror = function(){this.style.display='none';};
        })
    });
});
