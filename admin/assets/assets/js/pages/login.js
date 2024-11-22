$(function() {
    // login_page
    altair_login_page.init();
});

// variables
var $login_card = $('#login_card'),
    $login_form = $('#login_form'),
    $login_help = $('#login_help'),
    $login_password_reset = $('#login_password_reset');

altair_login_page = {
    init: function () {
        // show login form (hide other forms)
        var login_form_show = function() {
            $login_form
                .show()
                .siblings()
                .hide();
        };

        // show login help (hide other forms)
        var login_help_show = function() {
            $login_help
                .show()
                .siblings()
                .hide();
        };

        // show password reset form (hide other forms)
        var password_reset_show = function() {
            $login_password_reset
                .show()
                .siblings()
                .hide();
        };

        $('#login_help_show').on('click',function(e) {
            e.preventDefault();
            // card animation & complete callback: login_help_show
            altair_md.card_show_hide($login_card,undefined,login_help_show,undefined);
        });

       
        $('.back_to_login').on('click',function(e) {
            e.preventDefault();
            $('#signup_form_show').fadeIn('280');
            // card animation & complete callback: login_form_show
            altair_md.card_show_hide($login_card,undefined,login_form_show,undefined);
        });

        $('#password_reset_show').on('click',function(e) {
            e.preventDefault();
            // card animation & complete callback: password_reset_show
            altair_md.card_show_hide($login_card,undefined,password_reset_show,undefined);
        });
		
		$('#submit_login').on('click',function(e){
			
			e.preventDefault();
			$('#form_validation').submit();
			
		});

        $(document).on('keypress',function(e){

            if(e.keyCode == 13){
                $('#form_validation').submit();
			}

        });
		
		$('#reset').on('click',function(e){
			
			e.preventDefault();
			$('#form_validation1').submit();
			
		});
		
		$('#form_validation').on('submit',function(e){
			
			e.preventDefault();
			altair_helpers.custom_preloader_show('regular');
			$.ajax({
				
				type 	: 'POST',
				url  	: base_url+'login',
				data 	: $('#form_validation').serialize(),
				success : function(data){
					data = JSON.parse(data);
					
					if(data.code == 201){

						if('show_recaptcha' in data && data['show_recaptcha'] == '1') {
							if ($('[name="g-recaptcha-response"]').length == 0) {
								var captchaContainer = null;
								var loadCaptcha = function() {
									captchaContainer = grecaptcha.render('grecaptcha', {
										'sitekey' : '6Lfc6n0UAAAAACLgEjaXFdpuG3SgsEQ10L_44AF0',
										'callback' : function(response) {
											console.log(response);
										}
									});
								};
								loadCaptcha();
							}
						}

						$('#err-msg').attr('data-message',data.message);
						$('#err-msg').click();
						$.each(data.error,function(i,v){
							if (i == 'g-recaptcha-response') {
                                $('#g-recaptcha-response-login_err').html(v);
							}
							else {
                                $('#'+i).parent().parent().find('.error').html(v);
							}
						});


					}else if(data.code == 200){
						$('#suc-msg').attr('data-message',data.message);
						$('#suc-msg').click();
						setTimeout(function(){
							window.location = data.url;
						},500);
						
					}
					else if (data.code == 403){
						altair_helpers.custom_preloader_hide();
						//$('#err-msg').attr('data-message',data.message);
						Swal.fire({
							icon:  'error',
							title: 'Account Blocked!',
							text:   data.message,
							//footer: '<p>Please wait for some time while your account is restored...</p>'
						})

						//$('#err-msg').click();
					}
					else{
						$('#err-msg').attr('data-message','Something is going wrong!');
						$('#err-msg').click();
					}
					altair_helpers.custom_preloader_hide();
				},
				error 	: function(){
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}
				
			});
			
		});
		
		$('#form_validation1').on('submit',function(e){
			
			e.preventDefault();
			altair_helpers.custom_preloader_show();
			$.ajax({
				
				type 	: 'POST',
				url  	: base_url+'reset',
				data 	: $('#form_validation1').serialize(),
				success : function(data){
					data = JSON.parse(data);
					
					if(data.code == 201){
						$('#err-msg').attr('data-message',data.message);
						$('#err-msg').click();
						$.each(data.error,function(i,v){
							$('#'+i).parent().parent().find('.error').html(v);
						});
					}else if(data.code == 200){
						$('#suc-msg').attr('data-message',data.message);
						$('#suc-msg').click();
						$('#signup_form_show').fadeIn('280');
            			altair_md.card_show_hide($login_card,undefined,login_form_show,undefined);
						
					}else{
						$('#err-msg').attr('data-message','Something is going wrong!');
						$('#err-msg').click();
					}
					altair_helpers.custom_preloader_hide();
				},
				error 	: function(){
					$('#err-msg').attr('data-message','Something is going wrong!');
					$('#err-msg').click();
				}
				
			});
			
		});
		
		$('input').on('focus',function(){
			$(this).parent().parent().find('.error').html('');
		});


    }
};