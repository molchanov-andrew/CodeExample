$(document).ready(function(){
    $(document).on('hidden.bs.modal', function (event) {
        if ($('.modal:visible').length) {
            $('body').addClass('modal-open');
        }
    });

});

function showNotification(message,styleClass,timeout) {
    timeout = typeof timeout !== 'undefined' ? timeout : 2000;
    var alert = $('#generalAlert');
    alert.html(message).addClass(styleClass).show('fast');
    setTimeout(function(){
        alert.hide('fast').html('').removeClass(styleClass);
    },timeout);
}

// Function for getting default response.
function showAjaxResponse(response) {
    // var data = JSON.parse(response);
    var data = response;
    var status = '';
    if(data.status === 'success') {
        status = 'alert-success';
    } else if(data.status === 'warning') {
        status = 'alert-warning';
    } else {
        status = 'alert-danger';
    }
    showNotification(data.message,status);
}
