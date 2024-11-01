(function ( $ ) {
	"use strict";
	$(function () {
		$('.giveaway-list').each(function() {
			$(this).parent().parent().hide();
		});
		$('#giveaway_by_' + $('.giveaway-by').val()).parent().parent().show();

		$('.giveaway-by').on('change', function() {
			$('.giveaway-list').each(function() {
				$(this).parent().parent().hide();
			});
			$('#giveaway_by_' + $(this).val()).parent().parent().show();
		});
	});
}(jQuery));
