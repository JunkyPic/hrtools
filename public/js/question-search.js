$(document).ready(function() {

    $('#search-input').typeahead({
        ajax: {
            url: $('#api-route').val(),
            timeout: 500,
            displayField: "title",
            triggerLength: 1,
            method: "get",
            loadingClass: "loading-circle",
            preDispatch: function (query) {
                return {
                    search: query
                }
            },
            preProcess: function (data) {
                if (data.success === false) {
                    // Hide the list, there was some error
                    return false;
                }
                // We good!
                return data;
            }
        },
        onSelect: function(item) {
            window.location = 'question/' + item.value;
        }
    });
});