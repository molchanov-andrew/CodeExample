function beforeSubmitAjax(node){
    var form = $(node);
    var formObject = $(form)[0];
    // var formData = form.serialize();
    var formData = new FormData(formObject);
    var content = $(node).closest('.modal-content');
    var modal = $('#modalGeneral');
    var reloadForm = $(node).attr('data-reload-form');

    $.ajax({
        url: form.attr("action"),
        type: form.attr("method"),
        data: formData,
        processData: false,
        contentType: false,
        success: function (data) {
            form[0].reset();
            modal.modal('toggle');
            if(typeof data === 'object')
            {
                showAjaxResponse(data);
            }
            else{
                showNotification(data,'alert-success');
            }
            if($('#pjax').length > 0 && (typeof reloadForm === typeof undefined || reloadForm != false)){
                $.pjax.reload({container: '#pjax',timeout : 10000});
            }
        },
        error: function (xhr) {
            console.log(xhr);
            content.html(
                '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>' +
                '<h4 class="modal-title">Ajax error... </h4></div><div class="modal-body">Please contact administrator with description how is it happend.</div>' +
                '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>'
            )
        }
    });
    content.html(
        '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>' +
        '<h4 class="modal-title">Performing ajax request ... </h4></div><div class="modal-body">It can take some time.</div>' +
        '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>'
    )
}
$(document).on('submit','form.ajax-form', function(e){
    e.preventDefault();
    beforeSubmitAjax($(this));
    return false;
});