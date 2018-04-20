$(document).ready( function () {
    var ids = [];
    var q_id = $('#question_id').val();
    var image_delete_interface = $('#image__delete_interface');
    var image_cancel_btn = $('#image__cancel_btn');
    var image_delete_btn = $('#image__delete_btn');
    var image_select = $('.image__select');
    $('#message_display').hide();
    var route = $('#image_update_route').val();

    image_delete_interface.hide();

    image_select.on('click', function () {
        if(!image_delete_interface.is(":visible")) {
            image_delete_interface.show();
        }
        var id = $(this).attr('id');
        if($(this).hasClass('border')) {
            var index = ids.indexOf(id);
            if (index > -1) {
                ids.splice(index, 1);
            }

            $(this).removeClass('border');
        } else {
            ids.push(id);
            $(this).addClass('border');
        }
    });

    image_cancel_btn.on('click', function () {
        image_delete_interface.hide();
        image_select.removeClass('border');
        ids = [];
    });

    image_delete_btn.on('click', function () {
        updateImages(ids, q_id, route);
    })

});

function updateImages(ids, q_id, route) {
    $.ajax({
        type: 'POST',
        url: route,
        data: {
            ids: ids,
            qid: q_id
        },
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
            console.log(data);
            if(data.hasOwnProperty('success') && data.success == true) {
                $.each(data.ids, function( index, value ) {
                    $('#image_row_' + value).remove();
                    $('#message_display').show();
                    $('#message_output').html(data.message);
                });
            } else {
                $('#message_display').show();
                $('#message_output').html(data.message);
            }
        }
    });
}