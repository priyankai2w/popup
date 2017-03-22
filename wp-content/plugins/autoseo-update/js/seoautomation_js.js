jQuery.noConflict();
(function( $ ) {
  $(function() {
    		/*$('input.mycheckbox').on('change', function() {
     $('input.mycheckbox').not(this).prop('checked', false); 
     $("input:checkbox[name=chk]:checked").each(function () {
            //alert("Id: " + $(this).attr("id") + " Value: " + $(this).val());
        });
});*/
    $('.mycheckbox').on('change',function(){
    $('input.mycheckbox').not(this).prop('checked', false); 
    $("input:checkbox[name=chk]:checked").each(function () {
    });
    });
    $('#tab_form #cb-select-all-1').hide();
    $('#tab_form #cb-select-all-2').hide();
    $('#tab_form #bulk-action-selector-top').hide();
    $('#tab_form #bulk-action-selector-bottom').hide();
    $('#tab_form #doaction').hide();
    $('#tab_form #doaction2').hide();
    //$('#fileToUpload').on('change', function (e) {
	//		e.preventDefault();
	//		var file = e.target.files[0];
	//		var reader = new FileReader();
	//		reader.onload = function (event) {
	//		    var csv = reader.result;
	//		    console.log(csv);
			    // $('#textarea1').val(csv.join('\n'));
			    // $('#cke_1_contents .cke_wysiwyg_frame').contents().find('.cke_editable').html(csv);
			    //$('.editor_content').contents().find('.cke_editable').html(csv);
			    //$('#tab2_editor_content_ifr').contents().find('#tinymce').html(csv);
			    //$('#ss').html(csv);
	//		}
	//		reader.readAsText(file);
		//});
  });
})(jQuery);