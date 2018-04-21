$(document).ready(function() {
    var current_tags = $('#current_tags');
    var search_input = $('#search-input');
    var hidden_tags = $('#hidden_tags');
    var tags = {};
    var hidden_tags_tags = [];

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
            var html_tags = '';
            tags[item.value] = item.text;

            $.each(tags, function( index, value ) {
                hidden_tags_tags.push(index);
                html += '<div class="col-lg-2" onclick="remove($(this))" id="' + index + '"><button type="button" class="close" data-dismiss="alert">&times;</button>';
                html += '<span class="badge badge-primary">' + value +'</span></div>';
            });

            var uniqueArray = hidden_tags_tags.filter(function(item, pos) {
                return hidden_tags_tags.indexOf(item) === pos;
            });

            current_tags.html(html);

            $.each(uniqueArray, function( index, value ) {
                html_tags += '<input type="hidden" id="hidden_tag_input_' + value + '" value="' + value + '" name="tags[]">';
            });

            hidden_tags.html(html_tags);
        }
    });

});

function remove(self) {
    var hidden_tags = $('#hidden_tags');
    var id = self.attr('id');

    hidden_tags.children('input').each(function () {
        if(this.value == id) {
            $('#' + this.value).remove();
            $('#hidden_tag_input_' + this.value).remove();
        }
    });
}

