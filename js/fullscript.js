jQuery(document).ready(function($) {
	var formData = {action: 'get_calendar_details_ajax'};
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: formData,
			success: function (data) {	
				$('#woo-order-calendar').fullCalendar({
					editable: false,
					events: [data]
				});
			},
			error: function (MLHttpRequest, textStatus, errorThrown) {
		}
		});
});