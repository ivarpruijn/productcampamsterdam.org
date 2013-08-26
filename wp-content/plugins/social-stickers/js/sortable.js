jQuery(document).ready(function() {
	jQuery("#sortable").sortable({
		stop: function(event, ui) {
			jQuery("#social_stickers_order").val(jQuery(this).sortable('serialize'));
		}
	});
});