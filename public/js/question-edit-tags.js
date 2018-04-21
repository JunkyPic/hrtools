$(document).ready(function() {
    var search_input = $('#search-input');
    var current_tags = $('#current_tags');
    var manage_tag_route = $('#manage_tag_route').val();
    var qid = $('#qid').val()

    search_input.typeahead({
        ajax: {
            url: $('#tags_api_route').val(),
            timeout: 500,
            displayField: "tag",
            triggerLength: 2,
            method: "get",
            loadingClass: "loading-circle"
        },
        onSelect: function(item) {
            search_input.val('');
            search_input.focus();
            var html = '';

            html += '<div class="col-lg-2" id="tag_id_' + item.value  + '" onclick="manageTag($(this))" data-tag-content="' + item.text +'" data-tag-id="' + item.value + '"><button type="button" class="close" data-dismiss="alert">&times;</button>';
            html += '<span class="badge badge-primary">' +  item.text + '</span></div>';

            current_tags.append(html);

            $.ajax({
                type: 'POST',
                url: manage_tag_route,
                data: {
                    qid: qid,
                    add: item.value
                },
                cache: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(data){
                    console.log(data);
                    $('#message_display').show();

                    if(data.hasOwnProperty('success') && data.success == true) {
                        $('#message_output').html(data.message);
                    } else {
                        $('#message_output').html(data.message);
                    }
                }
            });
        }
    });

});

function manageTag(self) {
    $('#tag_id_' + self.attr("data-tag-id")).remove();

    $.ajax({
        type: 'POST',
        url: $('#manage_tag_route').val(),
        data: {
            qid: $('#qid').val(),
            remove: self.attr("data-tag-id")
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
