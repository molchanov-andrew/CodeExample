$(document).ready(function(){
    $(document).on('click','.open-modal-link',function (e) {
        e.preventDefault();
        openGeneralModal($(this));
    });
});
function openGeneralModal(self) {
    var modal = $('#modalGeneral');
    modal.find('.modal-content').html(
        '<div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button>' +
        '<h4 class="modal-title">Loading ...</h4></div><div class="modal-body">Please wait until data will be loaded.</div>' +
        '<div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div>'
    );
    if(!modal.hasClass('in')) {
        modal.modal('show');
    }
    $.ajax({
        type: 'POST',
        cache: false,
        url: self.attr('href'),
        success: function(response) {
            $('#modalGeneral').find('.modal-content').html(response);
        },
        error:function(xhr, textStatus){
            console.log(xhr);
            console.log(textStatus);
        }
    });
}