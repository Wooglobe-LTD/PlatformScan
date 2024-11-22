$(function () {
    'use strict';

	window.Parsley.on('field:error', function (fieldInstance) {
		fieldInstance.$element.popover({
			trigger: 'manual',
			container: 'body',
			placement: 'right',
			html: 'true',
			content: function () {
				var password = fieldInstance.value;
				var v_content = '';
				var v_class;
				var v_color;
				var m_left;
				[
					{'v_text':'8 - 20 characters',             'v_regex':/^.{8,20}$/},
					{'v_text':'At least 1 number',             'v_regex':/[0-9]/},
					{'v_text':'At least 1 upper case letter',  'v_regex':/[A-Z]/},
					{'v_text':'At least 1 lower case letter',  'v_regex':/[a-z]/},
					{'v_text':'At least 1 special character',  'v_regex':/[^A-Za-z0-9\s_]/}
				].forEach(function(v) {
					v_class = 'times';
					v_color = 'da1515';
					m_left  = 7; 

					if (password.match(v.v_regex)) {
						v_class = 'check';
						v_color = '22ad22';		
						m_left  = 4; 						
					}
					v_content += '<div><span><i class="fa fa-'+v_class+'" style="color:#'+v_color+'"></i></span><span style="margin-left:'+m_left+'px">'+v.v_text+'</span></div>';
				});
				
				return v_content;		
			}
		}).popover('show');
		$('#password').css('border-color', 'red');
		$('.field-icon').css('margin-top', '-33px');
	});

	window.Parsley.on('field:success', function (fieldInstance) {
		fieldInstance.$element.popover('destroy');
		$('#password').css('border-color', '#cfd8dc');
	}); 

	$(".toggle-password").click(function() {
		$(this).toggleClass("fa-eye fa-eye-slash");
		var input = $($(this).attr("toggle"));
		if (input.attr("type") == "password") {
			input.attr("type", "text");
		} else {
			input.attr("type", "password");
		}
	});


    $('#reset-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#reset-form').on('submit',function (e) {

        e.preventDefault();
        $.ajax({
            type 	: "POST",
            url  	: base_url+"update_password",
            data    : $('#reset-form').serialize(),
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#reset-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){
                    toastr.success(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });
    $('#set-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#set-form').on('submit',function (e) {

        e.preventDefault();
        $.ajax({
            type 	: "POST",
            url  	: base_url+"new-update-password",
            data    : $('#set-form').serialize(),
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#reset-form').parsley().reset();
                    $.each(data.error,function(i,v){
                        $('#'+i+'_err').html(v);
                    });

                }else if(data.code == 200){
                    toastr.success(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else{
                    toastr.error(data.message);
                }

            },
            error 	: function(){
                toastr.error('Something is going wrong!');
            }
        });

    });


})