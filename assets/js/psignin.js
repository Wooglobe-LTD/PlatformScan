$(function () {
    'use strict';

    $('#sign-in-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#sign-in-form').on('submit',function (e) {

        e.preventDefault();
        $('.error').html('');
        $.ajax({
            type 	: "POST",
            url  	: base_url+"partner-login",
            data    : $('#sign-in-form').serialize(),
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#sign-in-form').parsley().reset();
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

    window.Parsley.addValidator('users', function (value, requirement) {
            var response = false,
                email = $("#email").val();

            $.ajax({
                url: base_url+"partner_validate_user",
                data: {password: value, email: email},
                dataType: 'json',
                type: 'POST',
                async: false,
                success: function(data) {
                    // if you send something from the server, you might want to
                    // do some verification here
                    response = true;
                },
                error: function() {
                    response = false;
                }
            });

            return response;
        }, 32)
        .addMessage('en', 'username', 'Invalid email address or password.');
})