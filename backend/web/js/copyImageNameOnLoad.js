$(document).ready(function(){
    var filename = $('#image-filename');
    $('#image-file').change(function () {
        var file = $(this)[0].files[0];
        if (file){
            filename.val(file.name);
        }       
    })
});