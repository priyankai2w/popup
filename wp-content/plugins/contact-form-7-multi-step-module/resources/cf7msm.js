jQuery(document).ready(function($) {
	if (cf7msm_posted_data) {
		var step_field = $("input[name='step']");
		//multi step forms
		if (step_field.length > 0) {
			var cf7_form = $(step_field[0].form);
			$.each(cf7msm_posted_data, function(key, val){
				if (key == 'cf7msm_prev_urls') {
					cf7_form.find('.wpcf7-back, .wpcf7-previous').click(function(e) {
						window.location.href = val[step_field.val()];
						e.preventDefault();
					});
				}
				if ( ( key.indexOf('_') != 0 || key.indexOf('_wpcf7_radio_free_text_') == 0 || key.indexOf('_wpcf7_checkbox_free_text_') == 0 ) && key != 'step') {
					var field = cf7_form.find('*[name="' + key + '"]');
					if (field.length > 0) {
						if ( field.prop('type') == 'radio' || field.prop('type') == 'checkbox' ) {
							field.filter('input[value="' + val + '"]').prop('checked', true);
						}
						else {
							field.val(val);	
						}
					}
					else {
						//checkbox
						field = cf7_form.find('input[name="' + key + '[]"]'); //value is this or this or tihs
						if (field.length > 0) {
							if ( val != '' && val.length > 0  ) {
								$.each(val, function(i, v){
									field.filter('input[value="' + v + '"]').prop('checked', true);
								});	
							}
						}
					}
				}
			});
		} //end multi step forms
	}
});