$(document).ready(function(){
    var sortableContainer = $('#sortable-grid tbody');
    sortableContainer.sortable({
        axis: 'y',
        stop:function () {
            reindexItems();
        }
    });
    sortableContainer.disableSelection();
    reindexItems();
    $('.change-current-position').on('change',function () {
        var positionsList = $('.sortable-item');
        $(this).closest('.sortable-item').insertAfter(positionsList[$(this).val()-1]);
        reindexItems();
    });
    function reindexItems() {
        $('input.change-current-position').each(function(idx) {
            $(this).val(idx);
        });
    }
});