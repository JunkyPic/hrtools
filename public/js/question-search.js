$(document).ready(function() {

    $('#search-input').typeahead({
        ajax: {
            url: $('#api-route').val(),
            timeout: 500,
            displayField: "title",
            triggerLength: 2,
            method: "get",
            loadingClass: "loading-circle"
        },
        onSelect: function(item) {
            window.location = 'question/' + item.value + '/edit';
        }
    });
});
