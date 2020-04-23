$(document).ready(function(){
    $(document).on('click','.hidden-opener',function (e) {
        $(this).siblings('.hidden-until-clicked').toggleClass('shown');
        var text = $(this).text();
        var nextText = $(this).attr('data-next-text');
        $(this).text(nextText);
        $(this).attr('data-next-text',text);
    })
});