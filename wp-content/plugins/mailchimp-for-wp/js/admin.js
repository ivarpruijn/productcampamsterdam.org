(function($) { 

	// variables
	var FormDesigner;
	// event bindings
	$("#mc4wp-nav a").click(function(e) {
		var target, wp_referer;

		target = $(this).attr('data-target');
		$("#mc4wp-tabs .mc4wp-tab.active").removeClass('active');
		$("#mc4wp-tab-" + target).addClass('active');

		// show info tabs
		$("#mc4wp-info-tabs .info-tab").hide();
		$("#mc4wp-info-tabs .info-tab-" + target).show();

		$("#mc4wp-nav .active").removeClass('active');
		$(this).addClass('active');

		// Change window location to add URL params
		if (window.history && history.replaceState) {
		  // NOTE: doesn't take into account existing params
			history.replaceState("", "", $(this).attr('href'));
		}

		// update WP hidden input field
		$('input[name="_wp_http_referer"]').val(mc4wp_urls.admin_page + "&tab=" + target);

		if($("#mc4wp-tab-" + target).is(":visible")) {
			e.preventDefault();
			return false;
		} else {
			return true;
		}
		
	});

	$("#mc4wp_form_usage_1").click(function() { 
		$("#mc4wp_form_options, #mc4wp_form_options_2").fadeIn(); 
	});
	$("#mc4wp_form_usage_0").click(function() { 
		$("#mc4wp_form_options, #mc4wp_form_options_2").fadeOut(); 
	});

	FormDesigner = {
		fields: {
			$value: $("#mc4wp_ffd_field_value"),
			$placeholder: $("#mc4wp_ffd_field_placeholder"),
			$type: $("#mc4wp_ffd_field_type"),
			$name: $("#mc4wp_ffd_field_name"),
			$all: $("#mc4wp_ffd_fields"),
			$wrapInP: $("#mc4wp_ffd_wrap_in_p"),
			$preview: $("#mc4wp_ffd_preview_field_code"),
			$form: $("#mc4wp_form_markup"),
			$required: $("#mc4wp_ffd_field_required"),
			$label: $("#mc4wp_ffd_field_label"),
			$preset: $("#mc4wp_ffd_field_preset"),
			$valueLabel: $("#mc4wp_ffd_field_value_label")
		},
		updatePreviewCode: function() {
			var f = this.fields, fieldPreview = '';

			var fieldId = "mc4wp_f%N%_"+ f.$name.val().toLowerCase();

			// wrap in <p> tags if necessary
			if(f.$wrapInP.is(':checked:visible')) { fieldPreview += "<p>\n\t"; }

			// setup field code
			if(f.$label.is(':visible') && f.$label.val() != '') { fieldPreview += "<label for=\"" + fieldId +"\">"+ f.$label.val() +"</label>\n\t"; }

			fieldPreview += "<input type=\""+ f.$type.val() + "\" ";

			// add name attribute
			if(f.$type.val() != "submit") {
				fieldPreview += "name=\""+ f.$name.val() +"\" ";
			}

			// add value attribute
			if(f.$value.val() != '') { fieldPreview += "value=\""+ f.$value.val() +"\" "; }

			// add id attribute
			if(f.$type.val() != 'hidden' && f.$type.val() != 'submit') {
				fieldPreview += "id=\"" + fieldId + "\" ";
			}

			// if placeholder is given, add it. Otherwise, omit it for W3C validity
			if(f.$placeholder.is(':visible') && f.$placeholder.val() != '') { fieldPreview += "placeholder=\""+ f.$placeholder.val() +"\" "; }
			if(f.$required.is(':visible') && f.$required.is(":checked")) { fieldPreview += "required" ; }
			
			// add closing trailing flash
			fieldPreview += "/>";

			// add closing </p> tag, if necessary
			if(f.$wrapInP.is(':checked:visible')) { fieldPreview += "\n</p>"; }

			// show preview code
			f.$preview.val(fieldPreview);
		},
		setup: function(fieldType) {
			var f = this.fields;
			// reset
			f.$all.hide();
			f.$all.find('p.row').show();

		
			// set field defaults
			f.$name.val('');
			f.$value.val('');
			f.$label.val('');
			f.$placeholder.val('');
			f.$preset.val('');
			f.$wrapInP.prop('checked', true);
			f.$required.prop('checked', false);
			f.$valueLabel.html("Initial value");

			// hide or show some of the fields, depending on chosen fieldType
			switch(fieldType) {

				case 'hidden':
					f.$all.find('.row-placeholder, .row-wrap-in-p, .row-label, .row-required').hide();
					f.$wrapInP.prop('checked', false);
				break;

				case 'submit':
					f.$all.find('.row-placeholder, .row-name, .row-label, .row-required, .row-preset').hide();
					f.$valueLabel.html("Button text");
				break;

				case 'checkbox':
					f.$all.find('.row-placeholder, .row-required, span.initial').hide();
					f.$valueLabel.html("Value");
				break;

			}

			f.$all.show();
			FormDesigner.preset(f.$preset.val());
			FormDesigner.updatePreviewCode();
		},
		validateFields: function() {
			var f = this.fields;

			f.$name.val(f.$name.val().trim().toUpperCase().replace(/\s+/g,''));
		},
		preset: function(preset) {
			var f = this.fields;

			switch(preset) {
				case '': 
					return false; 
				break;
				case 'email':
					f.$label.val("Email address");
					f.$name.val('EMAIL');
					f.$placeholder.val("Your email address");
					f.$required.prop('checked', true);
				break;
				case 'fname':
					f.$label.val('First name:');
					f.$name.val('FNAME');
					f.$placeholder.val("Your first name");
				break;
				case 'lname':
					f.$label.val('Last name:');
					f.$name.val('LNAME');
					f.$placeholder.val("Your last name");
				break;
				case 'name':
					f.$label.val('Name:');
					f.$name.val('NAME');
					f.$placeholder.val("Your name");
				break;
				case 'group':
					//f.$label.val('Group Name comes here');
					//f.$name.val('Group ID comes here');
				break;

			}
		},
		transferCodeToForm: function() {
			var f = this.fields;
			f.$form.val(f.$form.val() + "\n" + f.$preview.val());
		},
		validateSettings: function() {
			var html;

			html = this.fields.$form.val();

			// simple check to see if form mark-up contains the proper e-mail field
			if(html.indexOf('="EMAIL"') == -1) {
				return confirm('It seems that your form does not contain an input field for the email address.' + "\n\n"
					+ 'Please make sure your form contains an input field with a name="EMAIL" attribute.' + "\n\n"
					+ 'Example: <input type="text" name="EMAIL"....' + "\n\n"
					+ 'Click OK to save settings nonetheless or cancel to go back and edit the form mark-up.');
			}

			return true;
		}
	}

	// Events
	$("#mc4wp_ffd_field_type").change(function() {
		FormDesigner.setup($(this).val());		
	});
	$("#mc4wp_ffd_field_preset").change(function() {
		FormDesigner.preset($(this).val());
	});

	$("#mc4wp_ffd_fields :input").change(function() {
		FormDesigner.validateFields();
		FormDesigner.updatePreviewCode();
	});
	$("#mc4wp_ffd_add_to_form").click(function(e) { 
		FormDesigner.transferCodeToForm();
		return false;
	});

	$("#mc4wp-submit-form-settings").click(function(e) {
		return FormDesigner.validateSettings();
	});

	FormDesigner.fields.$form.bind('copy', function(e) {
		return alert("Use the [mc4wp-form] shortcode to render this form inside a page, post or widget.");
	});



})(jQuery);

