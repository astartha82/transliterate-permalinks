(function ($) {
	$(document).ready(function() {
		$('#select_post_types, #select_taxonomies').click(function (e) {
			var $checkboxes = $(this).parents('th').next('td').find('input[type=checkbox]');
			$(this).toggleClass('checked');
			if($(this).hasClass('checked'))
				$checkboxes.prop("checked", true);
			else
				$checkboxes.prop("checked", false);
		});

		$('#transliterate-all').click(function(e){
			var data = {
				'action': 'transliterate_all'
			}
			$.post(ajaxurl, data, function(response) {
				$('#transliterate-all').addClass('success').text(response);
			});
		});
	})
})(jQuery);

