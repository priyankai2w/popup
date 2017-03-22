jQuery.noConflict();
(function($){
	$(function() {
		$('#scheduleacall').datetimepicker({
		 	minDate: new Date,
   			format: "DD/MM/YYYY hh:mm:ss A"  });
		$('.pricing-box').each( function(k) {
			$(this).find('.plan-footer a.button_theme').attr('id', 'ordernow'+k);
			$(this).find('#ordernow'+k).click(function () {
				var packagename = $(this).parent('.plan-footer').siblings('.plan-header').find('h2').text();
				var pricesymbol = $(this).parent('.plan-footer').siblings('.plan-header').find('.price .currency').text();
				var priceamount = $(this).parent('.plan-footer').siblings('.plan-header').find('.price').contents().filter(function () {
				     return this.nodeType === 3; 
				}).text();
				$('#packagename').val(packagename);
				$('#packageamt').val(priceamount);
				$('#pricesymbol').val(pricesymbol);
			});
		});
		$('.modal-content').each( function (m) {
			$(this).find('form').attr('id', 'form'+m);
			$('#form'+m).each( function () {
				// $(this).find('[name="first_form"]').click( function (e) {
				// 	$('#myModal2').modal('show');
				// });

				$(this).find('[type="submit"]').click( function ( event ) {
					// alert('hiiiii');
					// console.log( $( this ).serialize() );
					event.preventDefault();
					// Check if there is an entered value
					var fname_val = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #firstname');
					var lname_val = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #lastname');
				    if(!fname_val.val() || !lname_val.val()) {
				      // Add errors highlight
				      	fname_val.closest('.form-group').removeClass('has-success').addClass('has-error');
						lname_val.closest('.form-group').removeClass('has-success').addClass('has-error');
				      // Stop submission of the form
				      
				    } else {
				      // Remove the errors highlight
				      fname_val.closest('.form-group').removeClass('has-error').addClass('has-success');
				      lname_val.closest('.form-group').removeClass('has-error').addClass('has-success');
				    }
// event.preventDefault();

					//form one
					var fname = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #firstname').val();
					var lname = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #lastname').val();
					var email = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #inputemail').val();
					var phnumber = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #phonenumber').val();
					console.log(fname+' '+lname+' '+email+' '+phnumber);
					var packagename = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #packagename').val(); 
					var packageamt = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #packageamt').val();
					var pricesymbol = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #pricesymbol').val();
					console.log(packagename+' '+packageamt+' '+pricesymbol);
					// var data = '';
					//form two
					var websiteurl = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #websiteurl').val();
					var businessaddress = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #businessaddress').val();
					console.log(websiteurl+'*'+businessaddress);
					//form three
					var scheduleacall = $(this).parent('.modal-footer').siblings('.modal-body').find(' div div #scheduleacall').val();
					console.log(scheduleacall+'-*-'+'');


					if( ((fname != '') && (fname != null))  && ((lname != '') && (lname != null)) && ((email != '') && (email != null)) && ((phnumber != '') && (phnumber != null)) ) {
						$('#myModal2').modal('show');
						$('#myModal').modal('hide');
						
						$.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
				       		options.async = true;
					    });
					  	var ajaxurls = ajax_object.ajaxurl;
					    jQuery.ajax({
					        url: ajaxurls, //AJAX file path - admin_url('admin-ajax.php')
					        type: "POST",
					        dataType : "json",					       
					        data: {
					            //action name
					            action:'strideup_multistep',
					            nonce: ajax_object.nonce,
					            fname : fname,
					        },
					        beforeSend: function() {
								// $(".loading-image").show();
							},
					        async : true,
					        success: function(datas){
					        	console.log(datas);
					        	// $(".loading-image").hide();
					        }
					    });
					}

					if( (typeof fname === "undefined") || (typeof lname === "undefined") || (typeof email === "undefined") || (typeof phnumber === "undefined") || (typeof packagename === "undefined") || (typeof packageamt === "undefined") || (typeof pricesymbol === "undefined") ) {
						alert('test');
					}
					else if( (typeof websiteurl === "undefined") || (typeof businessaddress === "undefined") ) {
						
					}
					else if( (typeof websiteurl === "undefined") ) {

					}
						
				});
			  
			});
		});
	});
})(jQuery);