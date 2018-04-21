$(document).ready(function() {
    var route = $('#question-chapter-associate').val();

    $('.associate-question').on('change', function() {
        var selected = $(this).find('option:selected');
        var qid = selected.data("qid");
        var chapter_id = selected.val();

        if(chapter_id !== "NA" && qid !== "NA") {
            associate(qid, chapter_id, route, 'add');
        }

    });

    $('.remove-question').on('change', function() {
        var selected = $(this).find('option:selected');
        var qid = selected.data("qid");
        var chapter_id = selected.val();

        if(chapter_id !== "NA" && qid !== "NA") {
            associate(qid, chapter_id, route, 'remove');
        }

    });

});

function associate(qid, chapter_id, route, type) {
    $.ajax({
        type: 'POST',
        url: route,
        data: {
            qid: qid,
            type: type,
            chapter_id: chapter_id
        },
        cache: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data){
            $('#message_display').show();

            if(data.hasOwnProperty('success') && data.success == true) {
                $('#message_output').html(data.message);
            } else {
                $('#message_output').html(data.message);
            }
        }
    });
}
