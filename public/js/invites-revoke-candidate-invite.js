function revokeUserInvite(test_id, self) {
	$.ajax({
		type: 'POST',
		url: $('#candidate_invalidate_invite').val(),
		data: {
			test_id: test_id
		},
		cache: false,
		headers: {
			'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		},
		success: function(data){
			$('#message_display').show();
			if(data.hasOwnProperty('success') && data.success == true) {
				var parent_parent = self.parent().parent();
				self.parent().remove();
				parent_parent.append('<span>Invite revoked</span>');
			}

			$('#message_output').html(data.message);
		}
	});
}
