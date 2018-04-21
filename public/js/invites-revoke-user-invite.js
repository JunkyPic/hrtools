function revokeUserInvite(invite_id) {
    $.ajax({
        type: 'POST',
        url: $('#user_revoke_invite').val(),
        data: {
            invite_id: invite_id
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