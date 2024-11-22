$(function () {
    'use strict';


    $('#otp-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#otp-form').on('submit',function (e) {

        e.preventDefault();
        $.ajax({
            type 	: "POST",
            url  	: base_url+"verified_otp",
            data    : $('#otp-form').serialize(),
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#otp-form').parsley().reset();
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

    $('#regenerate_otp').on('click',function (e) {
       e.preventDefault();
       $.ajax({
           url :  base_url+"regenerate-OTP",
           data:  {token:token},
           dataType: 'JSON',
           type: 'POST',
           success: function (data) {
               if(data.code == 200){
                   toastr.success(data.message);
                   setTimeout(function(){
                       window.location = data.url;
                   },1000);
               }
           }
       });
    });

    window.Parsley.addValidator('otp', function (value, requirement) {
        var response = false;
        $.ajax({
            url: base_url+"validate_otp",
            data: {otp: value,token:token},
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
        .addMessage('en', 'otp', 'Please enter the valid OTP!');
});