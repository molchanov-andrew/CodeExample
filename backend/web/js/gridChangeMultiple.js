$(document).ready(function(){
    $(document).on('click','.change-multiple-grid',function(e){
        e.preventDefault();
        setTimeout(function (){
            var formIds = getFormIds();
            disableFormElements(formIds);
        },1000);
    });
    $(document).on('submit','.change-multiple-modal form', function(e){
        gridchangeMultiple($(this));
        e.preventDefault();
        return false;
    });
});

function getFormIds() {
    var fieldsString = $('.change-multiple-modal form').attr('data-fields');
    if(typeof fieldsString === typeof undefined){
        return [];
    } else {
        return fieldsString.split(',');
    }
}

function disableFormElements(data) {
     for(var key in data) {
        var id = data[key];
        var element = $('#' + data[key]);
        disableField(element);
        createTogglingSwitch(element,id);
    }
}

function createTogglingSwitch(node,elementId) {
    var togglingSwitch = $('<label class="switch">' +
        '            <input name="toggle-'+elementId+'" type="checkbox">' +
        '            <span class="slider round"></span>' +
        '        </label>');
    var ishidden = typeof node.attr('type') !== typeof undefined && node.attr('type') === 'hidden';
    if(!ishidden){
        node.before(togglingSwitch);
    } else {
        return;
    }
    $(document).on('change','input[name=toggle-'+elementId+']',function(e){
        e.preventDefault();
        if(isFieldDisabled(node)){
            enablefield(node);
        } else {
            disableField(node);
        }
    });
}

function isFieldDisabled(node) {
    if(checkIsFroala(node)){
        return node.siblings('.fr-box').find('.fr-toolbar').hasClass('fr-disabled');
    } else {
        return typeof node.attr('disabled') !== typeof undefined;
    }
}

function disableField(node) {
    if(checkIsSelect2(node)){
        node.select2("enable",false)
        node.siblings('input[type=hidden]').attr('disabled','disabled');
    } else if(checkIsCheckbox(node)) {
        $(node).siblings('input[type=hidden]').attr('disabled','disabled');
        node.attr('disabled','disabled');
    } else if(checkIsFroala(node)){
        node.froalaEditor("edit.off");
    } else {
        node.attr('disabled','disabled');
    }
}

function enablefield(node) {
    if(checkIsSelect2(node)){
        node.select2("enable")
        node.siblings('input[type=hidden]').removeAttr('disabled');
    } else if(checkIsCheckbox(node)) {
        $(node).siblings('input[type=hidden]').removeAttr('disabled');
        node.removeAttr('disabled');
    } else if(checkIsFroala(node)){
        node.froalaEditor("edit.on");
    } else {
        node.removeAttr('disabled');
    }
}

function checkIsCheckbox(node) {
    var type = node.attr('type');
    return typeof type !== typeof undefined && type === 'checkbox';
}

function checkIsSelect2(node) {
    return node.data('select2');
}

function checkIsFroala(node) {
    return node.hasClass('froala-text-editor');
}

function gridchangeMultiple(node) {
    var rows = $('.grid-view').yiiGridView('getSelectedRows');
    var form = $(node);
    var formObject = $(form)[0];
    // var formData = form.serialize();
    var formData = new FormData(formObject);
    var content = $(node).closest('.modal-content');
    var modal = $('#modalGeneral');

    if(typeof rows[0] === 'object') {
        for(var key in rows){
            for(var field in rows[key]){
                formData.append('rows['+key+']['+field+']',rows[key][field]);
            }
        }
    } else {
        formData.append('rows',rows);
    }

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
                '<h4 class="modal-title">Ajax error... </h4></div><div class="modal-body">Please contact administrator with description how is it happened.</div>' +
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