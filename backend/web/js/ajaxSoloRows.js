$(document).ready(function(){
    $(document).on('click','.ajax-solo-rows',function(e){
        e.preventDefault();

        var url = $(this).attr('href');
        var pjaxContainer = $(this).attr('data-pjax-container');
        var rows = $('.grid-view').yiiGridView('getSelectedRows');
        var confirmMessage = $(this).attr('data-solo-confirm');
        if(typeof confirmMessage !== typeof undefined){
            if(confirm(confirmMessage)) {
                sendAjaxSolo(url,rows,pjaxContainer);
            }
        } else {
            sendAjaxSolo(url,rows,pjaxContainer);
        }
    });
});

$(document).ready(function(){
    $(document).on('click','.ajax-solo',function(e){
        e.preventDefault();
        var url = $(this).attr('href');
        var pjaxContainer = $(this).attr('data-pjax-container');
        $.post(url,{},function (response) {
            if(typeof response.data !== typeof undefined){
                showAjaxResponse(response);
            }
            else if (typeof response === "string") {
                showNotification(response,'alert-success');
            }
            if($('#pjax').length > 0){
                $.pjax.reload({container: '#pjax',timeout : 10000});
            }
            if(typeof pjaxContainer !== typeof undefined){
                $.pjax.reload({container:pjaxContainer});
            }
        })
    })
});

function sendAjaxSolo(url,rows,pjaxContainer) {
    $.post(url,{rows:rows},function (response) {
        if(typeof response.data !== typeof undefined){
            showAjaxResponse(response);
        }
        else if (typeof response === "string") {
            showNotification(response,'alert-success');
        }
        if($('#pjax').length > 0){
            $.pjax.reload({container: '#pjax',timeout : 10000});
        }
        if(typeof pjaxContainer !== typeof undefined){
            $.pjax.reload({container:pjaxContainer});
        }
    });
}