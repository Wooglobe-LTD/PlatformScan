var sr_temp;
$.fn.hasAttr = function(name) {
	return this.attr(name) !== undefined;
 };
var app = app || {};

// Utils
(function ($, app) {
    'use strict';

    app.utils = {};

    app.utils.formDataSuppoerted = (function () {
        return !!('FormData' in window);
    }());

}(jQuery, app));
$(function(){
	'use strict';
	
	$( document ).ajaxStart(function(){ 
		 altair_helpers.custom_preloader_show();					
	});

	$( document ).ajaxComplete(function() {
	  	altair_helpers.custom_preloader_hide();
	});
    sr_temp = $('#default_sr_template').selectize();

    if(sr_temp.length > 0){
        sr_temp = sr_temp[0].selectize;
    }
	$('#change_password').on('click',function(){
		$('#form_validation').parsley().reset();
		$('#form_validation')[0].reset();
		var modal = UIkit.modal("#password_model");
    	modal.show();
		
	});
	
	$('#site_settings').on('click',function(){
		$('#form_validation1').parsley().reset();
		$('#form_validation1')[0].reset();
		$.ajax({
			type 	: 'POST',
			url  	: base_url+'settings',
			data 	: {},
			success : function(data){
				data = JSON.parse(data);
                sr_temp.clearCache('option');
                sr_temp.clearOptions();
                sr_temp.addOption(data.templates);
                sr_temp.setValue(data.data.default_sr_template);
				var modal = UIkit.modal("#settings_model");
				modal.show();
				$.each(data.data,function(i,v){
					if(i == 'default_sr_template'){
                        //sr_temp.setValue(i, false);
					}else {
                        $('#' + i).val(v);
                    }
                    $('#'+i).focus();
				});
				
			}
		});
		
		
	});
	
	$('#password_save').on('click',function(e){
		
		e.preventDefault();
		
		$('#form_validation').submit();
		
	});
	
	$('#settins_save').on('click',function(e){
		
		e.preventDefault();
		
		$('#form_validation1').submit();
		
	});
	
	$('#form_validation').on('submit',function(e){
		
		e.preventDefault();
		
		$.ajax({
			type 	: "POST",
			url  	: base_url+"change_password",
			data    : $('#form_validation').serialize(),
			success : function(data){
				data = JSON.parse(data);
				if(data.code == 204){
					$('#err-msg').attr('data-message',data.message);
					$('#err-msg').click();
					setTimeout(function(){
						window.location = data.url;
					},1000);
				}else if(data.code == 201){
					$('#err-msg').attr('data-message',data.message);
					$('#err-msg').click();
					$('#form_validation').parsley().reset();
					$.each(data.error,function(i,v){
                        $('#'+i).parent().parent().find('.error').html(v);
                    });
					
				}else if(data.code == 200){
					$('#suc-msg').attr('data-message',data.message);
					$('#suc-msg').click();
					
					var modal = UIkit.modal("#password_model");
					modal.hide();
					$('#form_validation').parsley().reset();
					$('#form_validation')[0].reset();
				}else{
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}
				
			},
			error 	: function(){
				$('#err-msg').attr('data-message','Something is going wrong!');
				$('#err-msg').click();
			}
		});
		
	});
	
	$('#form_validation1').on('submit',function(e){
		
		e.preventDefault();
		
		$.ajax({
			type 	: "POST",
			url  	: base_url+"settings_save",
			data    : $('#form_validation1').serialize(),
			success : function(data){
				data = JSON.parse(data);
				if(data.code == 204){
					$('#err-msg').attr('data-message',data.message);
					$('#err-msg').click();
					setTimeout(function(){
						window.location = data.url;
					},1000);
				}else if(data.code == 201){
					$('#err-msg').attr('data-message',data.message);
					$('#err-msg').click();
					$('#form_validation1').parsley().reset();
					$.each(data.error,function(i,v){
                        $('#'+i).parent().parent().find('.error').html(v);
                    });
					
					
				}else if(data.code == 200){
					$('#suc-msg').attr('data-message',data.message);
					$('#suc-msg').click();
					
					var modal = UIkit.modal("#settings_model");
					modal.hide();
					$('#form_validation1').parsley().reset();
					$('#form_validation1')[0].reset();
				}else{
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}
				
			},
			error 	: function(){
				$('#err-msg').attr('data-message','Something is going wrong!');
				$('#err-msg').click();
			}
		});
		
	});

});

// Parsley validators
(function ($, app) {
    'use strict';

    window.Parsley
        .addValidator('filemaxmegabytes', {
            requirementType: 'string',
            validateString: function (value, requirement, parsleyInstance) {

                if (!app.utils.formDataSuppoerted) {
                    return true;
                }

                var file = parsleyInstance.$element[0].files;
                var maxBytes = requirement * 1048576;

                if (file.length == 0) {
                    return true;
                }

                return file.length === 1 && file[0].size <= maxBytes;

            },
            messages: {
                en: 'File is to big.'
            }
        })
        .addValidator('filemimetypes', {
            requirementType: 'string',
            validateString: function (value, requirement, parsleyInstance) {

                if (!app.utils.formDataSuppoerted) {
                    return true;
                }

                var file = parsleyInstance.$element[0].files;

                if (file.length == 0) {
                    return true;
                }

                var allowedMimeTypes = requirement.replace(/\s/g, "").split(',');
                return allowedMimeTypes.indexOf(file[0].type) !== -1;

            },
            messages: {
                en: 'File mime type not allowed.'
            }
        })
		.addValidator('filrequired', {
				requirementType: 'string',
				validateString: function (value, requirement, parsleyInstance) {

					if (!app.utils.formDataSuppoerted) {
						return true;
					}

					var file = parsleyInstance.$element[0].files;

					if (file.length == 0) {
						return false;
					}


				},
				messages: {
					en: 'This field is required.'
				}
			});

}(jQuery, app));
