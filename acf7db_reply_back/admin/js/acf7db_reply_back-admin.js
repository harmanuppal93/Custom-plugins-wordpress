(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

})( jQuery );

jQuery(document).ready(function(){
	
	// Display field value when field selected
	jQuery("#cf7-rb-to-name-select").change(function(){
		var nameVal = jQuery(this).val();
		jQuery("#acf7_db_rb_to_name").val(nameVal);
	});
	
	// Display field value when field selected
	jQuery("#cf7-rb-to-email-select").change(function(){
		var emailVal = jQuery(this).val();
		jQuery("#acf7_db_rb_to_email").val(emailVal);
	});
	
	jQuery('a.cf7-rb-value').click(function(event) {
		jQuery('#cf7-rb-modal-form-edit-value').removeClass('loading');
		jQuery('body').addClass('our-body-class');
		var prefix = "acf7_db_rb_";
		jQuery("#"+prefix+"to_email").val('');
		jQuery("#"+prefix+"to-email-select option[value='']").prop("selected", true); 
		jQuery("#"+prefix+"is_html").prop('checked',false);		
		jQuery("#"+prefix+"msg_body").val('');
		jQuery("#"+prefix+"subject").val('');
		jQuery('.vsz-reply-error').html('');
		document.getElementById('rb_overlayLoader').style.display = "block";
		var rid = parseInt(jQuery(this).data('rid'));
		var fid = parseInt(jQuery('input[name="fid"]').val());
			
		var arr_field_type = jQuery.parseJSON(jQuery('form#cf7-rb-modal-form-edit-value input[name="arr_field_type"]').val());
		var arr_option = ['radio','checkbox','select'];
		
        jQuery('form#cf7-rb-modal-form-edit-value input[name="rid"]').attr('value', rid);
		rs = jQuery('form#cf7-rb-modal-form-edit-value input[class^="field-"]');
		
		//Set all fields value is loading
		for(var fieldname in arr_field_type){
			jQuery('form#cf7-rb-modal-form-edit-value option[class^="field-'+fieldname+'"]').val("");
		}
		
		//Call Ajax request here for get entry related information
		jQuery.ajax({
            url: ajaxurl + '?action=vsz_cf7_edit_form_value',
            type: 'POST',
            data: {'rid': rid,'fid':fid},
        })
        .done(function(data) {
            //Decode json data here
			var json = jQuery.parseJSON(data);
			//Set fields value
			jQuery.each(json, function(index, el){
                //Get existing fields information
				console.log(index+" => "+el);
				jQuery('form#cf7-rb-modal-form-edit-value option[class^="field-'+index+'"]').val(el);
			});
        })
        .fail(function() {
            console.log("error");
		})
        .always(function() {
            console.log("complete");
			document.getElementById('rb_overlayLoader').style.display = "none";
        });
	});
	
	jQuery("#acf7_db_rb_send_reply_email").click(function(){
		var emailRegx = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,10})+$/;  // email checking regex
		var prefix = "acf7_db_rb_";
		
		// validating new user registration email template
		var fromName = jQuery("#"+prefix+"from_name").val();
		var msgBody = jQuery("#"+prefix+"msg_body").val();
		var fromEmail = jQuery("#"+prefix+"from_email").val();
		var toEmail = jQuery("#"+prefix+"to_email").val();
		var mail_subject = jQuery("#"+prefix+"subject").val();
		fromEmail = fromEmail.trim();
		toEmail = toEmail.trim();
		mail_subject = mail_subject.trim();
		error = 0;
		jQuery('.vsz-reply-error').html('');
		if(fromName == "" || fromName == undefined){					// subject is blank
			//alert("Please type email subject");
			jQuery('.vsz-reply-error').append('<div id="message" class="notice error is-dismissible"><p>Please enter <b>From Name</b> field</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
			error = 1;			
		}
		if(fromEmail == "" || fromEmail == undefined){
			//alert("Please type email from field");			// from email is blank
			jQuery('.vsz-reply-error').append('<div id="message" class="notice error is-dismissible"><p>Please enter <b>From Email address</b> field</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
			error = 1;
			//return false;
		}else if (!emailRegx.test(fromEmail)) {
			//alert("Please type valid from email");
			jQuery('.vsz-reply-error').append('<div id="message" class="notice error is-dismissible"><p>Please enter valid <b>From  Email address</b>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
			error = 1;
			//jQuery("#"+prefix+"from_email").focus();	// from email is invalid
			//return false;
		}
		if(toEmail == "" || toEmail == undefined){			// to email is blank
			jQuery('.vsz-reply-error').append('<div id="message" class="notice error is-dismissible"><p>Please enter <b>To Email address</b> field</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
			error = 1;
			//return false;
		}
		else{
			var toArray = toEmail.split(",");			// to email can be multiple  so splitting it
			var i;
			for(i=0;i<toArray.length;i++){
				if (!emailRegx.test(toArray[i])) {				// to email is invalid 
					jQuery('.vsz-reply-error').append('<div id="message" class="notice error is-dismissible"><p>Please enter valid <b>To Email address</b>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
					error = 1;
					//return false;
				}
			}
		}
		if(mail_subject == "" || mail_subject == undefined){					// subject is blank
			jQuery('.vsz-reply-error').append('<div id="message" class="notice error is-dismissible"><p>Please enter mail <b>Subject</b> field.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
			error = 1;
			//return false;
		}
		if(msgBody == "" || msgBody == undefined){					// subject is blank
			jQuery('.vsz-reply-error').append('<div id="message" class="notice error is-dismissible"><p>Please enter <b> Message Body</b> field.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
			error = 1;
			//return false;
		}
		if(error == 1){
			return false;
		}

		
		//Call Ajax request here to send reply email
		var fd = jQuery("#cf7-rb-modal-form-edit-value").serialize();
		jQuery.ajax({
            url: ajaxurl + '?action=acf7_db_rb_send_mail',
            type: 'POST',
            data: fd,
        })
        .done(function(data) {
			if(data.trim() == "y"){
				jQuery('.vsz-reply-error').html('');	
				jQuery('.vsz-reply-error').append('<div id="message" class="notice notice-success is-dismissible"><p>Reply email sent successfully!.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>');
				//alert("Reply email sent successfully!");
				setTimeout(function(){ 
					self.parent.tb_remove();
					location.reload();
				}, 1000);
			}
			else{
				alert("Sorry ! Reply email was not sent.");
			}
		   
		   document.getElementById('rb_overlayLoader').style.display = "none";
        })
        .fail(function() {
            console.log("error");
		})
        .always(function() {
            console.log("complete");
			document.getElementById('rb_overlayLoader').style.display = "none";
        });
	});
	jQuery(document).on('click','.notice-dismiss',function(){
					jQuery(this).parent().remove();
				});

});