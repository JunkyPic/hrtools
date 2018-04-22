$(document).ready(function() {
    var route = $('#chapter-test-associate-route').val();

    $('.associate-chapter').on('change', function() {
        var selected = $(this).find('option:selected');
        var chapter_id = selected.data("cid");
        var test_id = selected.val();

        if(test_id !== "NA" && chapter_id !== "NA") {
            associate(chapter_id, test_id, route, 'add');
        }

    });

    $('.remove-chapter').on('change', function() {
        var selected = $(this).find('option:selected');
        var chapter_id = selected.data("cid");
        var test_id = selected.val();

        if(test_id !== "NA" && chapter_id !== "NA") {
            associate(chapter_id, test_id, route, 'remove');
        }

    });

});

function associate(chapter_id, test_id, route, type) {
    $.ajax({
        type: 'POST',
        url: route,
        data: {
            chapter_id: chapter_id,
            type: type,
            test_id: test_id
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
