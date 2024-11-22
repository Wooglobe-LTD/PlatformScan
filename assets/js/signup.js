$(function () {
    'use strict';

    $('#sign-up-form').parsley({
        'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden]'
    });

    $('#sign-up-form').on('submit',function (e) {
        e.preventDefault();
        $('.error').html('');
        $.ajax({
            type 	: "POST",
            url  	: base_url+"register",
            data    : $('#sign-up-form').serialize(),
            success : function(data){
                data = JSON.parse(data);
                if(data.code == 204){
                    toastr.error(data.message);
                    setTimeout(function(){
                        window.location = data.url;
                    },1000);
                }else if(data.code == 201){

                    toastr.error(data.message);
                    $('#sign-up-form').parsley().reset();
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