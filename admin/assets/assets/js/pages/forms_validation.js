//  require altair_forms.parsley_validation_config(); ( altair_admin_common.js )

$(function() {
    // validation (parsley)
    altair_form_validation.init();
});

// validation (parsley)
altair_form_validation = {
    init: function() {
        var $formValidate = $('#form_validation');
        var $formValidate1 = $('#form_validation1');
        var $formValidate2 = $('#form_validation2');
        var $formValidate3 = $('#form_validation3');
        var $formValidate4 = $('#form_validation4');
        var $formValidate5 = $('#form_validation5');
        var $formValidate30 = $('#form_validation30');
        var $distribute = $('#distribute');
		if($formValidate.length){
			$formValidate
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($formValidate.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}
		
		if($formValidate1.length){
			$formValidate1
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($formValidate1.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}
		
		if($formValidate2.length){
			$formValidate2
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($formValidate2.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}
		
		if($formValidate3.length){
			$formValidate3
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($formValidate3.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}
		
		if($formValidate4.length){
			$formValidate4
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($formValidate4.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}
		
		if($formValidate5.length){
			$formValidate5
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($formValidate5.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}
		if($formValidate30.length){
			$formValidate30
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($formValidate5.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}
		if($distribute.length){
            $distribute
				.parsley({
					'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
				})
				.on('form:validated',function() {
					altair_md.update_input($distribute.find('.md-input-danger'));
				})
				.on('field:validated',function(parsleyField) {
					if($(parsleyField.$element).hasClass('md-input')) {
						altair_md.update_input( $(parsleyField.$element) );
					}
				});
		}

        window.Parsley.on('field:validate', function() {
            var $server_side_error = $(this.$element).closest('.md-input-wrapper').siblings('.error_server_side');
            if($server_side_error) {
                $server_side_error.hide();
            }
        });


        // datepicker callback
        $('#val_birth').on('hide.uk.datepicker', function() {
            $(this).parsley().validate();
        });
    }
};